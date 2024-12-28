<?php
// Include database connection
include 'components/connect.php';

// Handle form submission for adding a new room
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $number_of_rooms = $_POST['number_of_rooms'];

    // Validate and sanitize input data
    $room_type = $conn->real_escape_string($room_type);
    $price = (int)$price;
    $number_of_rooms = (int)$number_of_rooms;

    // Prepare INSERT SQL statement
    $sql = "INSERT INTO rooms (room_type, price, number_of_rooms, availability_status) VALUES (?, ?, ?, 'available')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $room_type, $price, $number_of_rooms);

    // Execute and check if successful
    if ($stmt->execute()) {
        echo "<script>alert('Room added successfully!'); window.location.href='add_room.php';</script>";
    } else {
        echo "<script>alert('Error adding room: " . $stmt->error . "');</script>";
    }

    // Close statement
    $stmt->close();
}

// Handle room deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare DELETE SQL statement
    $sql = "DELETE FROM rooms WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    // Execute and check if successful
    if ($stmt->execute()) {
        echo "<script>alert('Room deleted successfully!'); window.location.href='add_room.php';</script>";
    } else {
        echo "<script>alert('Error deleting room: " . $stmt->error . "');</script>";
    }

    // Close statement
    $stmt->close();
}

// Retrieve all rooms from the database
$sql = "SELECT * FROM rooms";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Rooms</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<!-- header section starts -->
<?php include 'admin_header.php'; ?>
<!-- header section ends -->

<!-- add room section starts -->
<section class="grid1">
    <h1 class="heading1">Manage Rooms</h1>
    
    <!-- Add New Room Form -->
    <div class="box-container1">
        <form method="post" action="add_room.php">
            <div class="form-group">
                <label for="room_type">Room Type:</label>
                <input type="text" id="room_type" name="room_type" class="input-box1" required>
            </div>

            <div class="form-group1">
                <label for="price">Price (₱):</label>
                <input type="text" id="price" name="price" class="input-box1" min="0" step="50" placeholder="Enter price" required>
            </div>

            <div class="form-group1">
                <label for="number_of_rooms">Number of Rooms:</label>
                <input type="text" id="number_of_rooms" name="number_of_rooms" class="input-box1" min="1" max="50" step="1" placeholder="Enter number of rooms" required>
            </div>

            <button type="submit" class="btn1">Add Room</button>
        </form>
    </div>

    <!-- Display Rooms -->
    <div class="box-container1">
        <h2>All Rooms</h2>
        <table border="1" class="room-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Price (₱)</th>
                    <th>Number of Rooms</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['room_type']}</td>
                                <td>{$row['price']}</td>
                                <td>{$row['number_of_rooms']}</td>
                                <td>{$row['availability_status']}</td>
                                <td>
                                    <a href='add_room.php?delete_id={$row['id']}' class='delete-btn'>Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No rooms available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link -->
<script src="js/admin_script.js"></script> 

<?php include 'components/message.php'; ?>

</body>
</html>
