<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>405 - Method not allowed</title>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center text-sm">
        <h1 class="text-6xl font-bold text-gray-800">405</h1>
        <p class="text-lg text-gray-600 mt-4">Sorry! Method Not Allowed.</p>
        <p class="text-md text-gray-600 mt-4">The request method is not supported for this route. Please try again or go
            back to the homepage.</p>
        <a href="{{ url('/') }}"
            class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Go Back Home
        </a>
    </div>
</body>

</html>
