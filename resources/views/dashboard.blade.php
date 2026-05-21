@extends('layouts.app')

@section('title', 'Dashboard Perpustakaan - The Midnight Archive')

@section('content')
<header class="bg-base shadow-sm border-b border-light/50 p-4 px-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 z-10">
    <div>
        <h2 class="text-2xl font-bold text-dark">Monitoring Sistem</h2>
        <p class="text-sm font-semibold text-secondary mt-1">Ringkasan aktivitas dan operasional arsip</p>
    </div>

    <!-- Role Switcher -->
    <div class="flex items-center gap-2 bg-light/20 p-1.5 rounded-md border border-light/50">
        <span class="text-xs text-secondary px-2 font-bold uppercase tracking-wider">Akses:</span>
        <button onclick="setRole('pustakawan')" id="btn-pustakawan" class="px-4 py-1.5 text-sm font-bold rounded bg-base shadow-sm text-primary transition-all">Pustakawan</button>
        <button onclick="setRole('admin')" id="btn-admin" class="px-4 py-1.5 text-sm font-bold rounded text-secondary hover:text-primary transition-all">Administrator</button>
    </div>
</header>

<div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8">

    <!-- Admin Only: System Tools -->
    <div id="admin-tools" class="hidden bg-dark p-5 rounded-md shadow-lg flex flex-col md:flex-row justify-between items-center gap-4 border-l-4 border-primary">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-secondary/50 rounded text-light">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-base">Alat Administrator Sistem</h3>
                <p class="text-sm font-semibold text-light/80">Akses panel export data operasional dan log sistem penuh</p>
            </div>
        </div>
        <div>
            <button class="w-full md:w-auto flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-base px-6 py-2.5 rounded shadow-lg font-bold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Data Log (CSV/PDF)
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-base p-6 rounded-md shadow-sm border border-light/60">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Total Koleksi</p>
                    <h3 class="text-3xl font-bold text-dark">12,450</h3>
                </div>
                <div class="p-2.5 bg-light/30 rounded text-primary"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg></div>
            </div>
        </div>
        <div class="bg-base p-6 rounded-md shadow-sm border border-light/60">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Sedang Dipinjam</p>
                    <h3 class="text-3xl font-bold text-dark">842</h3>
                </div>
                <div class="p-2.5 bg-light/30 rounded text-primary"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg></div>
            </div>
        </div>
        <div class="bg-base p-6 rounded-md shadow-sm border border-red-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Terlambat</p>
                    <h3 class="text-3xl font-bold text-danger">45</h3>
                </div>
                <div class="p-2.5 bg-red-50 rounded text-danger"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            </div>
        </div>
        <div class="bg-base p-6 rounded-md shadow-sm border border-warning/20">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-2">Estimasi Denda</p>
                    <h3 class="text-3xl font-bold text-dark">Rp 450K</h3>
                </div>
                <div class="p-2.5 bg-orange-50 rounded text-warning"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Statistika Tren Peminjaman -->
        <div class="bg-base p-6 rounded-md shadow-sm border border-light/60 lg:col-span-2">
            <h3 class="text-lg font-bold text-dark mb-1">Statistika Tren Peminjaman</h3>
            <p class="text-sm font-semibold text-secondary mb-6">Grafik fluktuasi aktivitas peminjaman 6 bulan terakhir</p>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Status Ketersediaan -->
        <div class="bg-base p-6 rounded-md shadow-sm border border-light/60 flex flex-col">
            <h3 class="text-lg font-bold text-dark mb-1">Ketersediaan Buku</h3>
            <p class="text-sm font-semibold text-secondary mb-6">Rasio buku tersedia vs dipinjam</p>
            <div class="relative flex-1 w-full flex justify-center items-center min-h-[200px]">
                <canvas id="availabilityChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-6">

        <!-- Data Anggota Aktif -->
        <div class="bg-base p-6 rounded-md shadow-sm border border-light/60">
            <h3 class="text-lg font-bold text-dark mb-1">Statistik Keanggotaan</h3>
            <p class="text-sm font-semibold text-secondary mb-6">Anggota Baru vs Aktif (Bulan Ini)</p>
            <div class="relative h-60 w-full">
                <canvas id="memberChart"></canvas>
            </div>
        </div>

        <!-- Laporan Keterlambatan dan Denda (Tabel) -->
        <div class="bg-base rounded-md shadow-sm border border-light/60 lg:col-span-2 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-light/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-dark mb-1">Log Keterlambatan & Denda</h3>
                    <p class="text-sm font-semibold text-secondary">Prioritas penanganan denda anggota</p>
                </div>
                <button class="text-sm text-base bg-dark hover:bg-secondary px-4 py-2 rounded font-bold transition-colors">Lihat Detail</button>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-light/20 text-dark text-xs uppercase tracking-wider font-bold">
                            <th class="p-4">Peminjam</th>
                            <th class="p-4">Judul Buku</th>
                            <th class="p-4">Jatuh Tempo</th>
                            <th class="p-4">Terlambat</th>
                            <th class="p-4">Denda</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-light/50 font-semibold text-secondary">
                        <tr class="hover:bg-light/10 transition-colors">
                            <td class="p-4 text-dark font-bold">Ahmad Faisal</td>
                            <td class="p-4">The Clean Coder</td>
                            <td class="p-4">18 Mei 2026</td>
                            <td class="p-4 text-danger font-bold">3 Hari</td>
                            <td class="p-4 text-dark">Rp 6.000</td>
                            <td class="p-4"><span class="px-2.5 py-1 bg-red-50 text-danger rounded border border-red-200 text-xs font-bold">Belum Lunas</span></td>
                        </tr>
                        <tr class="hover:bg-light/10 transition-colors">
                            <td class="p-4 text-dark font-bold">Siti Aminah</td>
                            <td class="p-4">Design Patterns</td>
                            <td class="p-4">15 Mei 2026</td>
                            <td class="p-4 text-danger font-bold">6 Hari</td>
                            <td class="p-4 text-dark">Rp 12.000</td>
                            <td class="p-4"><span class="px-2.5 py-1 bg-emerald-50 text-success rounded border border-emerald-200 text-xs font-bold">Lunas</span></td>
                        </tr>
                        <tr class="hover:bg-light/10 transition-colors">
                            <td class="p-4 text-dark font-bold">Budi Santoso</td>
                            <td class="p-4">Refactoring UI</td>
                            <td class="p-4">20 Mei 2026</td>
                            <td class="p-4 text-danger font-bold">1 Hari</td>
                            <td class="p-4 text-dark">Rp 2.000</td>
                            <td class="p-4"><span class="px-2.5 py-1 bg-red-50 text-danger rounded border border-red-200 text-xs font-bold">Belum Lunas</span></td>
                        </tr>
                        <tr class="hover:bg-light/10 transition-colors">
                            <td class="p-4 text-dark font-bold">Diana Putri</td>
                            <td class="p-4">Sapiens</td>
                            <td class="p-4">10 Mei 2026</td>
                            <td class="p-4 text-danger font-bold">11 Hari</td>
                            <td class="p-4 text-dark">Rp 22.000</td>
                            <td class="p-4"><span class="px-2.5 py-1 bg-red-50 text-danger rounded border border-red-200 text-xs font-bold">Belum Lunas</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
