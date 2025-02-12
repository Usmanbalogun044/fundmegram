<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Passport and National ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-6">
        <h2 class="text-2xl font-bold text-center text-gray-800">Upload Your Documents</h2>
        <p class="text-sm text-gray-600 text-center mb-6">Upload both front and back images of your passport and national ID.</p>

        <form method="POST" action="{{ route('national_id') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Passport Front -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload your photo</label>
                <input type="file" name="passport_front" accept="image/*" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
            </div>
            <label for="" class="block text-sm font-medium text-gray-700 mb-1">choose Government ID</label>
                <select name="" id="">
                    <option value="">national ID</option>
                    <option value="">passport</option>
                    <option value="">Driver  License</option>
                </select>

            <!-- National ID Front -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Government ID (Front)</label>
                <input type="file" name="national_id_front" accept="image/*" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
            </div>

            <!-- National ID Back -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Government ID (Back)</label>
                <input type="file" name="national_id_back" accept="image/*" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
            </div>



            <button type="submit" class="w-full bg-blue-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Submit
            </button>
        </form>
    </div>
</body>
</html>
