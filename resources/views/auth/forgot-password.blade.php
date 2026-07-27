<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Fasel Aquarium</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        {{-- Logo / Nama Aplikasi --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                Fasel Aquarium
            </h1>
            <p class="text-gray-500 mt-2">
                Reset Password Akun
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl p-8">

            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-2">
                Lupa Password
            </h2>

            <p class="text-sm text-gray-500 text-center mb-6">
                Masukkan email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.
            </p>

            @if(session('success'))
                <div class="mb-5 rounded-lg bg-green-50 border border-green-200 p-4">
                    <p class="text-green-700 text-sm">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">
                    @foreach($errors->all() as $error)
                        <p class="text-red-600 text-sm">
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="contoh@email.com"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 text-white py-3 font-semibold hover:bg-blue-700 transition duration-200"
                >
                    Kirim Link Reset
                </button>
            </form>

            <div class="mt-6 text-center">
                <a
                    href="{{ route('login') }}"
                    class="text-sm text-gray-500 hover:text-blue-600 transition"
                >
                    ← Kembali ke Login
                </a>
            </div>

        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} Fasel Aquarium
        </p>

    </div>

</div>

</body>
</html>