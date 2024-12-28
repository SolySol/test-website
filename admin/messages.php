<?php

$conn = mysqli_connect('localhost', 'root', '', 'hotel_booking_system') or die('connection failed');

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    // Verify message exists
    $verify_delete = mysqli_prepare($conn, "SELECT * FROM `messages` WHERE id = ?");
    mysqli_stmt_bind_param($verify_delete, 's', $delete_id);
    mysqli_stmt_execute($verify_delete);
    mysqli_stmt_store_result($verify_delete);

    if (mysqli_stmt_num_rows($verify_delete) > 0) {
        // Delete message
        $delete_bookings = mysqli_prepare($conn, "DELETE FROM `messages` WHERE id = ?");
        mysqli_stmt_bind_param($delete_bookings, 's', $delete_id);
        mysqli_stmt_execute($delete_bookings);
        $success_msg[] = 'Message deleted!';
    } else {
        $warning_msg[] = 'Message deleted already!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- messages section starts  -->

<section class="grid">

   <h1 class="heading">messages</h1>

   <div class="box-container">

   <?php
      $select_messages = mysqli_query($conn, "SELECT * FROM `messages`");
      if (mysqli_num_rows($select_messages) > 0) {
         while ($fetch_messages = mysqli_fetch_assoc($select_messages)) {
   ?>
   <div class="box">
      <p>name : <span><?= $fetch_messages['name']; ?></span></p>
      <p>email : <span><?= $fetch_messages['email']; ?></span></p>
      <p>number : <span><?= $fetch_messages['number']; ?></span></p>
      <p>message : <span><?= $fetch_messages['message']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_messages['id']; ?>">
         <input type="submit" value="delete message" onclick="return confirm('delete this message?');" name="delete" class="btn">
      </form>
   </div>
   <?php
      }
   } else {
   ?>
   <div class="box" style="text-align: center;">
      <p>no messages found!</p>
      <a href="dashboard.php" class="btn">go to home</a>
   </div>
   <?php
      }
   ?>

   </div>

</section>
