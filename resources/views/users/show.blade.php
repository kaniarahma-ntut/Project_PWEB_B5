@extends('layouts.app')

@section('title', 'Detail Akun — Smart Library')

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
                Detail Akun
            </h1>
            <p class="text-sm text-[#0F4C75] opacity-80 mt-1">
                Informasi lengkap akun pengguna Smart Library.
            </p>
        </div>

        <div class="flex gap-3 mt-4 md:mt-0">
            @if (auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}"
                   class="px-4 py-2 bg-[#F4F9FD] hover:bg-[#BBE1FA] text-[#0F4C75] font-montserrat text-xs font-bold rounded-lg border border-[#BBE1FA] transition">
                    Kembali
                </a>
            @else
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 bg-[#F4F9FD] hover:bg-[#BBE1FA] text-[#0F4C75] font-montserrat text-xs font-bold rounded-lg border border-[#BBE1FA] transition">
                    Kembali
                </a>
            @endif

            @if (! $user->trashed())
                <a href="{{ route('users.edit', $user->id) }}"
                   class="px-4 py-2 bg-[#3282B8] hover:bg-[#1B262C] text-white font-montserrat text-xs font-bold rounded-lg transition">
                    Edit Akun
                </a>
            @endif
        </div>
    </div>

    {{-- CARD DETAIL --}}
    <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">

        {{-- PROFIL --}}
        <div class="p-6 sm:p-8 bg-[#F4F9FD] border-b border-[#BBE1FA]">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">

                <div class="w-20 h-20 rounded-2xl bg-[#BBE1FA]/60 border border-[#BBE1FA] flex items-center justify-center overflow-hidden">
                    @if ($user->foto_profil)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}"
                             alt="Foto {{ $user->nama_lengkap }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="font-montserrat font-bold text-[#3282B8] text-2xl">
                            {{ strtoupper(substr($user->nama_lengkap ?? $user->email, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div>
                    <h2 class="font-montserrat text-xl font-bold text-[#1B262C]">
                        {{ $user->nama_lengkap }}
                    </h2>

                    <p class="text-sm text-[#0F4C75] opacity-80 mt-1">
                        {{ $user->email }}
                    </p>

                    <div class="flex gap-2 mt-3">
                        <span class="px-3 py-1 rounded-md bg-[#3282B8]/10 text-[#3282B8] text-[10px] font-montserrat font-bold uppercase tracking-wider">
                            {{ $user->role }}
                        </span>

                        @if ($user->trashed())
                            <span class="px-3 py-1 rounded-md bg-red-100 text-red-700 text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                Nonaktif
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-md bg-green-100 text-green-700 text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                Aktif
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- INFORMASI AKUN --}}
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Nama Lengkap
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $user->nama_lengkap ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Email
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $user->email ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Role
                </p>
                <p class="text-sm font-semibold text-[#0F4C75] capitalize">
                    {{ $user->role ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Nomor HP
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $user->no_hp ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Kecamatan
                </p>
                <p class="text-sm font-semibold text-[#0F4C75]">
                    {{ $user->kecamatan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Status Akun
                </p>

                @if ($user->trashed())
                    <p class="text-sm font-semibold text-red-700">
                        Nonaktif
                    </p>
                @else
                    <p class="text-sm font-semibold text-green-700">
                        Aktif
                    </p>
                @endif
            </div>

            <div class="md:col-span-2">
                <p class="font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                    Alamat
                </p>
                <p class="text-sm font-semibold text-[#0F4C75] leading-relaxed">
                    {{ $user->alamat ?? '-' }}
                </p>
            </div>

        </div>
    </div>
</div>
@endsection