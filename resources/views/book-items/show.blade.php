@extends('layouts.app')

@section('title', 'Detail Eksemplar — ' . $bookItem->kode_buku)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('books.show', $bookItem->book_id) }}" class="text-[#0F4C75] hover:text-[#3282B8] font-semibold flex items-center gap-2 transition">
        &larr; Kembali ke Detail Buku
    </a>

    @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
        <div class="flex gap-2">
            <a href="{{ route('book-items.edit', $bookItem->id) }}"
               class="bg-[#3282B8] hover:bg-[#1B262C] text-white py-2 px-4 rounded text-sm font-semibold transition">
                Edit Eksemplar
            </a>

            @if(!$bookItem->trashed())
                <form action="{{ route('book-items.destroy', $bookItem->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm font-semibold transition"
                            onclick="return confirm('Yakin ingin menonaktifkan eksemplar fisik ini?')">
                        Nonaktifkan
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>

{{-- Detail Card --}}
<div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] mb-8 overflow-hidden relative">
    @if($bookItem->trashed())
        <div class="absolute inset-0 bg-red-500/5 z-10 pointer-events-none"></div>
    @endif

    <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8">
        {{-- Info Buku Utama --}}
        <div class="w-full md:w-1/2">
            <div class="inline-block bg-[#BBE1FA] text-[#1B262C] font-bold px-3 py-1 rounded text-xs mb-3 uppercase tracking-wider">
                Info Buku Induk
            </div>
            <h1 class="text-2xl font-montserrat font-bold text-[#1B262C] mb-2">
                {{ $bookItem->book->judul }}
            </h1>
            <p class="text-[#0F4C75] font-semibold mb-4">Penulis: {{ $bookItem->book->penulis }}</p>
        </div>

        {{-- Info Eksemplar --}}
        <div class="w-full md:w-1/2 bg-[#F4F9FD] p-6 rounded-xl border border-[#BBE1FA]">
            <h3 class="text-sm font-bold text-[#0F4C75] uppercase tracking-wider mb-4 border-b border-[#BBE1FA] pb-2">Spesifikasi Fisik</h3>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-[#0F4C75]/70 mb-1">Kode Buku / Eksemplar</p>
                    <p class="text-lg font-montserrat font-bold text-[#1B262C]">{{ $bookItem->kode_buku }}</p>
                </div>

                <div>
                    <p class="text-xs text-[#0F4C75]/70 mb-1">Status Ketersediaan</p>
                    @if($bookItem->status_ketersediaan === 'Tersedia')
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-green-100 text-green-700">Tersedia</span>
                    @elseif($bookItem->status_ketersediaan === 'Dipinjam')
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-yellow-100 text-yellow-700">Dipinjam</span>
                    @elseif($bookItem->status_ketersediaan === 'Rusak')
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-red-100 text-red-700">Rusak</span>
                    @elseif($bookItem->status_ketersediaan === 'Hilang')
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-gray-100 text-gray-700">Hilang</span>
                    @endif
                </div>

                <div>
                    <p class="text-xs text-[#0F4C75]/70 mb-1">Kode QR Fisik</p>
                    <p class="text-sm font-bold text-[#1B262C]">{{ $bookItem->kode_qr ?: 'Belum ada QR Terdaftar' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Riwayat Peminjaman --}}
<div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
    <div class="p-6 border-b border-[#BBE1FA] bg-[#F4F9FD]/50">
        <h3 class="text-lg font-montserrat font-bold text-[#1B262C]">
            Riwayat Peminjaman Eksemplar Ini
        </h3>
        <p class="text-sm text-[#0F4C75] mt-1">Daftar pengguna yang pernah meminjam fisik buku ini.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-[#0F4C75] text-[10px] uppercase font-bold tracking-wider">
                    <th class="px-6 py-4">Peminjam</th>
                    <th class="px-6 py-4">Tgl Pinjam</th>
                    <th class="px-6 py-4">Jatuh Tempo</th>
                    <th class="px-6 py-4">Status Peminjaman</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#BBE1FA]">
                @forelse($bookItem->peminjamans as $pinjam)
                    <tr class="hover:bg-[#F4F9FD] transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-[#1B262C]">{{ $pinjam->user->nama_lengkap }}</div>
                            <div class="text-xs text-[#0F4C75] opacity-70">{{ $pinjam->user->email }}</div>
                        </td>
                        {{-- 🟩 Aman dari crash --}}
                        <td class="px-6 py-4 text-sm font-semibold text-[#0F4C75]">
                            {{ $pinjam->borrowed_at ? $pinjam->borrowed_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-[#0F4C75]">
                            {{ $pinjam->due_at ? $pinjam->due_at->format('d M Y') : '-' }}
                        </td>
                            <td class="px-6 py-4">
                            @if($pinjam->status === 'Dipinjam')
                                <span class="text-xs font-bold text-yellow-600 bg-yellow-50 border border-yellow-200 px-2 py-1 rounded">Sedang Dipinjam</span>
                            @elseif($pinjam->status === 'Dikembalikan')
                                <span class="text-xs font-bold text-green-600 bg-green-50 border border-green-200 px-2 py-1 rounded">Selesai</span>
                            @elseif($pinjam->status === 'Terlambat')
                                <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-200 px-2 py-1 rounded">Terlambat</span>
                            @else
                                <span class="text-xs font-bold text-gray-600 bg-gray-50 border border-gray-200 px-2 py-1 rounded">{{ $pinjam->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-[#0F4C75] opacity-50 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <p class="font-semibold italic">Belum ada riwayat peminjaman untuk eksemplar ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
