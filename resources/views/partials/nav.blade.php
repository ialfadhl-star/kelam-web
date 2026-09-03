<nav class="nav">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="brand">KE<span>L</span>AM</a>

        <div class="nav-links">
            <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*') ? 'active' : '' }}">Katalog</a>
            @foreach(array_slice(\App\Models\Product::$categories, 0, 3) as $cat)
                <a href="{{ route('catalog.index', ['kategori' => $cat]) }}">{{ $cat }}</a>
            @endforeach
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
        </div>

        <div class="nav-actions">
            <a href="{{ route('cart.index') }}" class="cart-btn" aria-label="Keranjang">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <span class="cart-count {{ $globalCartCount > 0 ? '' : 'hidden' }}" data-cart-count>{{ $globalCartCount }}</span>
            </a>
            <button class="hamburger" data-menu-open aria-label="Buka menu">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
            </button>
        </div>
    </div>
</nav>
