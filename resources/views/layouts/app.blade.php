<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $globalSettings['store_name'] . ' — ' . $globalSettings['store_tagline'])</title>
    <meta name="description" content="@yield('meta_description', 'KELAM — streetwear premium. Kelam. Bukan untuk semua.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/kelam.css') }}">
</head>
<body>
    @if(!empty($globalSettings['announcement']))
        <div class="announce">{{ $globalSettings['announcement'] }}</div>
    @endif

    @include('partials.nav')

    <div class="wrap-main">
        @yield('content')
    </div>

    @include('partials.footer')

    {{-- Mobile menu --}}
    <div class="mobile-menu" id="mobile-menu">
        <div class="mobile-menu-head">
            <a href="{{ route('home') }}" class="brand">KE<span>L</span>AM</a>
            <button data-menu-close aria-label="Tutup menu">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <nav>
            <a href="{{ route('home') }}" data-menu-close>Beranda</a>
            <a href="{{ route('catalog.index') }}" data-menu-close>Katalog</a>
            @foreach(\App\Models\Product::$categories as $cat)
                <a href="{{ route('catalog.index', ['kategori' => $cat]) }}" data-menu-close>{{ $cat }}</a>
            @endforeach
            <a href="{{ route('about') }}" data-menu-close>Tentang</a>
            <a href="{{ route('contact') }}" data-menu-close>Kontak</a>
        </nav>
    </div>

    <script src="{{ asset('js/kelam.js') }}"></script>
    @stack('scripts')
</body>
</html>
