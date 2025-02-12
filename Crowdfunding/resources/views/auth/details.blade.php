<!-- resources/views/address_form.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto mt-10 p-8 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Address Form</h2>

        <!-- Display validation errors if any -->
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 mb-4 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('address') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Home Address -->
                <div>
                    <label for="homeaddress" class="block text-gray-600">Home Address</label>
                    <input type="text" name="homeaddress" id="homeaddress" 
                           class="w-full p-3 border border-gray-300 rounded-md" 
                           value="{{ old('homeaddress') }}" required>
                </div>

                <!-- Street Address -->
                <div>
                    <label for="streetaddress" class="block text-gray-600">Street Address</label>
                    <input type="text" name="streetaddress" id="streetaddress" 
                           class="w-full p-3 border border-gray-300 rounded-md" 
                           value="{{ old('streetaddress') }}" required>
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-gray-600">City</label>
                    <input type="text" name="city" id="city" 
                           class="w-full p-3 border border-gray-300 rounded-md" 
                           value="{{ old('city') }}" required>
                </div>

                <!-- State -->
                <div>
                    <label for="state" class="block text-gray-600">State</label>
                    <input type="text" name="state" id="state" 
                           class="w-full p-3 border border-gray-300 rounded-md" 
                           value="{{ old('state') }}" required>
                </div>

                <!-- Zipcode -->
                <div>
                    <label for="zipcode" class="block text-gray-600">Zipcode</label>
                    <input type="text" name="zipcode" id="zipcode" 
                           class="w-full p-3 border border-gray-300 rounded-md" 
                           value="{{ old('zipcode') }}" required>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition duration-300">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>

</body>
</html>
