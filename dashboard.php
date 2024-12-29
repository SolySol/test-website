<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['admin_id'])){
   $admin_id = $_SESSION['admin_id'];
}else{
   header('location:login.php');
   exit;
}

// Calculate total revenue from paid reservations only
$select_revenue = mysqli_query($conn, "SELECT SUM(total_cost) as total_revenue 
   FROM reservations 
   WHERE status IN ('Paid', 'Completed')");
$revenue_data = mysqli_fetch_assoc($select_revenue);
$total_revenue = $revenue_data['total_revenue'] ?? 0;

// Get paid and unpaid reservation counts
$select_paid = mysqli_query($conn, "SELECT COUNT(*) as paid_count FROM reservations WHERE status IN ('Paid', 'Completed')");
$paid_data = mysqli_fetch_assoc($select_paid);
$paid_count = $paid_data['paid_count'];

$select_unpaid = mysqli_query($conn, "SELECT COUNT(*) as unpaid_count FROM reservations WHERE status = 'Unpaid'");
$unpaid_data = mysqli_fetch_assoc($select_unpaid);
$unpaid_count = $unpaid_data['unpaid_count'];

// Calculate average rating
$select_avg_rating = mysqli_query($conn, "SELECT AVG(rating) as avg_rating, COUNT(*) as total_ratings 
   FROM reservations WHERE rating IS NOT NULL");
$rating_data = mysqli_fetch_assoc($select_avg_rating);
$avg_rating = number_format($rating_data['avg_rating'] ?? 0, 1);
$total_ratings = $rating_data['total_ratings'] ?? 0;

// Get recent reservations with user information and ratings
$select_recent = mysqli_query($conn, "SELECT r.*, u.first_name, u.last_name 
   FROM reservations r 
   JOIN users u ON r.user_id = u.id 
   ORDER BY r.id DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="icon" type="image/jpg" href="images/logo.jpg">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
     

   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">
   <h1 class="heading">Dashboard Overview</h1>

   <div class="box-container">
      <!-- Reservations Box -->
      <div class="box">
         <i class="fas fa-calendar-check icon-stat"></i>
         <?php
         $select_reservations = mysqli_query($conn, "SELECT * FROM `reservations`");
         $count_reservations = mysqli_num_rows($select_reservations);
         ?>
         <h3><?= number_format($count_reservations); ?></h3>
         <p>Total Reservations</p>
         <div class="stats-info">
            <span class="stat-pill paid-pill">
               <i class="fas fa-check"></i> <?= $paid_count ?> Paid
            </span>
            <span class="stat-pill unpaid-pill">
               <i class="fas fa-clock"></i> <?= $unpaid_count ?> Unpaid
            </span>
         </div>
         <a href="admin_bookings.php" class="btn">View Reservations</a>
      </div>

      <!-- Users Box -->
      <div class="box">
         <i class="fas fa-users icon-stat"></i>
         <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users`");
         $count_users = mysqli_num_rows($select_users);
         ?>
         <h3><?= number_format($count_users); ?></h3>
         <p>Registered Users</p>
         <!-- <a href="admins.php" class="btn">Manage Users</a> -->
      </div>

      <!-- Revenue Box -->
      <div class="box">
         <i class="fas fa-dollar-sign icon-stat"></i>
         <h3>₱<?= number_format($total_revenue, 2); ?></h3>
         <p>Total Revenue</p>
         <div class="stats-info">
            <span class="stat-pill paid-pill">
               From <?= $paid_count ?> Paid Reservations
            </span>
         </div>
      </div>

      <!-- Ratings Box -->
      <div class="box">
         <i class="fas fa-star icon-stat"></i>
         <h3><?= $avg_rating ?> <span class="rating-stars">★</span></h3>
         <p>Average Rating</p>
         <div class="stats-info">
            <span class="stat-pill rating-pill">
               <i class="fas fa-poll"></i> <?= $total_ratings ?> Total Ratings
            </span>
         </div>
      </div>
   </div>

   <!-- Recent Bookings Table -->
   <div class="recent-bookings">
      <h2>Recent Reservations</h2>
      <table>
         <thead>
            <tr>
               <th>ID</th>
               <th>Guest Name</th>
               <th>Total Cost</th>
               <th>Status</th>
               <th>Rating</th>
            </tr>
         </thead>
         <tbody>
            <?php while($booking = mysqli_fetch_assoc($select_recent)) : ?>
            <tr>
               <td>#<?= $booking['id']; ?></td>
               <td><?= htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
               <td>₱<?= number_format($booking['total_cost'], 2); ?></td>
               <td>
                  <span class="status <?= strtolower($booking['status']); ?>">
                     <?= ucfirst($booking['status']); ?>
                  </span>
               </td>
               <td>
                  <?php if($booking['rating']): ?>
                     <span class="rating-stars"><?= str_repeat('★', $booking['rating']) ?></span>
                     <span class="rating-stars" style="color: #ddd;"><?= str_repeat('★', 5 - $booking['rating']) ?></span>
                  <?php else: ?>
                     <span style="color: var(--text-light);">No rating</span>
                  <?php endif; ?>
               </td>
            </tr>
            <?php endwhile; ?>
         </tbody>
      </table>
   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/admin_script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>