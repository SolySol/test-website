

<?php
// Start the session
session_start();











// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in to proceed with payment.");
}
$user_id = $_SESSION['user_id'];

// Include database connection
include 'components/connect.php';

// Fetch the reservation details based on `reservation_id` from the form submission
if (!isset($_POST['reservation_id'])) {
    die("Reservation ID not provided.");
}

$reservation_id = $_POST['reservation_id'];

// Validate the `reservation_id` and fetch reservation details
$query = $conn->prepare("SELECT total_cost FROM reservations WHERE id = ? AND user_id = ?");
$query->bind_param("ii", $reservation_id, $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    echo "Debug: Reservation ID = $reservation_id<br>";
echo "Debug: User ID = $user_id<br>";
    die("Invalid reservation ID or you do not have access to this reservation.");
}

$reservation = $result->fetch_assoc();
$total_cost = $reservation['total_cost'];

// PayPal credentials
$client_id = 'AaJRJ9CgmX3wlb-_fq44260SO1uKxtwFCHgml4FYmViSqclMYijEwyWIyzCugKgRvrAwbJsKjPHuP8Cq';
$secret = 'ECjnH6TpBgPxduALIh9KgVhac1-b4ZM-uo4G2Snrb9vDjpCavu3d1mhT-Ungb0GmzgkUafgRxURsO0nr';

// Step 1: Get PayPal access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
curl_setopt($ch, CURLOPT_USERPWD, $client_id . ":" . $secret);
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

// Step 2: Create the payment
$payment_data = [
    'intent' => 'sale',
    'payer' => [
        'payment_method' => 'paypal'
    ],
    'transactions' => [
        [
            'amount' => [
                'total' => number_format($total_cost, 2),
                'currency' => 'USD'
            ],
            'description' => "Payment for reservation ID #$reservation_id"
        ]
    ],
    'redirect_urls' => [
        'return_url' => "http://localhost/project/execute_payment.php?reservation_id=$reservation_id",
        'cancel_url' => "http://localhost/project/cancel_payment.php?reservation_id=$reservation_id"
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/payments/payment');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token
]);

$response = curl_exec($ch);
curl_close($ch);

// Parse payment response
$payment_response = json_decode($response);
if (isset($payment_response->links)) {
    foreach ($payment_response->links as $link) {
        if ($link->rel == 'approval_url') {
            header('Location: ' . $link->href); // Redirect to PayPal for approval
            exit;
        }
    }
} else {
    echo "Error creating PayPal payment.";
}




?>


