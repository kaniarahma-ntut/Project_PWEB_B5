@extends('layouts.app')

@section('title', 'Manajemen Peminjaman — Smart Library')

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
                Manajemen Peminjaman
            </h1>

            <p class="text-sm text-[#0F4C75] opacity-80 mt-1">
                @if (auth()->user()->isAnggota())
                    Lihat riwayat peminjaman dan ajukan pengembalian buku.
                @else
                    Kelola data peminjaman dan validasi pengembalian buku.
                @endif
            </p>
        </div>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="mb-6 px-4 py-3 rounded-xl bg-green-100 text-green-700 font-semibold border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 px-4 py-3 rounded-xl bg-red-100 text-red-700 font-semibold border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- FILTER --}}
    <form action="{{ route('peminjamans.index') }}"
          method="GET"
          class="mb-8 bg-white p-5 rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] flex flex-col md:flex-row flex-wrap items-center w-full">

        <div class="flex-1 w-full relative mb-4 md:mb-0 md:mr-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-[#0F4C75] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                    </path>
                </svg>
            </div>

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama peminjam, email, judul buku, atau kode buku..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors outline-none bg-[#F4F9FD] focus:bg-white">
        </div>

        <div class="w-full md:w-56 mb-4 md:mb-0 md:mr-4">
            <select name="status_peminjaman"
                    class="w-full px-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors cursor-pointer bg-[#F4F9FD] focus:bg-white outline-none">
                <option value="">Semua Status</option>
                <option value="dipinjam" {{ request('status_peminjaman') === 'dipinjam' ? 'selected' : '' }}>
                    Dipinjam
                </option>
                <option value="validasi pengembalian" {{ request('status_peminjaman') === 'validasi pengembalian' ? 'selected' : '' }}>
                    Validasi Pengembalian
                </option>
                <option value="dikembalikan" {{ request('status_peminjaman') === 'dikembalikan' ? 'selected' : '' }}>
                    Dikembalikan
                </option>
            </select>
        </div>

        <button type="submit"
                class="w-full md:w-auto px-6 py-2.5 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat text-xs font-bold rounded-lg transition-colors shadow-md">
            Cari & Filter
        </button>
    </form>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F4F9FD] border-b border-[#BBE1FA]">
                        <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider">
                            Buku
                        </th>

                        @if (! auth()->user()->isAnggota())
                            <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider">
                                Peminjam
                            </th>
                        @endif

                        <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider">
                            Tanggal Pinjam
                        </th>

                        <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider">
                            Tenggat
                        </th>

                        <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider">
                            Status
                        </th>

                        <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#BBE1FA]/70">
                    @forelse ($peminjamans as $peminjaman)
                        <tr class="hover:bg-[#F4F9FD] transition-colors">

                            {{-- BUKU --}}
                            <td class="px-5 py-4">
                                <p class="font-montserrat font-bold text-sm text-[#1B262C]">
                                    {{ $peminjaman->bookItem->book->judul ?? '-' }}
                                </p>

                                <p class="text-xs text-[#0F4C75] opacity-70 mt-1">
                                    Kode: {{ $peminjaman->bookItem->kode_buku ?? '-' }}
                                </p>
                            </td>

                            {{-- PEMINJAM --}}
                            @if (! auth()->user()->isAnggota())
                                <td class="px-5 py-4">
                                    <p class="font-opensans font-bold text-sm text-[#1B262C]">
                                        {{ $peminjaman->user->nama_lengkap ?? '-' }}
                                    </p>

                                    <p class="text-xs text-[#0F4C75] opacity-70 mt-1">
                                        {{ $peminjaman->user->email ?? '-' }}
                                    </p>
                                </td>
                            @endif

                            {{-- TANGGAL PINJAM --}}
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold text-[#0F4C75]">
                                    {{ $peminjaman->created_at ? $peminjaman->created_at->format('d M Y') : '-' }}
                                </p>
                            </td>

                            {{-- TENGGAT --}}
                            <td class="px-5 py-4">
                                <p class="text-sm font-semibold {{ $peminjaman->isTerlambat() ? 'text-red-700' : 'text-[#0F4C75]' }}">
                                    {{ $peminjaman->due_at ? $peminjaman->due_at->format('d M Y') : '-' }}
                                </p>

                                @if ($peminjaman->isTerlambat())
                                    <p class="text-[10px] font-bold text-red-700 uppercase tracking-wider mt-1">
                                        Terlambat
                                    </p>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4">
                                @if ($peminjaman->status_peminjaman === 'dipinjam')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-yellow-100 text-yellow-700 text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Dipinjam
                                    </span>
                                @elseif ($peminjaman->status_peminjaman === 'validasi pengembalian')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-100 text-blue-700 text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Validasi Pengembalian
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-green-100 text-green-700 text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Dikembalikan
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4">
                                <div class="flex justify-end items-center gap-2">

                                    <a href="{{ route('peminjamans.show', $peminjaman->id) }}"
                                       class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-[#F4F9FD] text-[#3282B8] hover:bg-[#3282B8] hover:text-white font-montserrat text-[10px] font-bold uppercase tracking-wider transition-colors">
                                        Detail
                                    </a>

                                    @if (auth()->user()->isAnggota() && $peminjaman->isDipinjam())
                                        <form action="{{ route('peminjamans.request-return', $peminjaman->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Ajukan pengembalian buku ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white font-montserrat text-[10px] font-bold uppercase tracking-wider transition-colors">
                                                Ajukan
                                            </button>
                                        </form>
                                    @endif

                                    @if ((auth()->user()->isAdmin() || auth()->user()->isPustakawan()) && $peminjaman->isValidasiPengembalian())
                                        <form action="{{ route('peminjamans.approve-return', $peminjaman->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Validasi pengembalian buku ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white font-montserrat text-[10px] font-bold uppercase tracking-wider transition-colors">
                                                Validasi
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAnggota() ? 5 : 6 }}">
                                <div class="py-16 flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-[#F4F9FD] rounded-full flex items-center justify-center text-[#0F4C75] opacity-50 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>

                                    <p class="font-montserrat font-bold text-lg text-[#1B262C] mb-1">
                                        Data Peminjaman Tidak Ditemukan
                                    </p>
                                    <p class="font-opensans text-sm font-semibold text-[#0F4C75] opacity-70 text-center max-w-md">
                                        Belum ada data peminjaman yang sesuai dengan pencarian atau filter.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-8">
        {{ $peminjamans->links() }}
    </div>

</div>
@endsection
