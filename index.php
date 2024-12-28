<?php

include 'components/connect.php';

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null; // Set to null if not logged in
}

// Redirect to login if reservation actions are attempted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($user_id)) {
        header('Location: login.php');
        exit;
    }
}

if(isset($_POST['check'])){

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->bind_param("s", $check_in);
   $check_bookings->execute();
   $result = $check_bookings->get_result();

   while($fetch_bookings = $result->fetch_assoc()){
      $total_rooms += $fetch_bookings['rooms'];
   }

   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{
      $success_msg[] = 'rooms are available';
   }

}

if(isset($_POST['book'])){

   $rooms = $_POST['rooms'];
   $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
   $check_out = $_POST['check_out'];
   $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
   $adults = $_POST['adults'];
   $adults = filter_var($adults, FILTER_SANITIZE_STRING);
   $childs = $_POST['childs'];
   $childs = filter_var($childs, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->bind_param("s", $check_in);
   $check_bookings->execute();
   $result = $check_bookings->get_result();

   while($fetch_bookings = $result->fetch_assoc()){
      $total_rooms += $fetch_bookings['rooms'];
   }

   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->bind_param("isssii", $user_id, $rooms, $check_in, $check_out, $adults, $childs);
      $verify_bookings->execute();
      $result = $verify_bookings->get_result();

      if($result->num_rows > 0){
         $warning_msg[] = 'room booked already!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `bookings`(user_id, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?)");
         $book_room->bind_param("isssii", $user_id, $rooms, $check_in, $check_out, $adults, $childs);
         $book_room->execute();
         $success_msg[] = 'room booked successfully!';
      }

   }

}

if(isset($_POST['send'])){

   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE user_id = ? AND message = ?");
   $verify_message->bind_param("is", $user_id, $message);
   $verify_message->execute();
   $result = $verify_message->get_result();

   if($result->num_rows > 0){
      $warning_msg[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, message) VALUES(?,?)");
      $insert_message->bind_param("is", $user_id, $message);
      $insert_message->execute();
      $success_msg[] = 'message sent successfully!';
   }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>M Ashley Suites</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>


<!-- header -->

<!-- Header Section -->
<header class="header1" id="header1">
    <!-- <div class="head-top">
        <div class="site-name"><span>M Ashley Suites</span></div>
    </div> -->
    <div class="head-bottom flex">
        <h2>"Where Every Stay Feels Like Home"</h2>
        
        <!-- <?php if (!$user_id): ?>
            <p>Log in or sign up to reserve your perfect room today!</p>
            <a class="head-btn" href="login.php">LOGIN</a>
            <a class="head-btn" href="login.php#register">SIGN UP</a>
        <?php endif; ?>-->
        <?php if ($user_id): ?>
            <!-- <p>Welcome to M Ashley Suites.</p>
            <a class="head-btn" href="index.php#header">BOOK NOW</a> -->
            
        <?php endif; ?> 
        <p>Welcome to M Ashley Suites.</p>
        <a class="head-btn" href="index.php#header">BOOK NOW</a>
    </div>
</header>
        <!-- end of header -->

<!-- home section starts  -->
<?php include 'components/user_header.php'; ?>



<!-- home section ends -->






<!-- Rooms Section -->
<section class="rooms sec-width" id="rooms">
    <div class="title"><h2>Rooms</h2></div>
    <div class="rooms-container">
        <!-- Suite Room -->
        <article class="room">
            <div class="room-image"><img src="images/suiteRoom.jpg" alt="Suite Room"></div>
            <div class="room-text">
                <h3>Suite Room</h3>
                <p>Good for 2 Persons. Check-in Time: 12noon.</p>
                <p class="rate"><span>₱ 2,000.00 /</span> 24hrs</p>
                <a class="btn" href="<?= $user_id ? 'reservation.php' : 'login.php'; ?>">BOOK NOW</a>
            </div>
        </article>
        <!-- Standard Room -->
        <article class="room">
            <div class="room-image"><img src="images/standard1.jpg" alt="Standard Room"></div>
            <div class="room-text">
                <h3>Standard Room</h3>
                <p>Single bed good for 2 persons.</p>
                <p class="rate"><span>₱ 1,200.00 /</span> Per Night</p>
                <a class="btn" href="<?= $user_id ? 'reservation.php' : 'login.php'; ?>">BOOK NOW</a>
            </div>
        </article>
        <!-- Family Room -->
        <article class="room">
            <div class="room-image"><img src="images/familyRoom.jpg" alt="Family Room"></div>
            <div class="room-text">
                <h3>Family Room</h3>
                <p>2 beds good for 4 persons.</p>
                <p class="rate"><span>₱ 2,300.00 /</span> Per Night</p>
                <a class="btn" href="<?= $user_id ? 'reservation.php' : 'login.php'; ?>">BOOK NOW</a>
            </div>
        </article>
        <!-- Big Family Room -->
        <article class="room">
            <div class="room-image"><img src="images/familyRoomBig.jpg" alt="Big Family Room"></div>
            <div class="room-text">
                <h3>Big Family Room</h3>
                <p>3 Queen-sized beds good for 6 persons.</p>
                <p class="rate"><span>₱ 3,000.00 /</span> Per Night</p>
                <a class="btn" href="<?= $user_id ? 'reservation.php' : 'login.php'; ?>">BOOK NOW</a>
            </div>
        </article>
        
    </div>
</section>


















<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

<?php include 'footer.php'; ?>
</body>
</html>