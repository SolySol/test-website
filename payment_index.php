<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* Basic styling for the form */
        body { font-family: Arial, sans-serif; }
        .payment-form { max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .form-field { margin-bottom: 20px; }
        .form-field input { width: 100%; padding: 10px; font-size: 16px; }
        .form-field button { width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; font-size: 18px; cursor: pointer; }
        .form-field button:hover { background-color: #45a049; }
    </style>
</head>
<body>

    <div class="payment-form">
        <h2>Payment Form</h2>
        <form id="payment-form">
            <div class="form-field">
                <label for="card-element">Credit or Debit Card</label>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
            </div>
            <div class="form-field">
                <button type="submit">Pay Now</button>
            </div>
        </form>
    </div>

    <script>
        // Set your publishable key
        var stripe = Stripe('your_publishable_key_here'); // Find it in your Stripe Dashboard
        var elements = stripe.elements();

        // Create an instance of the card Element
        var card = elements.create('card');

        // Add an instance of the card Element to the DOM
        card.mount('#card-element');

        // Handle form submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createPaymentMethod('card', card).then(function(result) {
                if (result.error) {
                    // Show error in payment form
                    alert(result.error.message);
                } else {
                    // Send the payment method to your server
                    fetch('/payment.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            payment_method_id: result.paymentMethod.id,
                            amount: 5000 // Amount in cents (e.g., $50.00)
                        })
                    }).then(function(response) {
                        return response.json();
                    }).then(function(paymentResult) {
                        if (paymentResult.error) {
                            alert(paymentResult.error);
                        } else {
                            // The payment was successful
                            alert('Payment successful! Your client secret: ' + paymentResult.clientSecret);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
