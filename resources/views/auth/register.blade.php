<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-400 flex items-center justify-center h-screen">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo pasar.png') }}" alt="Logo" class="mx-auto w-48 h-48">
            {{-- <h1 class="text-white text-4xl font-bold">Logo</h1> --}}
        </div>

        <!-- Form Register -->
        <form method="POST" action="{{ route('register') }}" class="bg-transparent">
            @csrf

            <div class="mb-4">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="name">Name</label>
                <input id="name" name="name" type="text" placeholder="Your Full Name . . ."
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                @error('name')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="Your Email . . ."
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none @error('email') border-red-500 @enderror" value="{{ old('email') }}">
                @error('email')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Password . . ."
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none @error('password') border-red-500 @enderror">
                @error('password')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm Password . . ."
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none">
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-10 rounded-full shadow-md transition-all">
                    REGISTER
                </button>
            </div>
        </form>
    </div>

</body>

</html>