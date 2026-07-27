<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Fasel Aquarium</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Fasel Aquarium
            </h1>
            <p class="mt-2 text-gray-500">
                Atur Ulang Password
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">

            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-2">
                Reset Password
            </h2>

            <p class="text-sm text-center text-gray-500 mb-6">
                Silakan masukkan password baru untuk akun Anda.
            </p>

            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm text-red-600">
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5" autocomplete="off">
                @csrf

                <input
                    type="hidden"
                    name="token"
                    value="{{ $token }}"
                >

                {{-- Email --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ request('email') }}"
                        readonly
                        class="w-full rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 text-gray-600 cursor-not-allowed"
                    >
                </div>

                {{-- Password Baru --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Password Baru
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password baru"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                    >
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Ulangi password baru"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 py-3 font-semibold text-white hover:bg-blue-700 transition"
                >
                    Reset Password
                </button>

                <a
                    href="{{ route('login') }}"
                    class="block text-center text-sm text-gray-500 hover:text-blue-600 transition"
                >
                    ← Kembali ke Login
                </a>

            </form>

        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            © {{ date('Y') }} Fasel Aquarium
        </p>

    </div>

</div>

</body>
</html>