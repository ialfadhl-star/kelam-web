<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a href="{{ route('home') }}" class="brand">KE<span>L</span>AM</a>
                <p>{{ $globalSettings['store_tagline'] }} Streetwear premium untuk yang jalan sendiri — potongan terukur, bahan berat, kurasi terbatas.</p>
                <div class="pay-methods">
                    <span class="pay-chip">Transfer Bank</span>
                    <span class="pay-chip">QRIS</span>
                    <span class="pay-chip">COD</span>
                </div>
            </div>

            <div>
                <h4 class="head">Belanja</h4>
                <ul>
                    <li><a href="{{ route('catalog.index') }}">Semua Produk</a></li>
                    @foreach(\App\Models\Product::$categories as $cat)
                        <li><a href="{{ route('catalog.index', ['kategori' => $cat]) }}">{{ $cat }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="head">Bantuan</h4>
                <ul>
                    <li><a href="{{ route('contact') }}">Kontak</a></li>
                    <li><a href="{{ route('contact') }}#faq">FAQ</a></li>
                    <li><a href="{{ route('contact') }}#retur">Pengembalian</a></li>
                    <li><a href="{{ route('about') }}">Tentang KELAM</a></li>
                </ul>
            </div>

            <div>
                <h4 class="head">Terhubung</h4>
                <ul>
                    <li><a href="https://instagram.com/{{ ltrim($globalSettings['instagram'], '@') }}" target="_blank" rel="noopener">Instagram {{ $globalSettings['instagram'] }}</a></li>
                    <li><a href="mailto:{{ $globalSettings['contact_email'] }}">{{ $globalSettings['contact_email'] }}</a></li>
                    @if(!empty($globalSettings['contact_phone']))
                        <li><a href="tel:{{ $globalSettings['contact_phone'] }}">{{ $globalSettings['contact_phone'] }}</a></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $globalSettings['store_name'] }}. Semua hak dilindungi.</span>
            <span>Dibuat di Indonesia.</span>
        </div>
    </div>
</footer>
