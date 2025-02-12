<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Payment Option</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <h2 class="text-lg font-semibold mb-4">Choose Payment Option</h2>

    <div class="grid grid-cols-2 gap-4">
        <!-- Pay with Card -->
        <a href="{{ route('payment.card', ['id' => $campaign->id]) }}"
            class="flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow-lg hover:bg-blue-700 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M10 2c-1.6 0-3 1.2-3.3 2.8L4 17.6c-.2 1.3.8 2.4 2.1 2.4h2.2l.6-4h3.1c2.5 0 4.7-1.8 5.1-4.3.3-1.7-.3-3.4-1.5-4.6S11.7 2 10 2z"></path>
            </svg>
            Pay with Card
        </a>

        <!-- PayPal -->
        <a href="{{ route('payment.paypal', ['id' => $campaign->id]) }}"
            class="flex items-center justify-center gap-2 px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg shadow-lg hover:bg-yellow-600 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M10 2c-1.6 0-3 1.2-3.3 2.8L4 17.6c-.2 1.3.8 2.4 2.1 2.4h2.2l.6-4h3.1c2.5 0 4.7-1.8 5.1-4.3.3-1.7-.3-3.4-1.5-4.6S11.7 2 10 2z"></path>
            </svg>
            Pay with PayPal
        </a>

        <!-- CashApp -->
        <a href="{{ route('payment.cashapp', ['id' => $campaign->id]) }}"
            class="flex items-center justify-center gap-2 px-6 py-3 bg-green-600 text-white font-medium rounded-lg shadow-lg hover:bg-green-700 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 12a9 9 0 1018 0A9 9 0 003 12zm11.3-3.7L9.6 13h3.1c.7 0 1.3.6 1.3 1.3s-.6 1.3-1.3 1.3H8.3c-.7 0-1.3-.6-1.3-1.3s.6-1.3 1.3-1.3h3.1l-4.7 4.7 1.4 1.4 7-7-1.4-1.4z"></path>
            </svg>
            Pay with CashApp
        </a>

        <!-- Bank Transfer -->
        <a href="{{ route('payment.banktransfer', ['id' => $campaign->id]) }}"
            class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 text-white font-medium rounded-lg shadow-lg hover:bg-gray-800 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10V6h14v4M3 16h18m-5 0v4H8v-4"></path>
            </svg>
            Bank Transfer
        </a>
          <a href="{{ route('payment.wiretransfer', ['id' => $campaign->id]) }}"
            class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 text-white font-medium rounded-lg shadow-lg hover:bg-gray-800 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10V6h14v4M3 16h18m-5 0v4H8v-4"></path>
            </svg>
            wire transfer
        </a>

        <!-- Crypto -->
        <a href="{{ route('payment.crypto', ['id' => $campaign->id]) }}"
            class="flex items-center justify-center gap-2 px-6 py-3 bg-yellow-500 text-white font-medium rounded-lg shadow-lg hover:bg-yellow-600 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-1h2v1zm1-4h-4V8h4v5z"></path>
            </svg>
            Pay with Crypto
        </a>
<!-- Venmo -->
<a href="{{ route('payment.venmo', ['id' => $campaign->id]) }}"
    class="flex items-center justify-center gap-2 px-6 py-3 bg-blue-500 text-white font-medium rounded-lg shadow-lg hover:bg-blue-600 transition">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-1h2v1zm1-4h-4V8h4v5z"></path>
    </svg>
    Pay with Venmo
</a>

<!-- Zelle -->
<a href="{{ route('payment.zelle', ['id' => $campaign->id]) }}"
    class="flex items-center justify-center gap-2 px-6 py-3 bg-purple-600 text-white font-medium rounded-lg shadow-lg hover:bg-purple-700 transition">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-1h2v1zm1-4h-4V8h4v5z"></path>
    </svg>
    Pay with Zelle
</a>



    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "⚠️ Warning!",
                text: "{{$payment->description}}",
                icon: "warning",
                confirmButtonText: "OK, I Understand",
                confirmButtonColor: "#d33",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    </script>
</body>
</html>
