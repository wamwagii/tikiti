{{-- resources/views/payment/success.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful - Tikiti Kenya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md text-center">
            <div class="text-green-500 text-6xl mb-4">✓</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Payment Successful!</h1>
            <p class="text-gray-600 mb-4">Your order #{{ $order->order_number }} has been confirmed.</p>
            <p class="text-gray-600 mb-6">Total paid: <span class="font-bold text-indigo-600">KES {{ number_format($order->total_amount, 2) }}</span></p>
            <p class="text-sm text-gray-500 mb-6">A confirmation email has been sent to your email address.</p>
            <a href="{{ route('home') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>