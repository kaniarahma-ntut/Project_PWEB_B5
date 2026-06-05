@extends('layouts.app')

@section('title', 'Edit Akun — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full max-w-4xl mx-auto">

    {{-- KEMBALI --}}
    <div class="mb-6">
        <a href="{{ route('users.show', $user->id) }}"
           class="inline-flex items-center text-sm font-bold text-[#0F4C75] hover:text-[#3282B8] transition-colors group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali ke Detail Akun
        </a>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] p-6 md:p-10">

        <div class="mb-8 border-b border-[#BBE1FA] pb-5">
            <h2 class="font-montserrat text-2xl font-bold text-[#1B262C]">
                Edit Akun
            </h2>
            <p class="text-sm text-[#0F4C75] opacity-80 mt-1">
                Perbarui informasi akun pengguna Smart Library.
            </p>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                {{-- ROLE --}}
                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Role <span class="text-[#D32F2F]">*</span>
                    </label>

                    @if (auth()->user()->isAdmin())
                        <select name="role" required
                                class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none">
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                Admin
                            </option>
                            <option value="pustakawan" {{ old('role', $user->role) === 'pustakawan' ? 'selected' : '' }}>
                                Pustakawan
                            </option>
                            <option value="anggota" {{ old('role', $user->role) === 'anggota' ? 'selected' : '' }}>
                                Anggota
                            </option>
                        </select>
                    @else
                        <input type="hidden" name="role" value="{{ $user->role }}">

                        <div class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] text-[#0F4C75] font-semibold text-sm capitalize">
                            {{ $user->role }}
                        </div>
                    @endif

                    @error('role')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- NAMA --}}
                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Nama Lengkap <span class="text-[#D32F2F]">*</span>
                    </label>

                    <input type="text"
                           name="nama_lengkap"
                           value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none">

                    @error('nama_lengkap')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Email <span class="text-[#D32F2F]">*</span>
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none">

                    @error('email')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Password Baru
                    </label>

                    <input type="password"
                           name="password"
                           placeholder="Kosongkan jika tidak ingin mengubah password"
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40">

                    @error('password')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- NO HP --}}
                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Nomor HP
                    </label>

                    <input type="text"
                           name="no_hp"
                           value="{{ old('no_hp', $user->no_hp) }}"
                           placeholder="Contoh: 081234567890"
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40">

                    @error('no_hp')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- KECAMATAN --}}
                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Kecamatan
                    </label>

                    <input type="text"
                           name="kecamatan"
                           value="{{ old('kecamatan', $user->kecamatan) }}"
                           placeholder="Contoh: Tenggarang"
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40">

                    @error('kecamatan')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- FOTO PROFIL SAAT INI --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Foto Profil
                    </label>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-4">
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

                        <div class="flex-1">
                            <input type="file"
                                   name="foto_profil"
                                   accept="image/*"
                                   class="w-full text-sm font-semibold text-[#0F4C75] file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-montserrat file:font-bold file:bg-[#BBE1FA] file:text-[#0F4C75] hover:file:bg-[#3282B8] hover:file:text-white file:transition-colors file:cursor-pointer cursor-pointer bg-[#F4F9FD] border border-[#BBE1FA] rounded-xl pr-3">

                            <p class="text-[10px] font-bold text-[#0F4C75] opacity-60 mt-2 uppercase tracking-wide">
                                Kosongkan jika tidak ingin mengganti foto. Maksimal 2MB.
                            </p>
                        </div>
                    </div>

                    @error('foto_profil')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ALAMAT --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">
                        Alamat
                    </label>

                    <textarea name="alamat"
                              rows="4"
                              placeholder="Masukkan alamat lengkap pengguna..."
                              class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40">{{ old('alamat', $user->alamat) }}</textarea>

                    @error('alamat')
                        <span class="text-[#D32F2F] text-xs font-bold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-10 pt-6 border-t border-[#BBE1FA]">
                <a href="{{ route('users.show', $user->id) }}"
                   class="py-2.5 px-6 bg-[#F4F9FD] text-[#0F4C75] font-montserrat text-xs font-bold uppercase tracking-wider rounded-lg border border-[#BBE1FA] hover:bg-[#BBE1FA]/50 transition-colors text-center">
                    Batal
                </a>

                <button type="submit"
                        class="py-2.5 px-8 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat text-xs font-bold uppercase tracking-wider rounded-lg transition-colors shadow-[0_4px_10px_rgba(27,38,44,0.2)]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection