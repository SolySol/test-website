<?php
// Assuming you have a session started and a user ID stored
include 'components/connect.php';

// Fetch user details
$user_id = $_SESSION['user_id'] ?? null; // Check if user_id is set in the session
$first_name = '';

if ($user_id) {
    $query = "SELECT first_name FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($first_name);
    $stmt->fetch();
    $stmt->close();
}
?>

<section class="header" id="header">
    <div class="flex">
        <a href="#home" class="logo">M Ashley Suites</a>
        <div class="user-info">
            <?php if ($user_id && !empty($first_name)): ?>
                <span class="welcome-message">Hello, <strong><?php echo htmlspecialchars($first_name); ?></strong></span>
                <a href="logout.php" class="btn logout">Logout</a>
            <?php else: ?>
                <a class="btn" href="login.php">LOGIN</a>
                <a class="btn" href="login.php#register">SIGN UP</a>
            <?php endif; ?>
        </div>
        <div id="menu-btn" class="fas fa-bars"></div>
    </div>

    <nav class="navbar">
        <a href="index.php#header1">Home</a>
        <a href="reservation.php">Reservation</a>
        <a href="bookings.php">My Bookings</a>
        
    </nav>
</section>