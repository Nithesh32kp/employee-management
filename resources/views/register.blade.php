<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Employee Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-amber-100 flex items-center justify-center p-4">

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

        {{-- Left: Logo --}}
        <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-amber-400 to-amber-600 p-10">
            <div class="w-full max-w-sm rounded-2xl bg-white p-4 shadow-lg">
                <img src="{{ asset('Logo/creative-bees-logo.png') }}" alt="Creative Bees"
                    class="block h-auto max-h-24 w-full object-contain">
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="p-8 sm:p-12 flex flex-col justify-center">

            <div class="md:hidden flex justify-center mb-6">
                <img src="{{ asset('Logo/creative-bees-logo.png') }}" alt="Creative Bees"
                    class="block h-auto max-h-14 w-full max-w-[220px] object-contain">
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf

                <div>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" required
                        autofocus
                        class="w-full px-4 py-3 border rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('name') border-red-500 @else border-gray-300 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address"
                        required
                        class="w-full px-4 py-3 border rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('email') border-red-500 @else border-gray-300 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-4 py-3 border rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('password') border-red-500 @else border-gray-300 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">

                <button type="submit"
                    class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    Register
                </button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-amber-600 font-semibold hover:underline">Login</a>
            </p>
        </div>
    </div>

</body>

</html>
