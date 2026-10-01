<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Employee Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-amber-100 flex items-center justify-center p-4">

    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

        {{-- Left: Branding --}}
        <div
            class="hidden md:flex flex-col items-center justify-center bg-gradient-to-br from-amber-400 to-amber-600 p-10 text-white text-center">
            <div class="mb-6 w-full max-w-sm rounded-2xl bg-white p-4 shadow-lg">
                <img src="{{ asset('Logo/creative-bees-logo.png') }}" alt="Creative Bees"
                    class="block h-auto max-h-24 w-full object-contain">
            </div>
            <h1 class="text-3xl font-bold mb-2">Creative Bees</h1>
            {{-- <p class="text-amber-50 text-sm leading-relaxed">
                Manage your team, attendance and payroll<br>all in one place.
            </p> --}}
        </div>

        {{-- Right: Form --}}
        <div class="p-8 sm:p-12">
            {{-- Mobile logo --}}
            <div class="mb-6 flex justify-center md:hidden">
                <img src="{{ asset('Logo/creative-bees-logo.png') }}" alt="Creative Bees"
                    class="block h-auto max-h-14 w-full max-w-[220px] object-contain">
            </div>

            <h2 class="text-2xl font-bold text-gray-800">Welcome back</h2>
            <p class="text-sm text-gray-500 mt-1 mb-8">Login to Employee Management System</p>

            @if (session('status'))
                <p class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</p>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        placeholder="you@company.com" required autofocus
                        class="w-full px-4 py-2.5 border rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('email') border-red-500 @else border-gray-300 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" placeholder="••••••••" required
                            class="w-full px-4 py-2.5 pr-16 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <button type="button" onclick="togglePassword()" id="toggleBtn"
                            class="absolute inset-y-0 right-0 px-4 text-xs font-semibold text-amber-600 hover:text-amber-700">
                            Show
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                        Remember me
                    </label>
                    {{-- <a href="{{ route('password.request') }}" class="text-amber-600 hover:underline">Forgot password?</a> --}}
                </div>

                <button id="loginSubmit" type="submit" aria-busy="false"
                    class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:cursor-wait disabled:opacity-80">
                    <span class="inline-flex items-center justify-center gap-2">
                        <svg id="loginSpinner" class="hidden h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z" />
                        </svg>
                        <span id="loginLabel" aria-live="polite">Login</span>
                    </span>
                </button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-amber-600 font-semibold hover:underline">Register</a>
            </p>
            <p class="text-center text-xs text-gray-400 mt-8">
                © {{ date('Y') }} Creative Bees. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            if (this.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }

            this.dataset.submitting = 'true';
            const button = document.getElementById('loginSubmit');
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            document.getElementById('loginSpinner').classList.remove('hidden');
            document.getElementById('loginLabel').textContent = 'Signing in...';
        });

        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.getElementById('toggleBtn');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Hide' : 'Show';
        }
    </script>
</body>

</html>
