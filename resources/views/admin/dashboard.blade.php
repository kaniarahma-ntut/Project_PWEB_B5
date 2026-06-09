@extends('layouts.app')

@section('title', 'Dashboard Admin — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

@php
    // Menghitung total eksemplar dan persentase secara dinamis untuk progress bar
    $totalEksemplar = ($tersedia ?? 0) + ($dipinjam ?? 0) + ($rusak ?? 0);
    $persenTersedia = $totalEksemplar > 0 ? ($tersedia / $totalEksemplar) * 100 : 0;
    $persenDipinjam = $totalEksemplar > 0 ? ($dipinjam / $totalEksemplar) * 100 : 0;
    $persenRusak = $totalEksemplar > 0 ? ($rusak / $totalEksemplar) * 100 : 0;
@endphp

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border border-[#BBE1FA] rounded-2xl p-6 shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)] hover:border-[#3282B8] transition-all relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#3282B8] opacity-5 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="w-10 h-10 rounded-xl bg-[#3282B8]/10 text-[#3282B8] flex items-center justify-center text-xl mb-4">📕</div>
            <h3 class="font-montserrat text-3xl font-bold text-[#1B262C] mb-1">{{ $totalBuku ?? 0 }}</h3>
            <p class="text-xs font-semibold text-[#0F4C75] opacity-80 uppercase tracking-wide">Total Judul Buku</p>
            <p class="text-xs font-bold text-[#3282B8] mt-3">Data waktu nyata</p>
        </div>

        <div class="bg-white border border-[#BBE1FA] rounded-2xl p-6 shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)] hover:border-[#3282B8] transition-all relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#D32F2F] opacity-5 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="w-10 h-10 rounded-xl bg-[#D32F2F]/10 text-[#D32F2F] flex items-center justify-center text-xl mb-4">⏱</div>
            <h3 class="font-montserrat text-3xl font-bold text-[#1B262C] mb-1">{{ $terlambat ?? 0 }}</h3>
            <p class="text-xs font-semibold text-[#0F4C75] opacity-80 uppercase tracking-wide">Peminjaman Terlambat</p>
            <p class="text-xs font-bold text-[#D32F2F] mt-3">Perlu ditindaklanjuti</p>
        </div>

        <div class="bg-white border border-[#BBE1FA] rounded-2xl p-6 shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)] hover:border-[#3282B8] transition-all relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#1B262C] opacity-5 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="w-10 h-10 rounded-xl bg-[#1B262C]/10 text-[#1B262C] flex items-center justify-center text-xl mb-4">👤</div>
            <h3 class="font-montserrat text-3xl font-bold text-[#1B262C] mb-1">{{ $anggotaAktif ?? 0 }}</h3>
            <p class="text-xs font-semibold text-[#0F4C75] opacity-80 uppercase tracking-wide">Total Anggota</p>
            <p class="text-xs font-bold text-[#3282B8] mt-3">Terdaftar di sistem</p>
        </div>

        <div class="bg-white border border-[#BBE1FA] rounded-2xl p-6 shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)] hover:border-[#3282B8] transition-all relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#0F4C75] opacity-5 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="w-10 h-10 rounded-xl bg-[#0F4C75]/10 text-[#0F4C75] flex items-center justify-center text-xl mb-4">💰</div>
            <h3 class="font-montserrat text-2xl font-bold text-[#1B262C] mb-1">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</h3>
            <p class="text-xs font-semibold text-[#0F4C75] opacity-80 uppercase tracking-wide">Denda Belum Lunas</p>
            <p class="text-xs font-bold text-[#3282B8] mt-3">Akumulasi biaya</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <div class="bg-white border border-[#BBE1FA] rounded-2xl p-6 shadow-[0_2px_16px_rgba(27,38,44,0.04)] lg:col-span-2">
            <div class="flex justify-between items-center mb-6 border-b border-[#BBE1FA] pb-4">
                <h3 class="font-montserrat font-bold text-[#1B262C] text-lg">Tren Peminjaman</h3>
                <span class="text-xs font-bold text-[#3282B8] cursor-pointer hover:text-[#1B262C] transition-colors">6 Bulan Terakhir</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-[#BBE1FA] rounded-2xl p-6 shadow-[0_2px_16px_rgba(27,38,44,0.04)] flex flex-col">
            <div class="flex justify-between items-center mb-6 border-b border-[#BBE1FA] pb-4">
                <h3 class="font-montserrat font-bold text-[#1B262C] text-lg">Status Ketersediaan</h3>
            </div>
            <div class="relative h-48 w-full flex justify-center items-center flex-grow">
                <canvas id="pieChart"></canvas>
            </div>
            <div class="mt-6 space-y-4">
                <div>
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span class="text-[#3282B8]">Tersedia</span>
                        <span class="text-[#0F4C75] opacity-80">{{ $tersedia ?? 0 }} eks</span>
                    </div>
                    <div class="w-full h-2 bg-[#BBE1FA]/30 rounded-full overflow-hidden">
                        <div class="h-full bg-[#3282B8] rounded-full" style="width: {{ $persenTersedia }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span class="text-[#0F4C75]">Dipinjam</span>
                        <span class="text-[#0F4C75] opacity-80">{{ $dipinjam ?? 0 }} eks</span>
                    </div>
                    <div class="w-full h-2 bg-[#BBE1FA]/30 rounded-full overflow-hidden">
                        <div class="h-full bg-[#0F4C75] rounded-full" style="width: {{ $persenDipinjam }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-bold mb-2">
                        <span class="text-[#D32F2F]">Rusak / Hilang</span>
                        <span class="text-[#0F4C75] opacity-80">{{ $rusak ?? 0 }} eks</span>
                    </div>
                    <div class="w-full h-2 bg-[#BBE1FA]/30 rounded-full overflow-hidden">
                        <div class="h-full bg-[#D32F2F] rounded-full" style="width: {{ $persenRusak }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white border border-[#BBE1FA] rounded-2xl shadow-[0_2px_16px_rgba(27,38,44,0.04)] lg:col-span-2 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-[#BBE1FA] flex justify-between items-center">
                <h3 class="font-montserrat font-bold text-[#1B262C] text-lg">Laporan Keterlambatan</h3>
                <a href="#" class="text-xs font-bold text-[#3282B8] hover:text-[#1B262C] transition-colors">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F4F9FD]">
                            <th class="py-4 px-6 font-montserrat text-xs font-bold text-[#0F4C75] uppercase tracking-wider border-b border-[#BBE1FA]">Anggota</th>
                            <th class="py-4 px-6 font-montserrat text-xs font-bold text-[#0F4C75] uppercase tracking-wider border-b border-[#BBE1FA]">Buku</th>
                            <th class="py-4 px-6 font-montserrat text-xs font-bold text-[#0F4C75] uppercase tracking-wider border-b border-[#BBE1FA]">Keterlambatan</th>
                            <th class="py-4 px-6 font-montserrat text-xs font-bold text-[#0F4C75] uppercase tracking-wider border-b border-[#BBE1FA]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#BBE1FA]/50">
                        @if(isset($laporanTerlambat) && count($laporanTerlambat) > 0)
                            @foreach($laporanTerlambat as $row)
                            <tr class="hover:bg-[#BBE1FA]/10 transition-colors">
                                <td class="py-4 px-6 text-sm font-bold text-[#1B262C]">{{ $row[0] }}</td>
                                <td class="py-4 px-6 text-sm font-semibold text-[#0F4C75]">{{ $row[1] }}</td>
                                <td class="py-4 px-6 text-sm font-semibold text-[#D32F2F]">{{ $row[2] }}</td>
                                <td class="py-4 px-6">
                                    @if($row[3] === 'lunas')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-montserrat font-bold bg-[#2E7D32]/10 text-[#2E7D32]">Lunas</span>
                                    @elseif($row[3] === 'proses')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-montserrat font-bold bg-[#F57C00]/10 text-[#F57C00]">Proses</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-montserrat font-bold bg-[#D32F2F]/10 text-[#D32F2F]">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="py-6 px-6 text-sm text-center font-semibold text-[#0F4C75] opacity-60">Tidak ada laporan keterlambatan saat ini.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border border-[#BBE1FA] rounded-2xl p-6 shadow-[0_2px_16px_rgba(27,38,44,0.04)]">
            <div class="flex justify-between items-center mb-6 border-b border-[#BBE1FA] pb-4">
                <h3 class="font-montserrat font-bold text-[#1B262C] text-lg">Aktivitas Anggota</h3>
                <a href="{{ route('users.index') }}" class="text-xs font-bold text-[#3282B8] hover:text-[#1B262C] transition-colors">Kelola →</a>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-[#BBE1FA]/30 rounded-xl p-4 text-center">
                    <div class="font-montserrat text-2xl font-bold text-[#1B262C]">{{ $anggotaBaru ?? 0 }}</div>
                    <div class="text-xs font-bold text-[#0F4C75] mt-1">Baru bln ini</div>
                </div>
                <div class="bg-[#3282B8]/10 rounded-xl p-4 text-center">
                    <div class="font-montserrat text-2xl font-bold text-[#3282B8]">{{ $anggotaMeminjam ?? 0 }}</div>
                    <div class="text-xs font-bold text-[#0F4C75] mt-1">Aktif pinjam</div>
                </div>
            </div>

            <h4 class="text-xs font-bold text-[#0F4C75] uppercase tracking-wider mb-4">Top Peminjam</h4>
            <div class="space-y-4">
                @if(isset($topPeminjam) && count($topPeminjam) > 0)
                    @foreach($topPeminjam as $m)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#BBE1FA] flex items-center justify-center text-[#1B262C] font-bold text-sm">
                                {{ $m[0] }}
                            </div>
                            <div>
                                <p class="font-bold text-sm text-[#1B262C]">{{ $m[1] }}</p>
                                <p class="text-xs text-[#0F4C75] font-semibold">Anggota</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-montserrat font-bold text-[#3282B8] text-lg">{{ $m[2] }}</p>
                            <p class="text-[10px] text-[#0F4C75] font-bold">BUKU</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-xs text-center font-semibold text-[#0F4C75] opacity-60 py-4">Belum ada riwayat peminjaman.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // ── TREND CHART ──
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
            datasets: [
                {
                    label: 'Dipinjam',
                    data: [52, 68, 45, 80, 74, 90], // Placeholder tren bulanan
                    backgroundColor: '#1B262C',
                    borderRadius: 4,
                },
                {
                    label: 'Dikembalikan',
                    data: [48, 60, 50, 72, 70, 82], // Placeholder tren bulanan
                    backgroundColor: '#3282B8',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { font: { family: 'Open Sans', size: 12, weight: '600' }, color: '#0F4C75' }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Open Sans', size: 11, weight:'600' }, color: '#0F4C75' } },
                y: { grid: { color: '#BBE1FA' }, ticks: { font: { family: 'Open Sans', size: 11, weight:'600' }, color: '#0F4C75' } }
            }
        }
    });

    // ── PIE CHART (Dinamis dari Database) ──
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipinjam', 'Rusak'],
            datasets: [{
                data: [
                    {{ $tersedia ?? 0 }},
                    {{ $dipinjam ?? 0 }},
                    {{ $rusak ?? 0 }}
                ],
                backgroundColor: ['#3282B8', '#0F4C75', '#D32F2F'],
                borderColor: '#FFFFFF',
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endsection
