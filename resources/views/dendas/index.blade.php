@extends('layouts.app')

@section('title', 'Daftar Denda — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-[#BBE1FA] pb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="font-montserrat text-2xl font-bold text-[#1B262C]">Daftar Denda</h1>
            <p class="text-sm font-opensans text-[#0F4C75] opacity-80 mt-1">
                @if(auth()->user()->isAnggota())
                    Denda keterlambatan pengembalian buku Anda.
                @else
                    Kelola denda keterlambatan pengembalian buku.
                @endif
            </p>
        </div>
        <div class="flex gap-2 items-center">
            @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
                <a href="{{ route('exports.dendas') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-montserrat text-xs font-bold rounded-lg transition-colors shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
            @endif
            <div class="text-sm font-montserrat font-bold text-red-600 bg-red-50 px-4 py-2 rounded-lg border border-red-200">
                {{ $dendas->total() }} Denda
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <form action="{{ route('dendas.index') }}" method="GET" class="mb-8 bg-white p-5 rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] flex flex-col md:flex-row flex-wrap items-center w-full">

        <div class="flex-1 w-full relative mb-4 md:mb-0 md:mr-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-[#0F4C75] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota atau judul buku..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors outline-none bg-[#F4F9FD] focus:bg-white">
        </div>

        <div class="w-full md:w-56 mb-4 md:mb-0 md:mr-4">
            <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors cursor-pointer bg-[#F4F9FD] focus:bg-white outline-none">
                <option value="">Semua Status</option>
                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="sudah" {{ request('status') == 'sudah' ? 'selected' : '' }}>Sudah Bayar</option>
            </select>
        </div>

        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat text-xs font-bold rounded-lg transition-colors shadow-md">
            Cari & Filter
        </button>
    </form>

    {{-- Tabel Denda --}}
    <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
        @if($dendas->isEmpty())
            <div class="p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-[#BBE1FA] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="font-montserrat text-lg font-bold text-[#1B262C] mb-2">Tidak Ada Denda</h3>
                <p class="text-sm font-opensans text-[#0F4C75] opacity-70">
                    @if(auth()->user()->isAnggota())
                        Anda tidak memiliki denda. Terus kembalikan buku tepat waktu!
                    @else
                        Belum ada denda yang tercatat dalam sistem.
                    @endif
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-[#0F4C75] text-[10px] uppercase font-bold tracking-wider">
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Keterlambatan</th>
                            <th class="px-6 py-4">Jumlah Denda</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#BBE1FA]">
                        @foreach($dendas as $denda)
                            <tr class="hover:bg-[#F4F9FD] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#1B262C]">
                                        {{ $denda->peminjaman->user->nama_lengkap }}
                                    </div>
                                    <div class="text-xs text-[#0F4C75] opacity-70">
                                        {{ $denda->peminjaman->user->email }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-[#0F4C75]">
                                        {{ $denda->peminjaman->bookItem->book->judul }}
                                    </div>
                                    <div class="text-xs text-[#0F4C75] opacity-70">
                                        {{ $denda->peminjaman->bookItem->kode_buku }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-red-600">
                                        {{ $denda->peminjaman->getHariTerlambat() }} Hari
                                    </div>
                                    <div class="text-xs text-[#0F4C75] opacity-70">
                                        Jatuh tempo: {{ $denda->peminjaman->due_at->format('d M Y') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-bold text-[#1B262C] text-lg">
                                        {{ $denda->jumlah_denda_formatted }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($denda->isBelum())
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-red-100 text-red-700">
                                            Belum Bayar
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-700">
                                            Sudah Bayar
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('dendas.show', $denda) }}" class="px-3 py-1.5 bg-[#3282B8] hover:bg-[#1B262C] text-white text-xs font-bold rounded transition">
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-[#BBE1FA]">
                {{ $dendas->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
