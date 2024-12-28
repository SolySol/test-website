<?php
include 'components/connect.php'; // Database connection file

// Set the timezone to Philippine time (UTC+8)
date_default_timezone_set('Asia/Manila');

if (isset($_GET['paymentId']) && isset($_GET['PayerID']) && isset($_GET['reservation_id'])) {
    // Get the payment ID, payer ID, and reservation ID from the request
    $payment_id = $_GET['paymentId'];
    $payer_id = $_GET['PayerID'];
    $reservation_id = $_GET['reservation_id']; // The reservation ID passed via the return URL

    // Validate that the reservation ID exists in the reservations table
    $check_reservation = $conn->prepare("SELECT id, total_cost FROM reservations WHERE id = ?");
    $check_reservation->bind_param("i", $reservation_id);
    $check_reservation->execute();
    $result = $check_reservation->get_result();

    if ($result->num_rows == 0) {
        die("Error: Reservation ID does not exist."); // Stop execution if reservation_id is invalid
    }

    // Fetch reservation data
    $reservation = $result->fetch_assoc();
    $total_cost = $reservation['total_cost'];

    // Set your PayPal credentials
    $client_id = 'AaJRJ9CgmX3wlb-_fq44260SO1uKxtwFCHgml4FYmViSqclMYijEwyWIyzCugKgRvrAwbJsKjPHuP8Cq';
    $client_secret = 'ECjnH6TpBgPxduALIh9KgVhac1-b4ZM-uo4G2Snrb9vDjpCavu3d1mhT-Ungb0GmzgkUafgRxURsO0nr';

    // Get PayPal access token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $client_secret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // Parse access token response
    $token_data = json_decode($response);
    if (!isset($token_data->access_token)) {
        die("Failed to retrieve PayPal access token.");
    }
    $access_token = $token_data->access_token;

    // Execute payment request data
    $payment_data = [
        'payer_id' => $payer_id
    ];

    // Execute payment API call
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/payments/payment/' . $payment_id . '/execute');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response
    $execute_response = json_decode($response);

    // Check if the payment was successful
    if (isset($execute_response->state) && $execute_response->state == 'approved') {
        // Extract payment details
        $payer_email = $execute_response->payer->payer_info->email;
        $amount_paid = $execute_response->transactions[0]->amount->total;
        $payment_date = date('Y-m-d'); // Get the current date
        $payment_time = date('H:i:s'); // Get the current time

        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert the payment details into the 'payment' table
            $insert_payment = $conn->prepare("INSERT INTO payment (reservation_id, amount, payment_date, payment_time) VALUES (?, ?, ?, ?)");
            $insert_payment->bind_param("idss", $reservation_id, $amount_paid, $payment_date, $payment_time);

            if ($insert_payment->execute()) {
                // Update the reservation status to 'Paid'
                $update_reservation = $conn->prepare("UPDATE reservations SET status = 'Paid' WHERE id = ?");
                $update_reservation->bind_param("i", $reservation_id);
                $update_reservation->execute();

                // Get the room_id from reservation_line table
                $get_room_id = $conn->prepare("SELECT room_id FROM reservation_line WHERE reservation_id = ?");
                $get_room_id->bind_param("i", $reservation_id);
                $get_room_id->execute();
                $room_result = $get_room_id->get_result();
                
                if ($room_data = $room_result->fetch_assoc()) {
                    $room_id = $room_data['room_id'];
                    
                    // Update the number_of_rooms in the rooms table
                    $update_rooms = $conn->prepare("UPDATE rooms SET number_of_rooms = number_of_rooms - 1 WHERE id = ?");
                    $update_rooms->bind_param("i", $room_id);
                    $update_rooms->execute();
                }

                // Commit transaction
                $conn->commit();
                $payment_status = "Payment successful! Your reservation has been paid.";
            } else {
                throw new Exception("Failed to record payment");
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $payment_status = "Failed to record payment in the database: " . $e->getMessage();
        }
    } else {
        $payment_status = "Payment failed. Please try again.";
    }
} else {
    $payment_status = "Payment error: Missing Payment ID, Payer ID, or Reservation ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #0070ba;
        }

        .status-message {
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 30px;
            color: #333;
        }

        .status-message.success {
            color: #28a745;
        }

        .status-message.error {
            color: #dc3545;
        }

        .btn {
            background-color: #0070ba;
            color: white;
            border: none;
            padding: 12px 25px;
            text-align: center;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background-color: #005fa3;
        }

        .go-back-btn {
            background-color: #f0f0f0;
            color: #333;
            margin-top: 15px;
        }

        .go-back-btn:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h1>Payment Status</h1>

    <div class="status-message <?php echo isset($payment_status) ? (strpos($payment_status, 'successful') !== false ? 'success' : 'error') : ''; ?>">
        <?php echo isset($payment_status) ? $payment_status : 'Processing...'; ?>
    </div>

    <a href="bookings.php" class="btn go-back-btn">Go Back</a>
</div>

</body>
</html>