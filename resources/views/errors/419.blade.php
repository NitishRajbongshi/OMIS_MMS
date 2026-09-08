<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Session Expired</title>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center text-sm">
        <h1 class="text-6xl font-bold text-gray-800">419</h1>
        <p class="text-lg text-gray-600 mt-4">Oops! Session Expired.</p>
        <p class="text-md text-gray-600 mt-4">Your session has expired. Please click the button below to log in again.
        </p>
        <a href="{{ url('login') }}"
            class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Login to Continue
        </a>
    </div>
</body>

</html>
