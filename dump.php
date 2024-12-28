
<!-- reviews section starts  -->

<section class="reviews" id="reviews">

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">
         <div class="swiper-slide box">
            <img src="images/pic-1.png" alt="">
            <h3>john deo</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-2.png" alt="">
            <h3>john deo</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-3.png" alt="">
            <h3>john deo</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-4.png" alt="">
            <h3>john deo</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-5.png" alt="">
            <h3>john deo</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-6.png" alt="">
            <h3>john deo</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates blanditiis optio dignissimos eaque aliquid explicabo.</p>
         </div>
      </div>

      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- reviews section ends  -->



<!-- contact section starts  -->

<section class="contact" id="contact">

   <div class="row">

      <form action="" method="post">
         <h3>send us message</h3>
         <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="box">
         <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
         <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="enter your number" class="box">
         <textarea name="message" class="box" required maxlength="1000" placeholder="enter your message" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" name="send" class="btn">
      </form>

      <div class="faq">
         <h3 class="title">frequently asked questions</h3>
         <div class="box active">
            <h3>how to cancel?</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus sunt aspernatur excepturi eos! Quibusdam, sapiente.</p>
         </div>
         <div class="box">
            <h3>is there any vacancy?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>what are payment methods?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>how to claim coupons codes?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
         <div class="box">
            <h3>what are the age requirements?</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsa ipsam neque quaerat mollitia ratione? Soluta!</p>
         </div>
      </div>

   </div>

</section>

<!-- contact section ends -->


   <!-- availability section ends -->

   <!-- about section starts  -->
   <!-- 
   <section class="about" id="about">

      <div class="row">
         <div class="image">
            <img src="images/about-img-1.jpg" alt="">
         </div>
         <div class="content">
            <h3>best staff</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi laborum maxime eius aliquid temporibus unde?</p>
            <a href="#reservation" class="btn">make a reservation</a>
         </div>
      </div>

      <div class="row revers">
         <div class="image">
            <img src="images/about-img-2.jpg" alt="">
         </div>
         <div class="content">
            <h3>best foods</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi laborum maxime eius aliquid temporibus unde?</p>
            <a href="#contact" class="btn">contact us</a>
         </div>
      </div>

      <div class="row">
         <div class="image">
            <img src="images/about-img-3.jpg" alt="">
         </div>
         <div class="content">
            <h3>swimming pool</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Animi laborum maxime eius aliquid temporibus unde?</p>
            <a href="#availability" class="btn">check availability</a>
         </div>
      </div>

   </section> -->

   <!-- about section ends -->



   
// include 'components/connect.php';

// session_start();

// if(isset($_SESSION['user_id'])){
//    $user_id = $_SESSION['user_id'];
// }else{
//    header('location:login.php');
//    exit;
// }

// if(isset($_POST['check'])){

//    $check_in = $_POST['check_in'];
//    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

//    $total_rooms = 0;

//    $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
//    $check_bookings->execute([$check_in]);

//    while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
//       $total_rooms += $fetch_bookings['rooms'];
//    }

//    // if the hotel has total 30 rooms 
//    if($total_rooms >= 30){
//       $warning_msg[] = 'rooms are not available';
//    }else{
//       $success_msg[] = 'rooms are available';
//    }

// }

// if(isset($_POST['book'])){

   
//    $name = $_POST['name'];
//    $name = filter_var($name, FILTER_SANITIZE_STRING);
//    $email = $_POST['email'];
//    $email = filter_var($email, FILTER_SANITIZE_STRING);
//    $number = $_POST['number'];
//    $number = filter_var($number, FILTER_SANITIZE_STRING);
//    $rooms = $_POST['rooms'];
//    $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
//    $check_in = $_POST['check_in'];
//    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
//    $check_out = $_POST['check_out'];
//    $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
//    $adults = $_POST['adults'];
//    $adults = filter_var($adults, FILTER_SANITIZE_STRING);
//    $childs = $_POST['childs'];
//    $childs = filter_var($childs, FILTER_SANITIZE_STRING);

//    $total_rooms = 0;

//    $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
//    $check_bookings->execute([$check_in]);

//    while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
//       $total_rooms += $fetch_bookings['rooms'];
//    }

//    if($total_rooms >= 30){
//       $warning_msg[] = 'rooms are not available';
//    }else{

//       $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
//       $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

//       if($verify_bookings->rowCount() > 0){
//          $warning_msg[] = 'room booked alredy!';
//       }else{
//          $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
//          $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
//          $success_msg[] = 'room booked successfully!';
//       }

//    }

// }

// if(isset($_POST['send'])){

   
//    $name = $_POST['name'];
//    $name = filter_var($name, FILTER_SANITIZE_STRING);
//    $email = $_POST['email'];
//    $email = filter_var($email, FILTER_SANITIZE_STRING);
//    $number = $_POST['number'];
//    $number = filter_var($number, FILTER_SANITIZE_STRING);
//    $message = $_POST['message'];
//    $message = filter_var($message, FILTER_SANITIZE_STRING);

//    $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
//    $verify_message->execute([$name, $email, $number, $message]);

//    if($verify_message->rowCount() > 0){
//       $warning_msg[] = 'message sent already!';
//    }else{
//       $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
//       $insert_message->execute([$id, $name, $email, $number, $message]);
//       $success_msg[] = 'message send successfully!';
//    }

// }

<?php include 'components/user_header.php'; ?>



<!-- --------------old index ------------------------>
<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   header('location:login.php');
   exit;
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

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
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

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->bind_param("issssssii", $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs);
      $verify_bookings->execute();
      $result = $verify_bookings->get_result();

      if($result->num_rows > 0){
         $warning_msg[] = 'room booked already!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `bookings`(user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?)");
         $book_room->bind_param("issssssii", $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs);
         $book_room->execute();
         $success_msg[] = 'room booked successfully!';
      }

   }

}

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->bind_param("ssss", $name, $email, $number, $message);
   $verify_message->execute();
   $result = $verify_message->get_result();

   if($result->num_rows > 0){
      $warning_msg[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->bind_param("ssss", $name, $email, $number, $message);
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
   <title>home</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- header -->
<header class = "header1" id = "header">
            <div class = "head-top">
                <div class = "site-name">
                
                    <!-- <span>M Ashley Suites</span> -->
                </div>
                <!-- <div class = "site-nav">
                    <span id = "nav-btn">MENU <i class = "fas fa-bars"></i></span>
                </div> -->
            </div>

            <div class = "head-bottom flex">
                <h2>"Where Every Stay Feels Like Home"</h2>
                <!-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto est quos veniam impedit numquam itaque voluptatum, dicta asperiores accusamus, eligendi neque ut incidunt, modi harum molestiae atque natus officia minima.</p> -->
                <button type = "button" class = "head-btn">GET STARTED</button>
            </div>
        </header>
        <!-- end of header -->

<!-- home section starts  -->
<?php include 'components/user_header.php'; ?>
<section class="home" id="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         <div class="box swiper-slide">
            <img src="images/FamilyRoom.jpg" alt="">
            <div class="flex">
               <h3>FamilyRoom</h3>
               <!-- <a href="#availability" class="btn">check availability</a> -->
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/familyRoomBig.jpg" alt="">
            <div class="flex">
               <h3>familyRoomBig</h3>
               <!-- <a href="#reservation" class="btn">make a reservation</a> -->
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/standard1.jpg" alt="">
            <div class="flex">
               <h3>standard</h3>
               <!-- <a href="#contact" class="btn">contact us</a> -->
            </div>
         </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>

<!-- home section ends -->

<!-- availability section starts  -->

<section class="availability" id="availability">

   <form action="" method="post">
      <div class="flex">
         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1">1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="-">0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
         <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1">1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
      </div>
      <input type="submit" value="check availability" name="check" class="btn">
   </form>

</section>

<!-- availability section ends -->

<!-- reservation section starts  -->

<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>make a reservation</h3>
      <div class="flex">
         <!-- <div class="box">
            <p>your name <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="input">
         </div> -->
         <!-- <div class="box">
            <p>your email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="input">
         </div> -->
         <!-- <div class="box">
            <p>your number <span>*</span></p>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="enter your number" class="input">
         </div> -->
        
         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
         
         <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
      </div>
      <input type="submit" value="book now" name="book" class="btn">
   </form>

</section>

<!-- reservation section ends -->






<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>



<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   header('location:login.php');
   exit;
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

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
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

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->bind_param("issssssii", $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs);
      $verify_bookings->execute();
      $result = $verify_bookings->get_result();

      if($result->num_rows > 0){
         $warning_msg[] = 'room booked already!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `bookings`(user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?)");
         $book_room->bind_param("issssssii", $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs);
         $book_room->execute();
         $success_msg[] = 'room booked successfully!';
      }

   }

}

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->bind_param("ssss", $name, $email, $number, $message);
   $verify_message->execute();
   $result = $verify_message->get_result();

   if($result->num_rows > 0){
      $warning_msg[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(name, email, number, message) VALUES(?,?,?,?)");
      $insert_message->bind_param("ssss", $name, $email, $number, $message);
      $insert_message->execute();
      $success_msg[] = 'message sent successfully!';
   }

}



?>




<!-- reservation section starts  -->

<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>make a reservation</h3>
      <div class="flex">
         <!-- <div class="box">
            <p>your name <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="input">
         </div> -->
         <!-- <div class="box">
            <p>your email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="input">
         </div> -->
         <!-- <div class="box">
            <p>your number <span>*</span></p>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="enter your number" class="input">
         </div> -->
        
         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
         
         <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
      </div>
      <input type="submit" value="book now" name="book" class="btn">
   </form>

</section>

<!-- reservation section ends -->


<?php
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
?>



 <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
      </div>
      <input type="submit" value="book now" name="book" class="btn">
   </form> 



   <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1">1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="-">0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>


         
// Send Message
if(isset($_POST['send'])) {

   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM messages WHERE user_id = ? AND message = ?");
   $verify_message->bind_param("is", $user_id, $message);
   $verify_message->execute();
   $result = $verify_message->get_result();

   if($result->num_rows > 0) {
      $warning_msg[] = 'Message sent already!';
   } else {
      $insert_message = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
      $insert_message->bind_param("is", $user_id, $message);
      $insert_message->execute();
      $success_msg[] = 'Message sent successfully!';
   }
}


  <!-- <p>Check-in <span>*</span></p>
    <input type="date" name="check_in" class="input" required min="<?= date('Y-m-d'); ?>">
</div>
<div class="box">
    <p>Check-out <span>*</span></p>
    <input type="date" name="check_out" class="input" required min="<?= date('Y-m-d'); ?>">
</div> -->