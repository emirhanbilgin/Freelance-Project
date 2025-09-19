<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakan Gıda Sipariş Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-100 to-white min-h-screen flex items-center justify-center">

<div class="bg-white shadow-xl rounded-2xl p-10 max-w-md w-full text-center">
    {{-- Logo --}}
    <div class="mb-6">
        <img src="{{ asset('logo.png') }}" alt="Hakan Gıda Logo" class="mx-auto w-36 h-36 object-contain">
    </div>

    {{-- Başlık --}}
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
        Hakan Gıda Sipariş Yönetimi
    </h1>
    <p class="text-gray-600 mb-6 text-sm md:text-base">
        Sisteme erişmek için giriş yapın ya da yeni hesap oluşturun.
    </p>

    {{-- Giriş Yap Butonu --}}
    <a href="{{ route('login') }}"
       class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 shadow-md mb-4">
         Giriş Yap
    </a>

    {{-- Kayıt Ol Linki --}}
    <p class="text-sm text-gray-600">
        Hesabınız yok mu?
        <a href="{{ route('register') }}" class="text-green-700 font-medium hover:underline">
            Kayıt olun
        </a>
    </p>
</div>

</body>
</html>
