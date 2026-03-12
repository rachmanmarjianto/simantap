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
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            
            <div class="flex border-b">
                <button class="w-full py-4 text-sm font-bold text-gray-400 hover:bg-gray-50 transition" onclick="document.getElementById('login-form').submit();">LOG IN</button>  
                <button class="w-full py-4 text-sm font-bold text-unair-blue border-b-2 border-blue-800 bg-blue-50"  onclick="document.getElementById('register-form').submit();">DAFTAR AKUN</button>
                              
            </div>

            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-extrabold text-gray-800">Buat Akun Baru</h1>
                    <p class="text-gray-500 text-sm mt-2">Daftarkan diri Anda untuk mulai menggunakan fasilitas laboratorium UNAIR.</p>
                </div>

                <div class="flex bg-gray-100 p-1 rounded-xl mb-8">
                    {{-- <button class="flex-1 py-3 text-sm font-bold rounded-lg transition-all tab-active" id="btn-internal">
                        <i class="fas fa-graduation-cap mr-2"></i> Civitas UNAIR
                    </button> --}}
                    {{-- <button class="flex-1 py-3 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-gray-700" id="btn-eksternal">
                        <i class="fas fa-building mr-2"></i> Mitra / Umum
                    </button> --}}
                    <button class="flex-1 py-3 text-sm font-bold rounded-lg transition-all tab-active" id="btn-internal">
                        <i class="fas fa-building mr-2"></i> Mitra / Umum
                    </button>
                </div>

                <form action="#" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nama Lengkap & Gelar</label>
                        <input type="text" placeholder="Contoh: Dr. Budi Santoso, S.T." 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Alamat Email</label>
                        <input type="email" placeholder="nama@email.com" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                    </div>

                    <div>
                        <label id="label-identitas" class="block text-xs font-bold text-gray-700 uppercase mb-2">NIM / NIP / NIK</label>
                        <input type="text" placeholder="Masukkan nomor identitas" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label id="label-unit" class="block text-xs font-bold text-gray-700 uppercase mb-2">Fakultas / Departemen / Nama Perusahaan</label>
                        <input type="text" placeholder="Contoh: Fakultas Sains dan Teknologi" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Kata Sandi</label>
                        <input type="password" placeholder="••••••••" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Konfirmasi Sandi</label>
                        <input type="password" placeholder="••••••••" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white focus:outline-none transition">
                    </div>

                    <div class="md:col-span-2 flex items-start space-x-3 mt-2">
                        <input type="checkbox" id="terms" class="mt-1 w-4 h-4 text-blue-800 border-gray-300 rounded focus:ring-blue-500">
                        <label for="terms" class="text-sm text-gray-600 leading-snug">
                            Saya menyetujui <a href="#" class="text-blue-700 font-bold hover:underline">Syarat & Ketentuan</a> pemanfaatan aset laboratorium Universitas Airlangga Surabaya.
                        </label>
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <button type="submit" class="w-full py-4 btn-unair-yellow rounded-xl font-bold text-lg shadow-lg transition-transform active:scale-95">
                            Daftar Sekarang
                        </button>
                    </div>
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

    <form id="login-form" action="{{ route('publik_login') }}" method="GET" class="hidden">
        <!-- Form login bisa ditambahkan di sini -->
    </form>

</body>
</html>