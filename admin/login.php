<?php

$conn = mysqli_connect('localhost', 'root', '', 'hotel_booking_system') or die('connection failed');

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING); 
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Prepare and execute the SQL statement
    $select_admins = mysqli_prepare($conn, "SELECT * FROM `admins` WHERE name = ? AND password = ? LIMIT 1");
    mysqli_stmt_bind_param($select_admins, 'ss', $name, $pass);
    mysqli_stmt_execute($select_admins);
    $result = mysqli_stmt_get_result($select_admins);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        setcookie('admin_id', $row['id'], time() + 60*60*24*30, '/');
        header('location:dashboard.php');
    } else {
        $warning_msg[] = 'Incorrect username or password!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<!-- login section starts  -->

<section class="form-container" style="min-height: 100vh;">

   <form action="" method="POST">
      <h3>welcome back!</h3>
      <p>default name = <span>admin</span> & password = <span>111</span></p>
      <input type="text" name="name" placeholder="enter username" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" placeholder="enter password" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" name="submit" class="btn">
   </form>

</section>

<!-- login section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>
