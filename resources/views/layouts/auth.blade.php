<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Portal Siswa</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome untuk ikon mata password --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/icon pelajar.jpeg') }}">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center px-4">
        {{-- Logo dan Judul --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/icon pelajar.jpeg') }}" alt="Logo Sekolah"
                 class="w-20 h-20 mx-auto rounded-full shadow-md mb-3">
            <h1 class="text-2xl font-bold text-[#1e3a8a]">Portal Siswa</h1>
            <p class="text-gray-500 text-sm">SMKN 2 Yogyakarta</p>
        </div>

        {{-- Konten utama (form login / lupa password / reset password) --}}
        <div class="w-full max-w-md">
            @yield('content')
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6 text-gray-500 text-sm">
            © {{ date('Y') }} SMKN 2 Yogyakarta — All Rights Reserved
        </div>
    </div>

</body>
</html>
