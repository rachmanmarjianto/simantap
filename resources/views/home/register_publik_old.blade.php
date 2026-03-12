<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Informasi Aset Lab UNAIR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .unair-blue { background-color: #004685; }
        .text-unair-blue { color: #004685; }
        .unair-yellow { background-color: #edb41a; }
        .btn-unair-yellow { background-color: #edb41a; color: #004685; }
        .btn-unair-yellow:hover { background-color: #d4a116; }
        .tab-active { border-bottom: 2px solid #004685; color: #004685; background-color: #f0f7ff; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col font-sans">

    <header class="p-4 md:p-6 bg-white shadow-sm">
        <div class="container mx-auto flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <img src="https://upload.wikimedia.org/wikipedia/id/thumb/b/bf/Logo_Universitas_Airlangga.png/600px-Logo_Universitas_Airlangga.png" alt="Logo UNAIR" class="h-10 md:h-12 w-auto">
                <div class="h-8 border-l-2 border-gray-200 hidden sm:block"></div>
                <h2 class="text-xs font-bold text-gray-600 uppercase tracking-widest leading-tight hidden sm:block">Portal Registrasi<br>Layanan Riset</h2>
            </div>
            <a href="login.html" class="text-sm font-bold text-blue-800 hover:underline">Sudah punya akun? Masuk</a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-10">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden border border-gray-100">

            <div class="flex border-b">
                <button class="w-full py-4 text-sm font-bold text-gray-400 hover:bg-gray-50 transition" onclick="document.getElementById('login-form').submit();">LOG IN</button>
                <button class="w-full py-4 text-sm font-bold text-unair-blue border-b-2 border-blue-800 bg-blue-50" onclick="document.getElementById('register-form').submit();">DAFTAR AKUN</button>
            </div>
            
            <div class="p-8 md:p-10">
                <div class="mb-10 text-center">
                    <h1 class="text-3xl font-extrabold text-gray-800">Buat Akun Baru</h1>
                    <p class="text-gray-500 mt-2">Daftarkan diri Anda untuk mulai menggunakan fasilitas laboratorium UNAIR.</p>
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
            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-gray-500 text-xs">
        <p>&copy; 2026 Universitas Airlangga Surabaya. | Direktorat Logistik & Aset</p>
    </footer>

    <form id="login-form" action="{{ route('publik_login') }}" method="GET" class="hidden">
        <!-- Form login bisa ditambahkan di sini -->
    </form>

    <script>
        const btnInternal = document.getElementById('btn-internal');
        const btnEksternal = document.getElementById('btn-eksternal');
        const labelIdentitas = document.getElementById('label-identitas');
        const labelUnit = document.getElementById('label-unit');

        btnInternal.onclick = () => {
            btnInternal.className = "flex-1 py-3 text-sm font-bold rounded-lg transition-all tab-active";
            btnEksternal.className = "flex-1 py-3 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-gray-700";
            labelIdentitas.innerText = "NIM / NIP / NIK";
            labelUnit.innerText = "Fakultas / Departemen";
        }

        btnEksternal.onclick = () => {
            btnEksternal.className = "flex-1 py-3 text-sm font-bold rounded-lg transition-all tab-active";
            btnInternal.className = "flex-1 py-3 text-sm font-bold rounded-lg transition-all text-gray-500 hover:text-gray-700";
            labelIdentitas.innerText = "Nomor KTP (NIK)";
            labelUnit.innerText = "Nama Perusahaan / Instansi Asal";
        }
    </script>

</body>
</html>