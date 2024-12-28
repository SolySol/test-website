<?php
include 'components/connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

// Fetch all booked dates from the database
$booked_dates = [];
$query = $conn->prepare("
    SELECT rl.check_in, rl.check_out, rm.room_type, COUNT(*) as total_booked, rm.number_of_rooms 
    FROM reservation_line rl
    JOIN rooms rm ON rl.room_id = rm.id
    GROUP BY rl.check_in, rl.check_out, rm.room_type
    HAVING total_booked >= rm.number_of_rooms
");
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    $booked_dates[] = [
        'title' => $row['room_type'] . ' - Fully Booked',
        'start' => $row['check_in'],
        'end' => (new DateTime($row['check_out']))->modify('+1 day')->format('Y-m-d'),
        'color' => '#FF0000'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booked Rooms</title>
    <!-- FullCalendar CSS (Non-Module Version) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <!-- FullCalendar JavaScript (Non-Module Version) -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="booked-rooms">
    <h1 class="heading">Booked Rooms Calendar</h1>
    <div id="calendar" style="max-width: 80%; margin: 0 auto; padding: 20px; height: 700px;"></div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    console.log("Initializing calendar...");
    console.log("Booked dates:", <?php echo json_encode($booked_dates); ?>);
    
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($booked_dates); ?>,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: false,
            selectable: false,
            eventColor: '#FF5733'
        });
        calendar.render();
        console.log("Calendar rendered successfully");
    } else {
        console.error("Calendar element not found!");
    }
});
</script>

<?php include 'components/footer.php'; ?>


</body>
</html>
