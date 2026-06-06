@extends('layouts.app')

@section('title', 'Detail Peminjaman — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-[#BBE1FA] pb-6">
        <div>
            <h1 class="font-montserrat text-2xl font-bold text-[#1B262C]">
                Detail Peminjaman
            </h1>
            <p class="text-sm text-[#0F4C75] opacity-80 mt-1">
                Informasi detail data peminjaman buku.
            </p>
        </div>

        <a href="{{ route('peminjamans.index') }}"
           class="mt-4 md:mt-0 px-4 py-2 bg-[#F4F9FD] hover:bg-[#BBE1FA] text-[#0F4C75] font-montserrat text-xs font-bold rounded-lg border border-[#BBE1FA] transition">
            Kembali
        </a>
    </div>

    {{-- CARD DETAIL --}}
    <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">

        {{-- BAGIAN ATAS --}}
        <div class="p-6 sm:p-8 bg-[#F4F9FD] border-b border-[#BBE1FA]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                <div>
                    <h2 class="font-montserrat text-xl font-bold text-[#1B262C]">
                        {{ $peminjaman->bookItem->book->judul ?? 'Judul Buku Tidak Ditemukan' }}
                    </h2>

                    <p class="text-sm text-[#0F4C75] opacity-80 mt-1">
                        Kode Buku: {{ $peminjaman->bookItem->kode_buku ?? '-' }}
                    </p>
                </div>

                <div>
                    @if ($peminjaman->status_peminjaman === 'dipinjam')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-yellow-100 text-yellow-700 text-xs font-montserrat font-bold uppercase tracking-wider">
                            Dipinjam
                        </span>
                    @elseif ($peminjaman->status_peminjaman === 'validasi pengembalian')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-100 text-blue-700 text-xs font-montserrat font-bold uppercase tracking-wider">
                            Validasi Pengembalian
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md bg-green-100 text-green-700 text-xs font-montserrat font-bold uppercase tracking-wider">
                            Dikembalikan
                        </span>
                    @endif
                </div>

            </div>
        </div>

        {{-- INFORMASI DETAIL --}}
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PEMINJAM --}}
            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Nama Peminjam
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $peminjaman->user->nama_lengkap ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Email Peminjam
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $peminjaman->user->email ?? '-' }}
                </p>
            </div>

            {{-- BUKU --}}
            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Judul Buku
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $peminjaman->bookItem->book->judul ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Kode Eksemplar
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $peminjaman->bookItem->kode_buku ?? '-' }}
                </p>
            </div>

            {{-- TANGGAL --}}
            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Tanggal Pinjam
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $peminjaman->created_at ? $peminjaman->created_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Tenggat Pengembalian
                </p>
                <p class="text-sm font-semibold {{ $peminjaman->isTerlambat() ? 'text-red-700' : 'text-[#0F4C75]' }}">
                    {{ $peminjaman->due_at ? $peminjaman->due_at->format('d M Y, H:i') : '-' }}
                </p>

                @if ($peminjaman->isTerlambat())
                    <p class="text-[10px] font-bold text-red-700 uppercase tracking-wider mt-1">
                        Peminjaman terlambat
                    </p>
                @endif
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Waktu Pengajuan Pengembalian
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $peminjaman->requested_return_at ? $peminjaman->requested_return_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Waktu Dikembalikan
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $peminjaman->returned_at ? $peminjaman->returned_at->format('d M Y, H:i') : '-' }}
                </p>
            </div>

        </div>

        {{-- AKSI --}}
        <div class="p-6 sm:p-8 border-t border-[#BBE1FA] bg-[#F4F9FD]">

            <div class="flex flex-col md:flex-row justify-end gap-3">

                {{-- ANGGOTA: AJUKAN PENGEMBALIAN --}}
                @if (auth()->user()->isAnggota() && $peminjaman->isDipinjam())
                    <form action="{{ route('peminjamans.request-return', $peminjaman->id) }}"
                          method="POST"
                          onsubmit="return confirm('Ajukan pengembalian buku ini?')">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="w-full md:w-auto px-4 py-2 bg-blue-100 hover:bg-blue-600 text-blue-700 hover:text-white font-montserrat text-xs font-bold rounded-lg transition-colors">
                            Ajukan Pengembalian
                        </button>
                    </form>
                @endif

                {{-- ADMIN/PUSTAKAWAN: VALIDASI PENGEMBALIAN --}}
                @if ((auth()->user()->isAdmin() || auth()->user()->isPustakawan()) && $peminjaman->isValidasiPengembalian())
                    <form action="{{ route('peminjamans.approve-return', $peminjaman->id) }}"
                          method="POST"
                          onsubmit="return confirm('Validasi pengembalian buku ini?')">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="w-full md:w-auto px-4 py-2 bg-green-100 hover:bg-green-600 text-green-700 hover:text-white font-montserrat text-xs font-bold rounded-lg transition-colors">
                            Validasi Pengembalian
                        </button>
                    </form>
                @endif

                {{-- ADMIN/PUSTAKAWAN: UBAH STATUS MANUAL --}}
                @if (auth()->user()->isAdmin() || auth()->user()->isPustakawan())
                    <form action="{{ route('peminjamans.updateStatus', $peminjaman->id) }}"
                          method="POST"
                          class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        @method('PATCH')

                        <select name="status_peminjaman"
                                class="px-4 py-2 rounded-lg border border-[#BBE1FA] bg-white text-[#0F4C75] font-opensans font-semibold text-sm outline-none">
                            <option value="dipinjam" {{ $peminjaman->status_peminjaman === 'dipinjam' ? 'selected' : '' }}>
                                Dipinjam
                            </option>
                            <option value="validasi pengembalian" {{ $peminjaman->status_peminjaman === 'validasi pengembalian' ? 'selected' : '' }}>
                                Validasi Pengembalian
                            </option>
                            <option value="dikembalikan" {{ $peminjaman->status_peminjaman === 'dikembalikan' ? 'selected' : '' }}>
                                Dikembalikan
                            </option>
                        </select>

                        <button type="submit"
                                class="px-4 py-2 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat text-xs font-bold rounded-lg transition-colors">
                            Ubah Status
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection