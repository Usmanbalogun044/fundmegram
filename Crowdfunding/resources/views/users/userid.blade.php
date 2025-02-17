<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Document Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('https://source.unsplash.com/random/1600x900') no-repeat center center/cover;
        }
    </style>
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(targetId).src = e.target.result;
                document.getElementById(targetId).classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
</head>
<body class="bg-gray-900 bg-opacity-75 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white shadow-2xl rounded-2xl p-8 border-4 border-blue-500">
        <h2 class="text-4xl font-extrabold text-center text-indigo-600 mb-4">Document Upload Center</h2>
        <p class="text-base text-center text-gray-700 mb-6">Securely upload your passport and ID documents.</p>

        <form method="POST" action="{{ route('national_id') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="flex flex-col gap-4">
                <label class="text-lg font-semibold text-gray-800">Passport Front:</label>
                <input type="file" name="passport_front" onchange="previewImage(event, 'passport_front_preview')" class="p-2 border border-gray-400 rounded-lg">
                <img id="passport_front_preview" class="hidden rounded-lg border shadow-lg w-full max-h-64 object-cover">
            </div>

            <div class="flex flex-col gap-4">
                <label class="text-lg font-semibold text-gray-800">Select ID Type:</label>
                <select name="id_type" class="p-2 border border-gray-400 rounded-lg">
                    <option value="national_id">National ID</option>
                    <option value="passport">Passport</option>
                    <option value="driver_license">Driver's License</option>
                </select>
            </div>

            <div class="flex flex-col gap-4">
                <label class="text-lg font-semibold text-gray-800">National ID Front:</label>
                <input type="file" name="national_id_front" onchange="previewImage(event, 'national_id_front_preview')" class="p-2 border border-gray-400 rounded-lg">
                <img id="national_id_front_preview" class="hidden rounded-lg border shadow-lg w-full max-h-64 object-cover">
            </div>

            <div class="flex flex-col gap-4">
                <label class="text-lg font-semibold text-gray-800">National ID Back:</label>
                <input type="file" name="national_id_back" onchange="previewImage(event, 'national_id_back_preview')" class="p-2 border border-gray-400 rounded-lg">
                <img id="national_id_back_preview" class="hidden rounded-lg border shadow-lg w-full max-h-64 object-cover">
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-indigo-500 hover:to-blue-600 transition-all">
                Submit Documents
            </button>
        </form>
    </div>
</body>
</html>