@extends('layouts.app')

@section('title', 'Dashboard Anggota — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full max-w-7xl mx-auto">

    <div class="relative bg-gradient-to-br from-[#1B262C] via-[#0F4C75] to-[#3282B8] rounded-3xl p-8 sm:p-10 text-white shadow-[0_8px_30px_rgba(15,76,117,0.2)] mb-10 overflow-hidden group">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
        <div class="absolute -left-8 -bottom-8 w-40 h-40 bg-[#BBE1FA]/20 rounded-full blur-2xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div>
                <h2 class="font-montserrat text-3xl sm:text-4xl font-bold mb-2 tracking-tight">Halo, {{ auth()->user()->nama_lengkap ?? 'Anggota' }}!</h2>
                <p class="text-[#BBE1FA] font-medium text-sm sm:text-base max-w-xl leading-relaxed">Selamat datang kembali di Smart Library. Jelajahi dunia baru hari ini, selesaikan bacaanmu, atau temukan inspirasi dari koleksi terpopuler kami.</p>
            </div>
            <div class="hidden md:flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-inner">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="mb-12">
        <div class="flex justify-between items-end mb-6 border-b border-[#BBE1FA] pb-4">
            <div>
                <h3 class="font-montserrat font-bold text-[#1B262C] text-xl flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#3282B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Sedang Dipinjam
                </h3>
                <p class="text-sm text-[#0F4C75] opacity-80 mt-1">Buku yang saat ini masih dalam masa peminjamanmu.</p>
            </div>
            <a href="{{ route('peminjamans.index') }}" class="text-xs font-bold text-[#3282B8] hover:text-[#1B262C] transition-colors flex items-center gap-1 group">
                Lihat Riwayat
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        @if($sedangDipinjam->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sedangDipinjam as $pinjam)
                <div class="bg-white border border-[#BBE1FA] rounded-2xl p-5 shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)] hover:border-[#3282B8] transition-all flex flex-col relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#3282B8] to-[#0F4C75] transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>

                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-20 bg-[#F4F9FD] rounded-xl flex-shrink-0 flex items-center justify-center text-[#3282B8] border border-[#BBE1FA]/50 group-hover:bg-[#3282B8] group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[10px] font-bold tracking-widest text-[#0F4C75] opacity-70 uppercase">
                                    {{ $pinjam->bookItem->kode_buku ?? '-' }}
                                </span>
                                @if($pinjam->status_peminjaman === 'Dipinjam')
                                    <span class="px-2 py-0.5 rounded bg-[#F57C00]/10 text-[#F57C00] text-[10px] font-bold">Dibawa</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-[#3282B8]/10 text-[#3282B8] text-[10px] font-bold">Validasi</span>
                                @endif
                            </div>
                            <h4 class="font-montserrat font-bold text-[#1B262C] text-sm line-clamp-2 leading-tight" title="{{ $pinjam->bookItem->book->judul ?? 'Judul Tidak Diketahui' }}">
                                {{ $pinjam->bookItem->book->judul ?? 'Judul Tidak Diketahui' }}
                            </h4>
                        </div>
                    </div>

                    <div class="mt-auto pt-4 border-t border-[#BBE1FA]/50 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-xs font-semibold {{ \Carbon\Carbon::parse($pinjam->due_at)->isPast() ? 'text-[#D32F2F]' : 'text-[#0F4C75]' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Batas: {{ \Carbon\Carbon::parse($pinjam->due_at)->translatedFormat('d M Y') }}
                        </div>
                        <a href="{{ route('peminjamans.show', $pinjam->id) }}" class="w-8 h-8 rounded-full bg-[#F4F9FD] flex items-center justify-center text-[#3282B8] hover:bg-[#3282B8] hover:text-white transition-colors" title="Lihat Detail Peminjaman">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="w-full py-12 flex flex-col items-center justify-center bg-white border border-[#BBE1FA] rounded-2xl shadow-[0_2px_16px_rgba(27,38,44,0.04)]">
                <div class="w-16 h-16 bg-[#F4F9FD] rounded-full flex items-center justify-center text-[#0F4C75] opacity-50 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <p class="font-montserrat font-bold text-lg text-[#1B262C] mb-1">Belum ada buku yang dipinjam</p>
                <p class="text-sm font-opensans text-[#0F4C75] opacity-70 mb-5 text-center">Yuk, eksplorasi koleksi kami dan mulai membaca hari ini!</p>
                <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-2.5 bg-[#3282B8] hover:bg-[#1B262C] text-white font-montserrat text-sm font-bold rounded-xl transition-colors duration-300 shadow-lg shadow-[#3282B8]/30">
                    Cari Buku Sekarang
                </a>
            </div>
        @endif
    </div>

    <div>
        <div class="flex justify-between items-end mb-6 border-b border-[#BBE1FA] pb-4">
            <div>
                <h3 class="font-montserrat font-bold text-[#1B262C] text-xl flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#D32F2F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                    </svg>
                    Buku Terpopuler
                </h3>
                <p class="text-sm text-[#0F4C75] opacity-80 mt-1">Pilihan favorit yang paling sering dipinjam oleh anggota lain.</p>
            </div>
            <a href="{{ route('books.index') }}" class="text-xs font-bold text-[#3282B8] hover:text-[#1B262C] transition-colors flex items-center gap-1 group hidden sm:flex">
                Jelajahi Semua
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        @if($rekomendasi->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach($rekomendasi as $buku)
                <div class="group bg-white border border-[#BBE1FA] rounded-2xl overflow-hidden shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.12)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-full relative">

                    <a href="{{ route('books.show', $buku->id) }}" class="absolute inset-0 z-10"></a>

                    <div class="w-full pt-[130%] bg-[#F4F9FD] relative overflow-hidden flex items-center justify-center border-b border-[#BBE1FA]/50 group-hover:border-[#3282B8]/30 transition-colors">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-16 h-16 text-[#3282B8] opacity-20 group-hover:scale-110 group-hover:opacity-40 transition-all duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 flex-grow flex flex-col bg-white">
                        <div class="mb-2">
                            <span class="inline-block px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider bg-[#3282B8]/10 text-[#3282B8] uppercase">
                                {{ $buku->kategori ?? 'Umum' }}
                            </span>
                        </div>
                        <h4 class="font-montserrat font-bold text-[#1B262C] text-sm mb-1 line-clamp-2 leading-snug group-hover:text-[#3282B8] transition-colors">
                            {{ $buku->judul }}
                        </h4>
                        <p class="text-xs text-[#0F4C75] font-semibold opacity-70 mt-auto flex items-center gap-1.5 pt-2">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            <span class="truncate">{{ $buku->penulis ?? 'Penulis Tidak Diketahui' }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="w-full py-12 flex flex-col items-center justify-center bg-white border border-[#BBE1FA] rounded-2xl shadow-[0_2px_16px_rgba(27,38,44,0.04)]">
                <div class="w-16 h-16 bg-[#F4F9FD] rounded-full flex items-center justify-center text-[#0F4C75] opacity-50 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="font-montserrat font-bold text-lg text-[#1B262C] mb-1">Belum ada data buku</p>
                <p class="text-sm font-opensans text-[#0F4C75] opacity-70 text-center">Buku-buku populer akan otomatis muncul di sini setelah ada riwayat transaksi.</p>
            </div>
        @endif
    </div>

</div>
@endsection
