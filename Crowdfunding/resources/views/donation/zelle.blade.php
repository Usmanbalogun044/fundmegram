<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Transfer Donation</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-lg">
        <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">zelle Donation</h2>

        <!-- Error / Success Messages -->
        <div id="messages">
            @if(session('error'))
                <div class="bg-red-500 text-white p-4 mb-4 rounded-lg">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 mb-4 rounded-lg">{{ session('success') }}</div>
            @endif
        </div>

        <div class="bg-gray-100 p-4 rounded-lg border mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">zelle Details</h3>
            <p class="text-gray-700">{!! nl2br(e($bank)) !!}</p>
        </div>

        <form action="{{ route('payment.zelle', ['id' => $campaign->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Full Name -->
                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="full_name" class="block text-gray-700 font-medium">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium">Email Address</label>
                        <input type="email" id="email" name="email" required class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Country -->
                    <div class="mb-4">
                        <label for="country" class="block text-gray-700 font-medium">Country</label>
                        <input type="text" id="country" name="country" required class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Postal Code -->
                    <div class="mb-4">
                        <label for="postal_code" class="block text-gray-700 font-medium">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" required class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Comment -->
                    <div class="mb-4">
                        <label for="comment" class="block text-gray-700 font-medium">Comment (Optional)</label>
                        <textarea id="comment" name="comment" class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200"></textarea>
                    </div>

                    <!-- Donation Amount -->
                    <div class="mb-4">
                        <label for="amount" class="block text-gray-700 font-medium">Donation Amount ($)</label>
                        <input type="number" id="amount" name="amount" value="10" min="{{ $settings->min_donation_amount }}" max="{{ $settings->max_donation_amount }}" required class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
                    </div>

            <!-- Upload Receipt -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium">Upload Payment Receipt</label>
                <input type="file" name="bank_transfer" required class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-200">
            </div>
            <div class="form-group form-check">
                <input class="form-check-input" id="customControlInline" name="anonymous" type="checkbox" value="1">
                <label class="form-check-label" for="customControlInline">{{ __('misc.anonymous_donation') }}</label>
              </div>
            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition-all duration-300">
                Submit Donation
            </button>
             <a href="{{ url('/') }}" class="w-full block text-center bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-all duration-300">
                Back to Home
            </a>
        </form>
    </div>
</body>
</html>
