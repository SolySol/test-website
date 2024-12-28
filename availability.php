<?php
// Include database connection
include 'components/connect.php';
if (isset($_POST['check_availability'])) {
    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Sanitize inputs
    $room_type = filter_var($room_type, FILTER_SANITIZE_STRING);
    $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
    $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);

    // Get total rooms available for this type from the rooms table
    $query = $conn->prepare("SELECT total_rooms FROM rooms WHERE room_type = ?");
    $query->bind_param("s", $room_type);
    $query->execute();
    $result = $query->get_result();
    $room_data = $result->fetch_assoc();

    if (!$room_data) {
        echo json_encode(["status" => "error", "message" => "Room type not found."]);
        exit;
    }

    $total_rooms = $room_data['total_rooms'];

    // Count the number of rooms already booked for this type between the selected dates
    $query = $conn->prepare("
        SELECT COUNT(*) AS booked_rooms 
        FROM bookings 
        JOIN booking_details ON bookings.reservation_id = booking_details.reservation_id 
        WHERE booking_details.room_type = ? 
          AND (check_in < ? AND check_out > ?)
    ");
    $query->bind_param("sss", $room_type, $check_out, $check_in);
    $query->execute();
    $result = $query->get_result();
    $booking_data = $result->fetch_assoc();

    $booked_rooms = $booking_data['booked_rooms'];

    // Check if rooms are available
    $available_rooms = $total_rooms - $booked_rooms;

    if ($available_rooms > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "$available_rooms room(s) available.",
            "available_rooms" => $available_rooms
        ]);
    } else {
        echo json_encode([
            "status" => "unavailable",
            "message" => "No rooms available for the selected dates."
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookings</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>



<!-- home section starts  -->
<?php include 'components/user_header.php'; ?>

<!-- availability section starts  -->

<section class="availability" id="availability">
   
   <form action="" method="post">
   <h3>Availabiltity</h3>
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
         <p>Room Type <span>*</span></p>
         <select name="room_type" class="input" required>
            <option value="Suite Room" selected>Suite Room</option>
            <option value="Standard Room">Standard Room</option>
            <option value="Family Room">Family Room</option>
            <option value="Family Room Big">Family Room Big</option>
         </select>
      </div>
      <input type="submit" value="check availability" name="check" class="btn">
   </form>

</section>

<!-- availability section ends -->

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>

</body>
</html>