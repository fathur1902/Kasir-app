<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-400 flex items-center justify-center h-screen">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-white text-4xl font-bold">Logo</h1>
        </div>

        <form method="POST" action="{{ route('login') }}" class="bg-transparent">
            @csrf

            <div class="mb-4">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="username">Username</label>
                <input id="username" name="username" type="text" placeholder="Username . . ."
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Password . . ."
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none">
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-10 rounded-full shadow-md transition-all">
                    LOGIN
                </button>
            </div>
        </form>
    </div>

</body>

</html>
