<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:login.php');
    exit;
}

$warning_msg = [];
$success_msg = [];

// Fetch available room types from the 'rooms' table
$available_rooms = $conn->query("SELECT id, room_type, price, number_of_rooms FROM rooms");

// Booking Room
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $room_id = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
    $check_in = $_POST['check_in'];
    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
    $check_out = $_POST['check_out'];
    $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);

    // Get today's date
    $today = date('Y-m-d');

    // Validate the check-in and check-out dates
    if ($check_in < $today) {
        $warning_msg[] = 'You cannot book a room for a past date!';
    } elseif ($check_in === $check_out) {
        $warning_msg[] = 'Check-in and check-out dates cannot be the same!';
    } elseif ($check_out < $check_in) {
        $warning_msg[] = 'Check-out date must be after the check-in date!';
    } elseif ($room_id === 0) {
        $warning_msg[] = 'Please select a valid room.';
    } else {
        $check_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $check_user->bind_param("i", $user_id);
        $check_user->execute();
        $result = $check_user->get_result();

        if ($result->num_rows === 0) {
            $warning_msg[] = 'User not found, please login again!';
        } else {
            $check_availability = $conn->prepare("SELECT price, number_of_rooms FROM rooms WHERE id = ?");
            $check_availability->bind_param("i", $room_id);
            $check_availability->execute();
            $room_data = $check_availability->get_result()->fetch_assoc();

            if ($room_data['number_of_rooms'] <= 0) {
                $warning_msg[] = 'No more rooms available for this room type!';
            } else {
                $price_per_night = $room_data['price'];
                $date1 = new DateTime($check_in);
                $date2 = new DateTime($check_out);
                $nights = $date1->diff($date2)->days;
                if ($nights < 1) $nights = 1;
                $subtotal = $price_per_night * $nights;

                $timestamp = date('Y-m-d H:i:s');
                $book_reservation = $conn->prepare("INSERT INTO reservations (user_id, total_cost, status, created_at) VALUES (?, ?, 'unpaid', ?)");
                $book_reservation->bind_param("iis", $user_id, $subtotal, $timestamp);
                $book_reservation->execute();
                $reservation_id = $conn->insert_id;

                $book_line = $conn->prepare("INSERT INTO reservation_line (room_id, reservation_id, check_in, check_out, subtotal) VALUES (?, ?, ?, ?, ?)");
                $book_line->bind_param("iissi", $room_id, $reservation_id, $check_in, $check_out, $subtotal);
                $book_line->execute();

                $success_msg[] = "Room booked successfully for $nights night(s)! Subtotal: $$subtotal";

                // Redirect to avoid re-submitting the form on refresh
                header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
                exit;
            }
        }
    }
}

// Check for success messages in the query string
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_msg[] = 'Your booking was completed successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/jpg" href="images/logo.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="reservation" id="reservation">
    <form action="" method="post">
        <h3>Make a Reservation</h3>
        <div class="flex">
            <div class="box">
                <p>Check-in <span>*</span></p>
                <input type="text" id="check_in" name="check_in" class="input" required>
            </div>
            <div class="box">
                <p>Check-out <span>*</span></p>
                <input type="text" id="check_out" name="check_out" class="input" required>
            </div>
            <div class="box">
                <p>Room Type <span>*</span></p>
                <select name="room_id" id="room_id" class="input" required>
                    <?php
                    if ($available_rooms->num_rows > 0) {
                        while ($room = $available_rooms->fetch_assoc()) {
                            $room_status = $room['number_of_rooms'] > 0 ? '' : ' (Fully Booked)';
                            $disabled = $room['number_of_rooms'] > 0 ? '' : 'disabled';
                            echo '<option value="' . htmlspecialchars($room['id']) . '" ' . $disabled . '>' . htmlspecialchars($room['room_type']) . ' - ₱' . htmlspecialchars($room['price']) . ' per night (' . $room['number_of_rooms'] . ' available)' . $room_status . '</option>';
                        }
                    } else {
                        echo '<option value="">No rooms available</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <input type="submit" value="Book Now" name="book" class="btn">
    </form>
</section>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkInField = document.getElementById('check_in');
        const checkOutField = document.getElementById('check_out');
        const roomSelect = document.getElementById('room_id');

        let unavailableDates = [];

        function fetchUnavailableDates(roomId) {
            fetch(`get_unavailable_dates.php?room_id=${roomId}`)
                .then(response => response.json())
                .then(dates => {
                    unavailableDates = dates;
                    initializeFlatpickr();
                });
        }

        function initializeFlatpickr() {
            flatpickr(checkInField, {
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: unavailableDates,
                onChange: function(selectedDates, dateStr) {
                    checkOutField.flatpickr.set('minDate', dateStr);
                }
            });

            flatpickr(checkOutField, {
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: unavailableDates,
            });
        }

        fetchUnavailableDates(roomSelect.value);

        roomSelect.addEventListener('change', function () {
            fetchUnavailableDates(this.value);
        });
    });

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
</script>

</body>
</html>
