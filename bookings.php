<?php
include 'components/connect.php';

session_start();

date_default_timezone_set('Asia/Manila');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:login.php');
    exit;
}

$success_msg = [];
$warning_msg = [];

// Handle rating submission
if (isset($_POST['submit_rating'])) {
    $reservation_id = filter_var($_POST['reservation_id'], FILTER_SANITIZE_NUMBER_INT);
    $rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);

    if ($rating >= 1 && $rating <= 5) {
        $update_rating = $conn->prepare("UPDATE reservations SET rating = ? WHERE id = ? AND user_id = ?");
        $update_rating->bind_param("iii", $rating, $reservation_id, $user_id);
        if ($update_rating->execute()) {
            $success_msg[] = 'Rating submitted successfully!';
        } else {
            $warning_msg[] = 'Failed to submit rating. Please try again.';
        }
    } else {
        $warning_msg[] = 'Invalid rating value.';
    }
}

// Automatically delete unpaid reservations with past check-out dates
$current_date = date('Y-m-d');

// Delete expired unpaid reservations
$delete_expired = $conn->prepare("DELETE rl FROM reservation_line rl 
    JOIN reservations r ON rl.reservation_id = r.id 
    WHERE r.user_id = ? AND r.status = 'Unpaid' AND rl.check_out < ?");
$delete_expired->bind_param("is", $user_id, $current_date);
$delete_expired->execute();

// Process completed reservations
$complete_reservations = $conn->prepare("SELECT r.id AS reservation_id, rl.room_id 
    FROM reservations r 
    JOIN reservation_line rl ON r.id = rl.reservation_id 
    WHERE r.user_id = ? AND r.status = 'Paid' 
    AND rl.check_out < ?");
$complete_reservations->bind_param("is", $user_id, $current_date);
$complete_reservations->execute();
$completed_results = $complete_reservations->get_result();

while ($row = $completed_results->fetch_assoc()) {
    $reservation_id = $row['reservation_id'];
    $room_id = $row['room_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Mark reservation as completed
        $mark_completed = $conn->prepare("UPDATE reservations SET status = 'Completed' WHERE id = ? AND status = 'Paid'");
        $mark_completed->bind_param("i", $reservation_id);
        $mark_completed->execute();
        
        // Only increment room count if the status was actually updated (was previously 'Paid')
        if ($mark_completed->affected_rows > 0) {
            // Increment the number of available rooms
            $update_room_count = $conn->prepare("UPDATE rooms SET number_of_rooms = number_of_rooms + 1 WHERE id = ?");
            $update_room_count->bind_param("i", $room_id);
            $update_room_count->execute();
        }
        
        // Commit transaction
        $conn->commit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $warning_msg[] = 'Error updating room availability: ' . $e->getMessage();
    }
}

// Handle booking cancellation
if (isset($_POST['cancel'])) {
    $booking_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    // Verify the booking exists and fetch details
    $verify_booking = $conn->prepare("SELECT rl.room_id, r.status FROM reservations r 
        JOIN reservation_line rl ON r.id = rl.reservation_id 
        WHERE r.id = ? AND r.user_id = ?");
    $verify_booking->bind_param("ii", $booking_id, $user_id);
    $verify_booking->execute();
    $result = $verify_booking->get_result();

    if ($result->num_rows > 0) {
        $fetch_booking = $result->fetch_assoc();
        $room_id = $fetch_booking['room_id'];
        $status = $fetch_booking['status'];

        // Start transaction
        $conn->begin_transaction();

        try {
            // Delete reservation lines
            $delete_details = $conn->prepare("DELETE FROM reservation_line WHERE reservation_id = ?");
            $delete_details->bind_param("i", $booking_id);
            $delete_details->execute();

            // Delete reservation record
            $delete_booking = $conn->prepare("DELETE FROM reservations WHERE id = ?");
            $delete_booking->bind_param("i", $booking_id);
            $delete_booking->execute();

            // Update room availability ONLY if the reservation was paid
            if ($status === 'Paid') {
                $update_rooms = $conn->prepare("UPDATE rooms SET number_of_rooms = number_of_rooms + 1 WHERE id = ?");
                $update_rooms->bind_param("i", $room_id);
                $update_rooms->execute();
            }

            // Commit transaction
            $conn->commit();
            $success_msg[] = 'Booking cancelled successfully!';
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $warning_msg[] = 'Error cancelling booking. Please try again.';
        }
        
    } else {
        $warning_msg[] = 'Booking not found or already cancelled!';
    }
}

// Date filter for paid reservations
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$date_filter = "";

if (!empty($start_date) && !empty($end_date)) {
    $date_filter = "AND p.payment_date BETWEEN ? AND ?";
}

// Determine active tab
$active_tab = isset($_POST['start_date']) && isset($_POST['end_date']) ? 'paid' : 'unpaid';

// Fetch unpaid reservations
$unpaid_reservations = $conn->prepare("SELECT r.id, r.status, rl.room_id, rl.check_in, rl.check_out, rl.subtotal, rm.room_type 
    FROM reservations r 
    JOIN reservation_line rl ON r.id = rl.reservation_id 
    JOIN rooms rm ON rl.room_id = rm.id 
    WHERE r.user_id = ? AND r.status = 'Unpaid'");
$unpaid_reservations->bind_param("i", $user_id);
$unpaid_reservations->execute();
$unpaid_result = $unpaid_reservations->get_result();

// Fetch paid reservations
$query = "SELECT r.id, r.status, rl.room_id, rl.check_in, rl.check_out, rl.subtotal, rm.room_type, 
    p.amount, p.payment_date, p.payment_time, r.rating 
    FROM reservations r 
    JOIN reservation_line rl ON r.id = rl.reservation_id 
    JOIN rooms rm ON rl.room_id = rm.id 
    LEFT JOIN payment p ON r.id = p.reservation_id 
    WHERE r.user_id = ? AND r.status IN ('Paid', 'Completed') $date_filter";
$paid_reservations = $conn->prepare($query);

if (!empty($start_date) && !empty($end_date)) {
    $paid_reservations->bind_param("iss", $user_id, $start_date, $end_date);
} else {
    $paid_reservations->bind_param("i", $user_id);
}
$paid_reservations->execute();
$paid_result = $paid_reservations->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/jpg" href="images/logo.jpg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body data-active="<?= htmlspecialchars($active_tab); ?>">

<?php include 'components/user_header.php'; ?>

<section class="bookings">
    <h1 class="heading">My Bookings</h1>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab-button" data-tab="unpaid">Unpaid Reservations</div>
        <div class="tab-button" data-tab="paid">Paid Reservations</div>
    </div>

    <!-- Unpaid Reservations -->
    <div id="unpaid" class="tab-content">
        <div class="box-container">
            <?php
            if ($unpaid_result->num_rows > 0) {
                while ($fetch_booking = $unpaid_result->fetch_assoc()) {
            ?>
                <div class="box">
                    <p>Room Type: <span><?= htmlspecialchars($fetch_booking['room_type']); ?></span></p>
                    <p>Check In: <span><?= htmlspecialchars($fetch_booking['check_in']); ?></span></p>
                    <p>Check Out: <span><?= htmlspecialchars($fetch_booking['check_out']); ?></span></p>
                    <p>Subtotal: <span>₱<?= htmlspecialchars(number_format($fetch_booking['subtotal'], 2)); ?></span></p>
                    <p>Status: <span><?= htmlspecialchars($fetch_booking['status']); ?></span></p>

                    <!-- Cancel Booking -->
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($fetch_booking['id']); ?>">
                        <input type="submit" value="Cancel Booking" name="cancel" class="btn" onclick="return confirm('Are you sure you want to cancel this booking?');">
                    </form>

                    <!-- Pay Now -->
                    <form action="create_payment.php" method="POST">
                        <input type="hidden" name="reservation_id" value="<?= htmlspecialchars($fetch_booking['id']); ?>">
                        <button type="submit" class="btn">Pay Now</button>
                    </form>
                </div>
            <?php
                }
            } else {
                echo '<div class="box" style="text-align: center;">No unpaid reservations found!</div>';
            }
            ?>
        </div>
    </div>

    <!-- Paid Reservations -->
    <div id="paid" class="tab-content">
        <div class="date-filter">
            <form action="bookings.php#paid" method="POST">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" value="<?= htmlspecialchars($start_date); ?>">

                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" value="<?= htmlspecialchars($end_date); ?>">

                <button type="submit" class="btn">Filter</button>
            </form>
            <form action="bookings.php#paid" method="POST" style="display: inline;">
                <input type="hidden" name="start_date" value="">
                <input type="hidden" name="end_date" value="">
                <button type="submit" class="btn">Clear Filter</button>
            </form>
        </div>

        <div class="box-container">
            <?php
            if ($paid_result->num_rows > 0) {
                while ($fetch_booking = $paid_result->fetch_assoc()) {
                    $reservation_id = htmlspecialchars($fetch_booking['id']);
                    $rating = htmlspecialchars($fetch_booking['rating'] ?? 'Not rated');
            ?>
                <div class="box">
                    <p>Room Type: <span><?= htmlspecialchars($fetch_booking['room_type']); ?></span></p>
                    <p>Check In: <span><?= htmlspecialchars($fetch_booking['check_in']); ?></span></p>
                    <p>Check Out: <span><?= htmlspecialchars($fetch_booking['check_out']); ?></span></p>
                    <p>Status: <span><?= htmlspecialchars($fetch_booking['status']); ?></span></p>
                    <p>Payment Date: <span><?= htmlspecialchars($fetch_booking['payment_date'] ?? 'N/A'); ?></span></p>
                    <p>Payment Time: <span><?= htmlspecialchars($fetch_booking['payment_time'] ?? 'N/A'); ?></span></p>
                    <p>Amount: <span>₱<?= htmlspecialchars($fetch_booking['amount'] ?? '0.00'); ?></span></p>

                    <?php if ($fetch_booking['status'] === 'Completed') { ?>
                        <p>Rating: <span><?= $rating === 'Not rated' ? 'Not rated yet' : "$rating / 5"; ?></span></p>

                        <!-- Show rating form if not rated -->
                        <?php if ($rating === 'Not rated') { ?>
                        <form action="" method="POST">
                            <input type="hidden" name="reservation_id" value="<?= $reservation_id; ?>">
                            <label for="rating">Rate this reservation:</label>
                            <select name="rating" required>
                                <option value="" disabled selected>Select</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Fair</option>
                                <option value="3">3 - Good</option>
                                <option value="4">4 - Very Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                            <button type="submit" name="submit_rating" class="btn">Submit Rating</button>
                        </form>
                        <?php } else { ?>
                            <!-- Add star display for rated reservations -->
                            <div class="rating-stars">
                                <?php for($i = 1; $i <= 5; $i++) { ?>
                                    <i class="fas fa-star <?= $i <= $rating ? 'active' : ''; ?>"></i>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php
                }
            } else {
                echo '<div class="box" style="text-align: center;">No paid reservations found!</div>';
            }
            ?>
        </div>
    </div>
</section>


<script>
 <?php
    if (!empty($warning_msg)) {
        foreach ($warning_msg as $msg) {
            echo "
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: '" . addslashes($msg) . "',
                customClass: {
                    container: 'swal-warning',
                    popup: 'swal-warning',
                    icon: 'swal-icon',
                    title: 'swal-title',
                    text: 'swal-text'
                },
                showConfirmButton: true,
                confirmButtonColor: '#ffc107',
                timer: 3000,
                timerProgressBar: true
            });";
        }
    }

    if (!empty($success_msg)) {
        foreach ($success_msg as $msg) {
            echo "
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '" . addslashes($msg) . "',
                customClass: {
                    container: 'swal-success',
                    popup: 'swal-success',
                    icon: 'swal-icon',
                    title: 'swal-title',
                    text: 'swal-text'
                },
                showConfirmButton: true,
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true
            });";
        }
    }
    ?>

// Rating submission confirmation
function confirmRating(formElement) {
    event.preventDefault();
    Swal.fire({
        title: 'Submit Rating',
        text: 'Are you sure you want to submit this rating?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit!'
    }).then((result) => {
        if (result.isConfirmed) {
            formElement.submit();
        }
    });
}

// Tab functionality
const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');
const activeTab = document.body.getAttribute('data-active');

// Set the correct active tab on page load
tabButtons.forEach(button => {
    if (button.dataset.tab === activeTab) {
        button.classList.add('active');
        document.getElementById(activeTab).classList.add('active');
    } else {
        button.classList.remove('active');
        document.getElementById(button.dataset.tab).classList.remove('active');
    }
});

// Handle tab button clicks
tabButtons.forEach(button => {
    button.addEventListener('click', () => {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        button.classList.add('active');
        document.getElementById(button.dataset.tab).classList.add('active');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
