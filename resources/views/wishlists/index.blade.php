@extends('layouts.app')

@section('title', 'Wishlist Saya — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-[#BBE1FA] pb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="font-montserrat text-2xl font-bold text-[#1B262C]">Wishlist Saya</h1>
            <p class="text-sm font-opensans text-[#0F4C75] opacity-80 mt-1">
                Daftar buku yang ingin Anda pinjam di masa mendatang.
            </p>
        </div>
        <div class="text-sm font-montserrat font-bold text-[#3282B8] bg-[#3282B8]/10 px-4 py-2 rounded-lg">
            {{ $wishlists->total() }} Buku
        </div>
    </div>

    @if($wishlists->isEmpty())
        <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-[#BBE1FA] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h3 class="font-montserrat text-lg font-bold text-[#1B262C] mb-2">Wishlist Masih Kosong</h3>
            <p class="text-sm font-opensans text-[#0F4C75] opacity-70 mb-6">
                Anda belum menambahkan buku ke wishlist. Mulai eksplor koleksi kami!
            </p>
            <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 bg-[#3282B8] hover:bg-[#1B262C] text-white font-montserrat text-sm font-bold rounded-lg transition-colors shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Jelajahi Buku
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($wishlists as $wishlist)
                @php
                    $book = $wishlist->book;
                @endphp
                <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)] hover:border-[#3282B8] transition-all overflow-hidden flex flex-col group">

                    <div class="h-64 bg-[#F4F9FD] flex items-center justify-center overflow-hidden relative border-b border-[#BBE1FA]/50">
                        @if($book->cover)
                            <img src="{{ Storage::url($book->cover) }}" alt="Cover {{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="flex flex-col items-center justify-center text-[#0F4C75] opacity-40">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span class="font-montserrat font-bold text-[10px] uppercase tracking-widest">No Cover</span>
                            </div>
                        @endif

                        <!-- Heart Icon -->
                        <div class="absolute top-3 right-3 bg-red-500 text-white p-2 rounded-full shadow-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[#3282B8]/10 text-[#3282B8] text-[10px] font-montserrat font-bold uppercase tracking-wider">
                                {{ $book->kategori }}
                            </span>
                        </div>
                        <h2 class="text-base font-montserrat font-bold text-[#1B262C] mb-1 line-clamp-2 leading-snug group-hover:text-[#3282B8] transition-colors" title="{{ $book->judul }}">
                            {{ $book->judul }}
                        </h2>
                        <p class="text-xs font-opensans font-semibold text-[#0F4C75] opacity-70 mb-4 line-clamp-1">
                            {{ $book->penulis }}
                        </p>

                        <div class="mt-auto pt-4 border-t border-[#BBE1FA]/50 flex items-center justify-between">
                            <div class="text-xs font-opensans">
                                @if($book->jumlah_tersedia > 0)
                                    <span class="inline-flex items-center text-green-600 font-semibold">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                        {{ $book->jumlah_tersedia }} Tersedia
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-red-600 font-semibold">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                        Tidak Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('books.show', $book) }}" class="flex-1 px-4 py-2 bg-[#3282B8] hover:bg-[#1B262C] text-white text-center font-montserrat text-xs font-bold rounded-lg transition-colors">
                                Detail
                            </a>
                            <form action="{{ route('wishlists.destroy', $wishlist) }}" method="POST" onsubmit="return confirm('Hapus buku dari wishlist?')" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-montserrat text-xs font-bold rounded-lg transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $wishlists->links() }}
        </div>
    @endif

</div>
@endsection
