<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .error { background: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="error">
        <h1>✗ Payment Failed</h1>
        <p>Your payment could not be processed. Please try again.</p>
        <a href="javascript:history.back()" class="btn">Try Again</a>
        <a href="{{ url('/') }}" class="btn">Back to Home</a>
    </div>
</body>
</html>