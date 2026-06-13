@extends('layouts.app')

@section('title', 'Detail Denda — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full">

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('dendas.index') }}" class="text-[#0F4C75] hover:text-[#3282B8] font-semibold flex items-center gap-2">
            &larr; Kembali ke Daftar Denda
        </a>

        @if($denda->isBelum())
            @if(auth()->user()->isAnggota())
                <form action="{{ route('dendas.pay', $denda) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded text-sm font-bold transition"
                            onclick="return confirm('Konfirmasi pembayaran denda?')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Bayar Denda
                    </button>
                </form>
            @endif
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Informasi Denda --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
                <div class="p-6 border-b border-[#BBE1FA] bg-[#F4F9FD]/50">
                    <h2 class="text-xl font-montserrat font-bold text-[#1B262C]">Detail Denda</h2>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Status Pembayaran --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#0F4C75] mb-2">Status Pembayaran</label>
                        @if($denda->isBelum())
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold uppercase bg-red-100 text-red-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Belum Dibayar
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold uppercase bg-green-100 text-green-700">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Sudah Dibayar
                            </span>
                        @endif
                    </div>

                    {{-- Jumlah Denda --}}
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <label class="block text-sm font-semibold text-red-700 mb-2">Total Denda</label>
                        <div class="text-4xl font-montserrat font-bold text-red-600">
                            {{ $denda->jumlah_denda_formatted }}
                        </div>
                        <div class="text-sm text-red-600 mt-2">
                            {{ $denda->peminjaman->getHariTerlambat() }} hari × Rp 1.000/hari
                        </div>
                    </div>

                    {{-- Info Pembayaran --}}
                    @if($denda->isSudah())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-[#F4F9FD] p-4 rounded-lg border border-[#BBE1FA]">
                                <label class="block text-xs font-semibold text-[#0F4C75] mb-1">Tanggal Bayar</label>
                                <div class="font-bold text-[#1B262C]">
                                    {{ $denda->paid_at ? $denda->paid_at->format('d M Y H:i') : '-' }}
                                </div>
                            </div>
                            <div class="bg-[#F4F9FD] p-4 rounded-lg border border-[#BBE1FA]">
                                <label class="block text-xs font-semibold text-[#0F4C75] mb-1">ID Pembayaran</label>
                                <div class="font-mono text-sm font-bold text-[#1B262C]">
                                    {{ $denda->id_payment ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Info Peminjaman --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
                <div class="p-6 border-b border-[#BBE1FA] bg-[#F4F9FD]/50">
                    <h3 class="text-lg font-montserrat font-bold text-[#1B262C]">Info Peminjaman</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#0F4C75] mb-1">Peminjam</label>
                        <div class="font-bold text-[#1B262C]">
                            {{ $denda->peminjaman->user->nama_lengkap }}
                        </div>
                        <div class="text-xs text-[#0F4C75] opacity-70">
                            {{ $denda->peminjaman->user->email }}
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#BBE1FA]">
                        <label class="block text-xs font-semibold text-[#0F4C75] mb-1">Buku</label>
                        <div class="font-bold text-[#1B262C]">
                            {{ $denda->peminjaman->bookItem->book->judul }}
                        </div>
                        <div class="text-xs text-[#0F4C75] opacity-70">
                            Kode: {{ $denda->peminjaman->bookItem->kode_buku }}
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#BBE1FA]">
                        <label class="block text-xs font-semibold text-[#0F4C75] mb-1">Jatuh Tempo</label>
                        <div class="font-bold text-red-600">
                            {{ $denda->peminjaman->due_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#BBE1FA]">
                        <label class="block text-xs font-semibold text-[#0F4C75] mb-1">Tanggal Kembali</label>
                        <div class="font-bold text-[#1B262C]">
                            {{ $denda->peminjaman->returned_at ? $denda->peminjaman->returned_at->format('d M Y') : 'Belum dikembalikan' }}
                        </div>
                    </div>

                    <div class="pt-4 border-t border-[#BBE1FA]">
                        <label class="block text-xs font-semibold text-[#0F4C75] mb-1">Keterlambatan</label>
                        <div class="font-bold text-red-600 text-lg">
                            {{ $denda->peminjaman->getHariTerlambat() }} Hari
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('peminjamans.show', $denda->peminjaman) }}" class="block w-full px-4 py-2 bg-[#3282B8] hover:bg-[#1B262C] text-white text-center font-montserrat text-xs font-bold rounded-lg transition-colors">
                            Lihat Detail Peminjaman
                        </a>
                    </div>
                </div>
            </div>

            {{-- Admin/Pustakawan Actions --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
                <div class="mt-6 bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
                    <div class="p-6 border-b border-[#BBE1FA] bg-[#F4F9FD]/50">
                        <h3 class="text-lg font-montserrat font-bold text-[#1B262C]">Aksi Admin</h3>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('dendas.updateStatus', $denda) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-xs font-semibold text-[#0F4C75] mb-2">Status Pembayaran</label>
                                <select name="status_pembayaran" class="w-full px-4 py-2 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] text-[#0F4C75] font-semibold text-sm">
                                    <option value="belum" {{ $denda->isBelum() ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="sudah" {{ $denda->isSudah() ? 'selected' : '' }}>Sudah Bayar</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#0F4C75] mb-2">ID Pembayaran (Optional)</label>
                                <input type="text" name="id_payment" value="{{ $denda->id_payment }}" class="w-full px-4 py-2 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] text-[#0F4C75] font-semibold text-sm" placeholder="MANUAL-xxxxx">
                            </div>

                            <button type="submit" class="w-full px-4 py-2 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat text-xs font-bold rounded-lg transition-colors">
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
