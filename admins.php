<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['admin_id'])){
   $admin_id = $_SESSION['admin_id'];
}else{
   header('location:login.php');
   exit;
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = mysqli_prepare($conn, "SELECT * FROM `users` WHERE id = ?"); // Change to users table
    mysqli_stmt_bind_param($verify_delete, 's', $delete_id);
    mysqli_stmt_execute($verify_delete);
    $result = mysqli_stmt_get_result($verify_delete);

    if (mysqli_num_rows($result) > 0) {
        $delete_user = mysqli_prepare($conn, "DELETE FROM `users` WHERE id = ?"); // Change to users table
        mysqli_stmt_bind_param($delete_user, 's', $delete_id);
        mysqli_stmt_execute($delete_user);
        $success_msg[] = 'User deleted!';
    } else {
        $warning_msg[] = 'User not found!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'admin_header.php'; ?>
<!-- header section ends -->

<!-- users section starts  -->

<section class="grid">

   <h1 class="heading">users</h1>

   <div class="box-container">

   <!-- <div class="box" style="text-align: center;">
      <p>create a new user</p>
      <a href="register.php" class="btn">register now</a>
   </div> -->

   <?php
      $select_users = mysqli_query($conn, "SELECT * FROM `users`"); // Change to users table
      if (mysqli_num_rows($select_users) > 0) {
         while ($fetch_users = mysqli_fetch_assoc($select_users)) {
   ?>
   <div class="box">
      <p>name: <span><?= $fetch_users['last_name']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_users['id']; ?>">
         <input type="submit" value="delete user" onclick="return confirm('Delete this user?');" name="delete" class="btn">
      </form>
   </div>
   <?php
         }
      } else {
         echo '<p style="text-align: center;">No users found!</p>'; // Display message if no users
      }
   ?>

   </div>

</section>

<!-- users section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>
