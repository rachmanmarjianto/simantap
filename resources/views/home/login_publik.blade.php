<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Aset Lab UNAIR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .unair-blue { background-color: #004685; }
        .text-unair-blue { color: #004685; }
        .unair-yellow { background-color: #edb41a; }
        .btn-unair-yellow { background-color: #edb41a; color: #004685; }
        .btn-unair-yellow:hover { background-color: #d4a116; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <header class="p-6">
        <div class="container mx-auto flex flex-wrap items-center justify-center md:justify-start gap-4">
            <img src="{{ asset('app-assets/images/unair/unair.png') }}" alt="Logo UNAIR" class="h-14 w-auto">
            <div class="h-10 border-l-2 border-gray-300 hidden md:block"></div>
            <div class="text-center md:text-left">
                <h2 class="text-sm font-bold text-gray-600 uppercase tracking-widest leading-tight">Sistem Informasi<br>Aset Laboratorium</h2>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 pb-12">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            
            <div class="flex border-b">
                <button class="w-full py-4 text-sm font-bold text-unair-blue border-b-2 border-blue-800 bg-blue-50">LOG IN</button>
                <button class="w-full py-4 text-sm font-bold text-gray-400 hover:bg-gray-50 transition" onclick="document.getElementById('register-form').submit();">DAFTAR AKUN</button>
            </div>

            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-extrabold text-gray-800">Selamat Datang</h1>
                    <p class="text-gray-500 text-sm mt-2">Silakan masuk untuk mengakses layanan riset & praktikum</p>
                </div>

                <form action="#" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email atau NIK/NIM</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-user text-sm"></i>
                            </span>
                            <input type="text" placeholder="Masukkan ID Anda" 
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Kata Sandi</label>
                            <a href="#" class="text-xs font-bold text-blue-700 hover:underline">Lupa Password?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-lock text-sm"></i>
                            </span>
                            <input type="password" placeholder="••••••••" 
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="w-4 h-4 text-blue-800 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Ingat akun saya</label>
                    </div>

                    <button type="submit" class="w-full py-3 btn-unair-yellow rounded-xl font-bold shadow-lg shadow-yellow-200 transform active:scale-95 transition">
                        Masuk ke Sistem
                    </button>
                </form>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                    <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-4 text-gray-400 font-semibold tracking-widest">Atau masuk dengan</span></div>
                </div>

                <a href="{{ route('login') }}" class="w-full flex items-center justify-center space-x-3 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition">
                    <img src="{{ asset('app-assets/images/unair/unair.png') }}" class="h-5">
                    <span>Cybercampus SSO</span>
                </a>

                <div class="w-full flex justify-center mt-6">
                    <a href="{{ route('publik_home') }}" class="text-sm font-bold text-gray-500 hover:text-unair-blue transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-gray-400 text-xs">
        <p>&copy; 2026 Direktorat Sistem Informasi - Universitas Airlangga</p>
    </footer>

    <form id="register-form" action="{{ route('publik_register') }}" method="GET" class="hidden">
        <!-- Form pendaftaran akun baru bisa ditambahkan di sini -->
    </form>

</body>
</html>