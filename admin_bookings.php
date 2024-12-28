<?php
session_start();
include 'components/connect.php';

if (isset($_SESSION['admin_id'])) {
    $user_id = $_SESSION['admin_id'];
} else {
    header('location:login.php');
    exit;
}

// Handle deletion of bookings
if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = mysqli_prepare($conn, "SELECT * FROM `reservations` WHERE id = ?");
    mysqli_stmt_bind_param($verify_delete, 's', $delete_id);
    mysqli_stmt_execute($verify_delete);
    $result = mysqli_stmt_get_result($verify_delete);

    if (mysqli_num_rows($result) > 0) {
        $delete_reservations = mysqli_prepare($conn, "DELETE FROM `reservations` WHERE id = ?");
        mysqli_stmt_bind_param($delete_reservations, 's', $delete_id);
        mysqli_stmt_execute($delete_reservations);
        $success_msg[] = 'Booking deleted!';
    } else {
        $warning_msg[] = 'Booking already deleted!';
    }
}

// Handle updating the reservation status
if (isset($_POST['update_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $new_status = $_POST['status'];
    $update_status = mysqli_prepare($conn, "UPDATE `reservations` SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($update_status, 'si', $new_status, $reservation_id);
    mysqli_stmt_execute($update_status);
    $success_msg[] = 'Reservation status updated successfully!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">

    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background-color: #1E1E1E;
            color: #333;
            line-height: 1.6;
        }

        .user-details {
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .user-details p {
            margin: 0.2rem 0;
            font-size: 14px;
        }

        /* Search box styles */
        .search-section {
            margin-bottom: 2rem;
            width: 100%;
            max-width: 600px;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 8px;
            padding: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-input {
            flex: 1;
            padding: 0.8rem;
            border: none;
            outline: none;
            font-size: 15px;
            background: transparent;
        }

        .search-button {
            background: none;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            color: #666;
        }

        .search-button:hover {
            color: #333;
        }

        .booking-card.hidden {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <div class="container">
        <div class="heading-section">
            <h1>Bookings</h1>
        </div>

        <!-- Search Box -->
        <div class="search-section">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by name..." class="search-input">
                <button class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <div class="bookings-container">
            <?php
            $select_users = mysqli_query($conn, "SELECT DISTINCT u.id, u.first_name, u.last_name, u.email, u.number FROM users u JOIN reservations r ON u.id = r.user_id");

            if (mysqli_num_rows($select_users) > 0) {
                while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                    $user_id = $fetch_users['id'];
                    $user_name = htmlspecialchars($fetch_users['first_name'] . ' ' . $fetch_users['last_name']);
            ?>
                <div class="booking-card">
                    <div class="booking-card-header" onclick="toggleReservations('user-<?= $user_id; ?>')">
                        <h3><?= $user_name; ?></h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>

                    <div id="user-<?= $user_id; ?>" class="booking-card-content">
                        <!-- User details -->
                        <div class="user-details">
                            <p><strong>Email:</strong> <?= htmlspecialchars($fetch_users['email']); ?></p>
                            <p><strong>Phone Number:</strong> <?= htmlspecialchars($fetch_users['number']); ?></p>
                        </div>

                        <!-- Unpaid Reservations -->
                        <div class="reservation-section">
                            <h4>Unpaid Reservations</h4>
                            <?php
                            $unpaid_reservations = mysqli_query($conn, "
                                SELECT r.id, rl.check_in, rl.check_out, rl.room_id, rl.subtotal, rooms.room_type 
                                FROM reservations r 
                                JOIN reservation_line rl ON rl.reservation_id = r.id 
                                JOIN rooms ON rl.room_id = rooms.id 
                                WHERE r.user_id = '$user_id' AND r.status = 'Unpaid'");

                            if (mysqli_num_rows($unpaid_reservations) > 0) {
                                while ($unpaid = mysqli_fetch_assoc($unpaid_reservations)) {
                            ?>
                                <div class="reservation-details">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span class="detail-label">Check-in:</span>
                                            <span class="detail-value"><?= htmlspecialchars($unpaid['check_in']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-check"></i>
                                            <span class="detail-label">Check-out:</span>
                                            <span class="detail-value"><?= htmlspecialchars($unpaid['check_out']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-door-open"></i>
                                            <span class="detail-label">Room:</span>
                                            <span class="detail-value"><?= htmlspecialchars($unpaid['room_type']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span class="detail-label">Total:</span>
                                            <span class="detail-value">₱<?= htmlspecialchars($unpaid['subtotal']); ?></span>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                        <span class="detail-label">Booking ID: <?= htmlspecialchars($unpaid['id']); ?></span>
                                        <span class="status-badge status-unpaid">Unpaid</span>
                                    </div>
                                </div>
                            <?php
                                }
                            } else {
                                echo '<p class="no-reservations">No unpaid reservations</p>';
                            }
                            ?>
                        </div>

                        <!-- Paid Reservations -->
                        <div class="reservation-section">
                            <h4>Paid Reservations</h4>
                            <?php
                            $paid_reservations = mysqli_query($conn, "
                                SELECT r.id, rl.check_in, rl.check_out, rl.room_id, rl.subtotal, rooms.room_type 
                                FROM reservations r 
                                JOIN reservation_line rl ON rl.reservation_id = r.id 
                                JOIN rooms ON rl.room_id = rooms.id 
                                WHERE r.user_id = '$user_id' AND r.status = 'paid'");

                            if (mysqli_num_rows($paid_reservations) > 0) {
                                while ($paid = mysqli_fetch_assoc($paid_reservations)) {
                            ?>
                                <div class="reservation-details">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span class="detail-label">Check-in:</span>
                                            <span class="detail-value"><?= htmlspecialchars($paid['check_in']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-check"></i>
                                            <span class="detail-label">Check-out:</span>
                                            <span class="detail-value"><?= htmlspecialchars($paid['check_out']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-door-open"></i>
                                            <span class="detail-label">Room:</span>
                                            <span class="detail-value"><?= htmlspecialchars($paid['room_type']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span class="detail-label">Total:</span>
                                            <span class="detail-value">₱<?= htmlspecialchars($paid['subtotal']); ?></span>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                        <span class="detail-label">Booking ID: <?= htmlspecialchars($paid['id']); ?></span>
                                        <span class="status-badge status-paid">Paid</span>
                                    </div>
                                </div>
                            <?php
                                }
                            } else {
                                echo '<p class="no-reservations">No paid reservations</p>';
                            }
                            ?>
                        </div>

                        <!-- Complete Reservations -->
                        <div class="reservation-section">
                            <h4>Completed Reservations</h4>
                            <?php
                            $completed_reservations = mysqli_query($conn, "
                                SELECT r.id, rl.check_in, rl.check_out, rl.room_id, rl.subtotal, rooms.room_type 
                                FROM reservations r 
                                JOIN reservation_line rl ON rl.reservation_id = r.id 
                                JOIN rooms ON rl.room_id = rooms.id 
                                WHERE r.user_id = '$user_id' AND r.status = 'Completed'");

                            if (mysqli_num_rows($completed_reservations) > 0) {
                                while ($completed = mysqli_fetch_assoc($completed_reservations)) {
                            ?>
                                <div class="reservation-details">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span class="detail-label">Check-in:</span>
                                            <span class="detail-value"><?= htmlspecialchars($completed['check_in']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-calendar-check"></i>
                                            <span class="detail-label">Check-out:</span>
                                            <span class="detail-value"><?= htmlspecialchars($completed['check_out']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-door-open"></i>
                                            <span class="detail-label">Room:</span>
                                            <span class="detail-value"><?= htmlspecialchars($completed['room_type']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span class="detail-label">Total:</span>
                                            <span class="detail-value">₱<?= htmlspecialchars($completed['subtotal']); ?></span>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                        <span class="detail-label">Booking ID: <?= htmlspecialchars($completed['id']); ?></span>
                                        <span class="status-badge status-paid">Complete</span>
                                    </div>
                                </div>
                            <?php
                                }
                            } else {
                                echo '<p class="no-reservations">No completed reservations</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo '<div class="booking-card"><p class="no-reservations">No users found!</p></div>';
            }
            ?>
        </div>
    </div>

    <script>
        function toggleReservations(userId) {
            const content = document.getElementById(userId);
            const header = content.previousElementSibling;
            const icon = header.querySelector('i');
            
            content.classList.toggle('visible');
            
            // Toggle icon
            if (content.classList.contains('visible')) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const bookingCards = document.querySelectorAll('.booking-card');

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                bookingCards.forEach(card => {
                    const userName = card.querySelector('h3').textContent.toLowerCase();
                    if (userName.includes(searchTerm)) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/admin_script.js"></script>
    <?php include 'components/message.php'; ?>
</body>
</html>