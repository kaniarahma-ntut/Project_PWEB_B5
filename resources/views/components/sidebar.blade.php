<aside class="w-64 bg-dark shadow-xl hidden md:flex flex-col border-r border-dark h-screen sticky top-0">

    <div class="p-6 flex items-center gap-4">
        <img src="{{ asset('logo-full.png') }}" alt="Smart Library Logo" class="w-full max-w-[150px]">
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

        @php
            // Menentukan rute dashboard berdasarkan role
            $dashboardRoute = route('dashboard');
            if(auth()->user()->isAdmin()) {
                $dashboardRoute = route('admin.dashboard');
            } elseif(auth()->user()->isPustakawan()) {
                $dashboardRoute = route('pustakawan.dashboard');
            }
        @endphp
        <a href="{{ $dashboardRoute }}"
           class="flex items-center gap-3 p-3 rounded-md font-semibold transition-all {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'bg-primary text-base shadow-md' : 'text-light hover:bg-secondary/40 hover:text-base' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>

        <a href="{{ route('books.index') }}"
           class="flex items-center gap-3 p-3 rounded-md font-semibold transition-all {{ request()->routeIs('books.*') || request()->routeIs('book-items.*') ? 'bg-primary text-base shadow-md' : 'text-light hover:bg-secondary/40 hover:text-base' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Buku
        </a>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 p-3 rounded-md font-semibold transition-all {{ request()->routeIs('users.*') ? 'bg-primary text-base shadow-md' : 'text-light hover:bg-secondary/40 hover:text-base' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Akun
            </a>
        @endif

        <a href="{{ route('peminjamans.index') }}"
           class="flex items-center gap-3 p-3 rounded-md font-semibold transition-all {{ request()->routeIs('peminjamans.*') ? 'bg-primary text-base shadow-md' : 'text-light hover:bg-secondary/40 hover:text-base' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75"></path></svg>
            Peminjaman
        </a>

        <a href="#"
           class="flex items-center gap-3 p-3 rounded-md font-semibold transition-all text-light hover:bg-secondary/40 hover:text-base">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"></path></svg>
            Denda
        </a>
    </nav>

    <div class="p-4 border-t border-secondary/20">
        <div class="flex flex-col gap-3">

            <div class="flex items-center gap-3">
                <img class="w-10 h-10 rounded-full object-cover border-2 border-secondary/50"
                     src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama_lengkap) }}&background=0F4C75&color=fff&bold=true"
                     alt="{{ auth()->user()->nama_lengkap }}">

                <div class="overflow-hidden flex-1">
                    <p class="text-sm font-semibold text-base truncate">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-xs text-light uppercase tracking-wider">{{ auth()->user()->role }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="w-full m-0">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center px-3 py-2 rounded-md text-sm font-semibold text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M18 12H9m0 0l3-3m-3 3l3 3" />
                    </svg>

                    <span class="ml-3">Logout Akun</span>
                </button>
            </form>
        </div>
    </div>
</aside>
