<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-400 flex items-center justify-center h-screen">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo_pasar.png') }}" alt="Logo" class="mx-auto w-48 h-48">
        </div>

        <form method="POST" action="{{ route('login') }}" class="bg-transparent">
            @csrf

            <div class="mb-4">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="email">Email</label>
                <input id="email" name="email" type="text" placeholder="Email"
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none">
                @error('email')
                    <p class="text-red-200 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 relative">
                <label class="block text-white font-bold mb-2 uppercase text-sm" for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Password"
                    class="w-full px-4 py-3 rounded-full shadow-md text-gray-700 focus:outline-none">
                <button type="button" class="absolute right-3 top-10 text-gray-300 toggle-password" data-target="password">
                    <i class="fas fa-eye"></i>
                </button>
                @error('password')
                    <p class="text-red-200 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-10 rounded-full shadow-md transition-all">
                    LOGIN
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
</body>

</html>