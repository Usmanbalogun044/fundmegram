<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate to Campaign</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="bg-gray-100">

    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-lg">
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Donate to Campaign</h2>

            <!-- Error / Success Messages -->
            <div id="messages">
                @if(session('error'))
                    <div class="bg-red-500 text-white p-4 mb-4 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 mb-4 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <form action="{{ route('payment.processCard', ['id' => $campaign->id]) }}" method="POST" id="payment-form">
                @csrf

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

                <input type="hidden" name="payment_gateway" value="stripe">

                <!-- Card Details -->
                <div class="mb-4">
                    <label for="card-element" class="block text-gray-700 font-medium">Card Details</label>
                    <div id="card-element" class="p-3 border rounded-lg bg-gray-50"></div>
                    <div id="card-errors" class="text-red-500 mt-2"></div>
                </div>
                <div class="form-group form-check">
                    <input class="form-check-input" id="customControlInline" name="anonymous" type="checkbox" value="1">
                    <label class="form-check-label" for="customControlInline">{{ __('misc.anonymous_donation') }}</label>
                  </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transition-all duration-300">
                    Donate Now
                </button>
            </form>
        </div>
    </div>

    <script>
        var stripe = Stripe("{{ $stripeKey }}");
        var elements = stripe.elements();
        var card = elements.create("card");
        card.mount("#card-element");

        var form = document.getElementById("payment-form");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    document.getElementById("card-errors").textContent = result.error.message;
                } else {
                    var hiddenInput = document.createElement("input");
                    hiddenInput.setAttribute("type", "hidden");
                    hiddenInput.setAttribute("name", "stripeToken");
                    hiddenInput.setAttribute("value", result.token.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
