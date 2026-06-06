@extends('layouts.app')

@section('title', 'Manajemen Akun — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-[#BBE1FA] pb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="font-montserrat text-2xl font-bold text-[#1B262C]">
                Manajemen Akun
            </h1>
            <p class="text-sm font-opensans text-[#0F4C75] opacity-80 mt-1">
                Kelola akun admin, pustakawan, dan anggota Smart Library.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center px-4 py-2 bg-[#3282B8] hover:bg-[#1B262C] text-white font-montserrat text-xs font-bold rounded-lg transition-colors shadow-[0_4px_10px_rgba(50,130,184,0.2)]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Akun
        </a>
    </div>

    {{-- FILTER --}}
    <form action="{{ route('users.index') }}"
          method="GET"
          class="mb-8 bg-white p-5 rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] flex flex-col md:flex-row flex-wrap items-center w-full">

        {{-- Search --}}
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
                   placeholder="Cari nama lengkap atau email..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors outline-none bg-[#F4F9FD] focus:bg-white">
        </div>

        {{-- Filter Role --}}
        <div class="w-full md:w-48 mb-4 md:mb-0 md:mr-4">
            <select name="role"
                    class="w-full px-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors cursor-pointer bg-[#F4F9FD] focus:bg-white outline-none">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
                <option value="pustakawan" {{ request('role') === 'pustakawan' ? 'selected' : '' }}>
                    Pustakawan
                </option>
                <option value="anggota" {{ request('role') === 'anggota' ? 'selected' : '' }}>
                    Anggota
                </option>
            </select>
        </div>

        {{-- Filter Status --}}
        <div class="w-full md:w-48 mb-4 md:mb-0 md:mr-4">
            <select name="tampilkan"
                    class="w-full px-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors cursor-pointer bg-[#F4F9FD] focus:bg-white outline-none">
                <option value="aktif" {{ request('tampilkan', 'aktif') === 'aktif' ? 'selected' : '' }}>
                    Hanya Aktif
                </option>
                <option value="nonaktif" {{ request('tampilkan') === 'nonaktif' ? 'selected' : '' }}>
                    Hanya Nonaktif
                </option>
                <option value="semua" {{ request('tampilkan') === 'semua' ? 'selected' : '' }}>
                    Semua Akun
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
                            Akun
                        </th>
                        <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-5 py-4 font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider">
                            Kontak
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
                    @forelse ($users as $user)
                        <tr class="hover:bg-[#F4F9FD] transition-colors {{ $user->trashed() ? 'opacity-70 bg-red-50/40' : '' }}">

                            {{-- Akun --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center">
                                    <div class="w-11 h-11 rounded-full bg-[#BBE1FA]/50 border border-[#BBE1FA] flex items-center justify-center overflow-hidden mr-3 flex-shrink-0">
                                        @if ($user->foto_profil)
                                            <img src="{{ asset('storage/' . $user->foto_profil) }}"
                                                 alt="Foto {{ $user->nama_lengkap }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <span class="font-montserrat font-bold text-[#3282B8] text-sm">
                                                {{ strtoupper(substr($user->nama_lengkap ?? $user->email, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="font-montserrat font-bold text-sm text-[#1B262C]">
                                            {{ $user->nama_lengkap }}
                                        </p>
                                        <p class="font-opensans text-xs text-[#0F4C75] opacity-70">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-5 py-4">
                                @if ($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[#1B262C] text-white text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Admin
                                    </span>
                                @elseif ($user->role === 'pustakawan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[#3282B8]/10 text-[#3282B8] text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Pustakawan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[#BBE1FA]/50 text-[#0F4C75] text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Anggota
                                    </span>
                                @endif
                            </td>

                            {{-- Kontak --}}
                            <td class="px-5 py-4">
                                <p class="font-opensans text-sm font-semibold text-[#0F4C75]">
                                    {{ $user->no_hp ?? '-' }}
                                </p>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                @if ($user->trashed())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-100 text-red-700 text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Nonaktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-green-100 text-green-700 text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                        Aktif
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex justify-end items-center gap-2">

                                    <a href="{{ route('users.show', $user->id) }}"
                                       class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-[#F4F9FD] text-[#3282B8] hover:bg-[#3282B8] hover:text-white font-montserrat text-[10px] font-bold uppercase tracking-wider transition-colors">
                                        Detail
                                    </a>

                                    @if (! $user->trashed())
                                        <a href="{{ route('users.edit', $user->id) }}"
                                           class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-yellow-50 text-yellow-700 hover:bg-yellow-500 hover:text-white font-montserrat text-[10px] font-bold uppercase tracking-wider transition-colors">
                                            Edit
                                        </a>

                                        <form action="{{ route('users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menonaktifkan akun ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-red-50 text-red-700 hover:bg-red-600 hover:text-white font-montserrat text-[10px] font-bold uppercase tracking-wider transition-colors">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.restore', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Pulihkan akun ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-green-50 text-green-700 hover:bg-green-600 hover:text-white font-montserrat text-[10px] font-bold uppercase tracking-wider transition-colors">
                                                Pulihkan
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="py-16 flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-[#F4F9FD] rounded-full flex items-center justify-center text-[#0F4C75] opacity-50 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z">
                                            </path>
                                        </svg>
                                    </div>

                                    <p class="font-montserrat font-bold text-lg text-[#1B262C] mb-1">
                                        Akun Tidak Ditemukan
                                    </p>
                                    <p class="font-opensans text-sm font-semibold text-[#0F4C75] opacity-70 text-center max-w-md">
                                        Coba gunakan kata kunci lain atau sesuaikan filter role dan status akun.
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
        {{ $users->links() }}
    </div>

</div>
@endsection