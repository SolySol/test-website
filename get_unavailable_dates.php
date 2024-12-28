<?php
include 'components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

    $unavailable_dates = [];
    if ($room_id > 0) {
        $stmt = $conn->prepare("
            SELECT number_of_rooms 
            FROM rooms 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $room_result = $stmt->get_result();

        if ($room_result->num_rows > 0) {
            $room_data = $room_result->fetch_assoc();
            if ($room_data['number_of_rooms'] <= 0) {
                $stmt = $conn->prepare("
                    SELECT check_in, check_out 
                    FROM reservation_line 
                    WHERE room_id = ?
                ");
                $stmt->bind_param("i", $room_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $start_date = new DateTime($row['check_in']);
                    $end_date = new DateTime($row['check_out']);
                    $end_date->modify('+1 day'); // Include the check-out date

                    $interval = new DateInterval('P1D');
                    $daterange = new DatePeriod($start_date, $interval, $end_date);

                    foreach ($daterange as $date) {
                        $unavailable_dates[] = $date->format('Y-m-d');
                    }
                }
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($unavailable_dates);
}
?>