<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Layanan Aset Laboratorium - Universitas Airlangga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .unair-blue { background-color: #004685; }
        .text-unair-blue { color: #004685; }
        .unair-yellow { background-color: #edb41a; }
        .bg-unit { background-color: #f0f7ff; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-4 mb-2 md:mb-0">
                    <img src="{{ asset('app-assets/images/unair/unair.png') }}" alt="Logo UNAIR" class="h-12 w-auto">
                    <div class="border-l-2 border-gray-200 h-8 hidden md:block"></div>
                    <span class="text-sm font-bold text-gray-600 hidden md:block uppercase tracking-wider">Layanan Aset<br>Laboratorium</span>
                </div>

                <nav class="hidden md:flex space-x-8 font-medium">
                    <a href="#" class="hover:text-blue-800 transition">Beranda</a>
                    <a href="#katalog" class="hover:text-blue-800 transition">Katalog Perangkat</a>
                    <a href="#" class="hover:text-blue-800 transition">Prosedur</a>
                    <a href="#" class="hover:text-blue-800 transition">Kontak</a>
                </nav>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('publik_login') }}" class="px-4 py-2 border border-blue-800 text-blue-800 rounded-lg font-semibold hover:bg-blue-50 transition">Login</a>
                    <a href="{{ route('publik_register') }}" class="px-4 py-2 btn-unair rounded-lg font-bold shadow-md">Daftar</a>
                </div>
            </div>
        </div>
    </header>

    <section class="unair-blue text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Optimalisasi Riset dengan Aset Terbaik</h1>
            <p class="text-xl opacity-90 max-w-2xl mx-auto mb-8">
                Universitas Airlangga menyediakan akses perangkat laboratorium mutakhir untuk civitas akademika internal maupun mitra eksternal guna mendukung inovasi dan penelitian berkualitas.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="#katalog" class="bg-white text-blue-900 px-6 py-3 rounded-full font-bold hover:bg-gray-100 transition">Lihat Katalog</a>
                <a href="#" class="border border-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-blue-900 transition">Panduan Layanan</a>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-4">
                <div class="bg-blue-100 p-4 rounded-full text-blue-800 text-2xl"><i class="fas fa-microscope"></i></div>
                <div><h3 class="font-bold text-2xl">150+</h3><p class="text-gray-500">Perangkat Lab</p></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-4">
                <div class="bg-yellow-100 p-4 rounded-full text-yellow-600 text-2xl"><i class="fas fa-building"></i></div>
                <div><h3 class="font-bold text-2xl">45</h3><p class="text-gray-500">Laboratorium Terpadu</p></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-4">
                <div class="bg-green-100 p-4 rounded-full text-green-600 text-2xl"><i class="fas fa-check-circle"></i></div>
                <div><h3 class="font-bold text-2xl">Tersertifikasi</h3><p class="text-gray-500">Standar Internasional</p></div>
            </div>
        </div>
    </div>

    <main id="katalog" class="container mx-auto px-4 py-16">
        <div class="flex flex-col md:flex-row justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Katalog Perangkat Laboratorium</h2>
                <p class="text-gray-600 mt-2">Daftar alat tersedia yang dapat digunakan untuk kebutuhan akademik dan pengujian.</p>
            </div>
            <div class="mt-4 md:mt-0 w-full md:w-1/3">
                <div class="relative">
                    <input type="text" placeholder="Cari alat (ex: PCR, Mikroskop)..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="filterAset(this.value)">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 w-full">
            @php
                $i = 0;
            @endphp

            @foreach($aset as $a)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex flex-col overflow-hidden" id="aset-{{ $a->kode_barang_aset }}">
                    @if($i%2 == 0)
                        <div class="p-1 unair-blue"></div>

                    @else
                        <div class="p-1 unair-yellow"></div>
                    @endif
                    <div class="p-6 flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <span class="bg-blue-50 text-blue-700 text-[10px] uppercase font-bold px-2 py-1 rounded tracking-widest" id="span-merk-{{ $a->kode_barang_aset }}">Merk: {{ $a->merk_barang }} {{ $a->keterangan }}</span>
                            <div class="flex space-x-1">
                                <span title="Bisa untuk Penelitian" class="text-blue-500 bg-blue-50 w-8 h-8 flex items-center justify-center rounded-full text-xs"><i class="fas fa-microscope"></i></span>
                                <span title="Bisa untuk Praktikum" class="text-green-500 bg-green-50 w-8 h-8 flex items-center justify-center rounded-full text-xs"><i class="fas fa-user-graduate"></i></span>
                            </div>
                        </div>

                        <h3 class="text-xl font-extrabold text-gray-800 mb-2 leading-snug">{{ $a->nama_barang }}</h3>
                        
                        <div class="flex items-center text-sm text-gray-500 mt-4 pt-4 border-t border-dashed">
                            <i class="fas fa-building mr-2 text-gray-400"></i>
                            <span class="font-medium uppercase tracking-tight text-xs" id="span-unit-{{ $a->kode_barang_aset }}">Unit: {{ $a->nama_unit_kerja }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 flex justify-between items-center">
                        <span class="text-xs font-semibold text-gray-400" id="span-id-{{ $a->kode_barang_aset }}">ID: {{ $a->kode_barang_aset }}</span>
                        {{-- <button class="bg-white border border-gray-200 text-blue-800 px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-800 hover:text-white transition">Lihat Detail</button> --}}
                    </div>
                </div>
                @php
                    $i++;
                @endphp
            @endforeach

        </div>

        <div class="text-center mt-12">
            {{-- <button class="border-2 border-gray-300 px-8 py-2 rounded-lg font-bold text-gray-600 hover:bg-gray-100 transition">Lihat Selengkapnya</button> --}}
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <img src="{{ asset('app-assets/images/unair/unair.png') }}" alt="Logo UNAIR" class="h-12 w-auto">
                <p class="text-gray-400 pr-10">Unit Layanan Terpadu Universitas Airlangga Surabaya. Berkomitmen mendukung penuh kegiatan riset nasional dengan fasilitas terbaik di kelasnya.</p>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">Tautan Cepat</h4>
                <ul class="text-gray-400 space-y-2">
                    <li><a href="#" class="hover:text-white transition">SIM-Aset</a></li>
                    <li><a href="#" class="hover:text-white transition">Prosedur Peminjaman</a></li>
                    <li><a href="#" class="hover:text-white transition">Daftar Laboratorium</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-4">Kontak</h4>
                <p class="text-gray-400 text-sm">Kampus C UNAIR, Mulyorejo, Surabaya<br>Email: helpdesk@unair.ac.id<br>Telp: (031) 5914042</p>
            </div>
        </div>
        <div class="container mx-auto px-4 mt-8 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; 2026 Universitas Airlangga Surabaya. All rights reserved.
        </div>
    </footer>

    <script>
        function filterAset(query) {
            const asetCards = document.querySelectorAll('[id^="aset-"]');
            asetCards.forEach(card => {
                const namaBarang = card.querySelector('h3').textContent.toLowerCase();
                const unit = card.querySelector('[id^="span-unit-"]').textContent.toLowerCase();
                const id = card.querySelector('[id^="span-id-"]').textContent.toLowerCase();
                const merk = card.querySelector('[id^="span-merk-"]').textContent.toLowerCase();
                // console.log('Mencari:', query, 'di', namaBarang, unit, id, merk);
                if (namaBarang.includes(query.toLowerCase()) || unit.includes(query.toLowerCase()) || id.includes(query.toLowerCase()) || merk.includes(query.toLowerCase())) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>