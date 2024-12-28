<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}

if(isset($_POST['delete'])){

   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = mysqli_prepare($conn, "SELECT * FROM `admins` WHERE id = ?");
   mysqli_stmt_bind_param($verify_delete, 's', $delete_id);
   mysqli_stmt_execute($verify_delete);
   $result = mysqli_stmt_get_result($verify_delete);

   if(mysqli_num_rows($result) > 0){
      $delete_admin = mysqli_prepare($conn, "DELETE FROM `admins` WHERE id = ?");
      mysqli_stmt_bind_param($delete_admin, 's', $delete_id);
      mysqli_stmt_execute($delete_admin);
      $success_msg[] = 'Admin deleted!';
   }else{
      $warning_msg[] = 'Admin deleted already!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admins</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- admins section starts  -->

<section class="grid">

   <h1 class="heading">admins</h1>

   <div class="box-container">

   <div class="box" style="text-align: center;">
      <p>create a new admin</p>
      <a href="register.php" class="btn">register now</a>
   </div>

   <?php
      $select_admins = mysqli_query($conn, "SELECT * FROM `admins`");
      if(mysqli_num_rows($select_admins) > 0){
         while($fetch_admins = mysqli_fetch_assoc($select_admins)){
   ?>
   <div class="box" <?php if( $fetch_admins['name'] == 'admin'){ echo 'style="display:none;"'; } ?>>
      <p>name : <span><?= $fetch_admins['name']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_admins['id']; ?>">
         <input type="submit" value="delete admin" onclick="return confirm('delete this admin?');" name="delete" class="btn">
      </form>
   </div>
   <?php
         }
      }
   ?>

   </div>

</section>

<!-- admins section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>
