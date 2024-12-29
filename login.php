<?php 
session_start(); 
include 'components/connect.php';

if (isset($_POST['register-btn'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit();
    }

    // Validate phone number (Philippines format: +63XXXXXXXXXX or 09XXXXXXXXX)
    if (!preg_match('/^(09\d{9}|\+639\d{9})$/', $number)) {
        echo "<script>alert('Invalid phone number. Must be a valid Philippines mobile number.');</script>";
        exit();
    }

    // Normalize mobile number to +639XXXXXXXXX format
    if (preg_match('/^09/', $number)) {
        $number = preg_replace('/^09/', '+639', $number);
    }

    // Validate first and last names (only letters allowed)
    if (!preg_match('/^[a-zA-Z]+$/', $first_name) || !preg_match('/^[a-zA-Z]+$/', $last_name)) {
        echo "<script>alert('First and Last name must only contain letters.');</script>";
        exit();
    }

    // Validate password (minimum 6 characters and at least 1 number)
    if (!preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]{6,}$/', $password)) {
        echo "<script>alert('Password must be at least 6 characters and include at least one number.');</script>";
        exit();
    }

    $email = mysqli_real_escape_string($conn, $email);
    $first_name = mysqli_real_escape_string($conn, $first_name);
    $last_name = mysqli_real_escape_string($conn, $last_name);
    $number = mysqli_real_escape_string($conn, $number);
    $password = mysqli_real_escape_string($conn, $password);

    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');

    if (mysqli_num_rows($select_user) > 0) {
        echo "<script>alert('Email already exists.');</script>";
    } else {
        $query = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `number`, `password`) 
                 VALUES ('$first_name', '$last_name', '$email', '$number', '$password')";
        mysqli_query($conn, $query) or die('Query failed');
        echo "<script>alert('Registered successfully! Proceed to the login page.');</script>";
    }
}

if (isset($_POST['login-btn'])) {
    $email = filter_var($_POST['1email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['1password']; // Direct password without hashing

    $sql = "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['first_name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:dashboard.php');
            exit();
        } else if ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['first_name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
            exit();
        }
    } else {
        echo "<script>alert('Invalid credentials.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" type="image/jpg" href="images/logo.jpg">
    <title>Login | Registration</title>
</head>
<body>
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo">
                <p>M ASHLEY SUITES .</p>
            </div>
            <div class="nav-menu" id="navMenu"></div>
            <div class="nav-button">
                <button class="btn white-btn" id="loginBtn" onclick="login()">Sign In</button>
                <button class="btn" id="registerBtn" onclick="register()">Sign Up</button>
                <button class="btn" id="homeBtn" onclick="window.location.href='index.php'">Back to Home</button>
            </div>
            
            <div class="nav-menu-btn">
                <i class="bx bx-menu" onclick="myMenuFunction()"></i>
            </div>
        </nav>

        <div class="form-box">
            <div class="login-container" id="login">
                <div class="top">
                    <header>Login</header>
                </div>
                <form method="post">
                    <div class="input-box">
                        <input type="email" class="input-field" name="1email" placeholder="Email" required 
                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address.">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" name="1password" placeholder="Password" required>
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" name="login-btn" value="Sign In">
                    </div>
                </form>
            </div>

            <div class="register-container" id="register">
                <div class="top">
                    <header>Sign Up</header>
                </div>
                <form method="post">
                    <div class="two-forms">
                        <div class="input-box">
                            <input type="text" class="input-field" name="first_name" placeholder="Firstname" required 
                                   pattern="[a-zA-Z]+" title="Firstname must only contain letters.">
                            <i class="bx bx-user"></i>
                        </div>
                        <div class="input-box">
                            <input type="text" class="input-field" name="last_name" placeholder="Lastname" required 
                                   pattern="[a-zA-Z]+" title="Lastname must only contain letters.">
                            <i class="bx bx-user"></i>
                        </div>
                    </div>
                    <div class="input-box">
                        <input type="email" class="input-field" name="email" placeholder="Email" required 
                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address.">
                        <i class="bx bx-envelope"></i>
                    </div>
                    <div class="input-box">
                        <input type="text" class="input-field" name="number" placeholder="Mobile Number" required 
                               pattern="^(09\d{9}|\+639\d{9})$" title="Enter a valid Philippines mobile number.">
                        <i class="bx bx-phone"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" name="password" placeholder="Password" required 
                               pattern="^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]{6,}$" 
                               title="Password must be at least 6 characters long and contain at least one number.">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-box">
                        <input type="submit" class="submit" name="register-btn" value="Register">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
       function myMenuFunction() {
           var i = document.getElementById("navMenu");
           if(i.className === "nav-menu") {
               i.className += " responsive";
           } else {
               i.className = "nav-menu";
           }
       }

       function login() {
           var x = document.getElementById("login");
           var y = document.getElementById("register");
           var a = document.getElementById("loginBtn");
           var b = document.getElementById("registerBtn");
           x.style.left = "4px";
           y.style.right = "-520px";
           a.className += " white-btn";
           b.className = "btn";
           x.style.opacity = 1;
           y.style.opacity = 0;
       }

       function register() {
           var x = document.getElementById("login");
           var y = document.getElementById("register");
           var a = document.getElementById("loginBtn");
           var b = document.getElementById("registerBtn");
           x.style.left = "-510px";
           y.style.right = "5px";
           a.className = "btn";
           b.className += " white-btn";
           x.style.opacity = 0;
           y.style.opacity = 1;
       }
    </script>
</body>
</html>
