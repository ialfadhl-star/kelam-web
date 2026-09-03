@extends('layouts.app')

@section('content')

    {{-- ===== HERO ===== --}}
    <section class="hero">
        @if($hero)
            <div class="hero-bg" style="background: linear-gradient(135deg, {{ $hero->gradient_from }}, {{ $hero->gradient_to }});"></div>
        @else
            <div class="hero-bg" style="background: linear-gradient(135deg, #2B2B2B, #0A0A0A);"></div>
        @endif
        <div class="hero-noise"></div>
        <div class="container hero-content">
            <div class="eyebrow">Drop Terbaru</div>
            <h1>Bukan<br>untuk <span class="rust">semua.</span></h1>
            <p>Streetwear premium dengan potongan terukur dan bahan berat. Kurasi terbatas, bukan katalog tak berujung. Buat yang jalan sendiri.</p>
            <div class="hero-actions">
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Lihat Koleksi</a>
                @if($hero)
                    <a href="{{ route('catalog.show', $hero) }}" class="btn btn-outline">{{ $hero->name }}</a>
                @endif
            </div>
        </div>
    </section>

    {{-- ===== KATEGORI ===== --}}
    <section class="section-sm">
        <div class="container">
            <div class="cat-grid">
                @php
                    $catIcons = [
                        'Hoodie & Sweater' => '<path d="M3 8l4-4 5 3 5-3 4 4-3 3v9H6v-9Z"/>',
                        'Outerwear' => '<path d="M6 3l6 4 6-4 3 5-3 2v11H6V10L3 8Z"/>',
                        'T-Shirt' => '<path d="M4 6l4-3 4 2 4-2 4 3-3 3v11H7V9Z"/>',
                        'Aksesoris' => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/>',
                        'Bottoms' => '<path d="M6 3h12l-1 18h-4l-1-9-1 9H7Z"/>',
                    ];
                @endphp
                @foreach($categories as $cat)
                    <a href="{{ route('catalog.index', ['kategori' => $cat]) }}" class="cat-card">
                        <div class="cat-ico">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linejoin="round">
                                {!! $catIcons[$cat] ?? '<rect x="4" y="4" width="16" height="16"/>' !!}
                            </svg>
                        </div>
                        <div class="cat-name">{{ $cat }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== PRODUK UNGGULAN ===== --}}
    <section class="section">
        <div class="container">
            <div class="section-head">
                <div class="eyebrow">Best Seller</div>
                <h2>Produk Unggulan</h2>
                <p>Yang paling dicari. Dipilih, bukan ditumpuk.</p>
            </div>
            <div class="product-grid">
                @foreach($featured as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="text-center" style="margin-top:40px;">
                <a href="{{ route('catalog.index') }}" class="btn btn-outline">Lihat Semua Produk</a>
            </div>
        </div>
    </section>

    {{-- ===== TENTANG BRAND ===== --}}
    <section class="about-band section">
        <div class="container">
            <div class="about-grid">
                <div>
                    <div class="eyebrow">Filosofi</div>
                    <h2>Dibuat berat.<br>Dipakai lama.</h2>
                    <p>KELAM bukan fast fashion. Tiap potong dirancang dengan bahan bergramasi tinggi, jahitan yang tahan, dan siluet yang tidak ikut tren musiman — biar tetap relevan tahun depan.</p>
                    <p>Kami merilis sedikit, bukan banyak. Itu sebabnya tidak semua orang punya. Dan memang bukan untuk semua.</p>
                    <ul class="about-features">
                        <li><span class="rust">■</span><span><strong>Bahan berat</strong> — fleece 380 gsm, cotton 24s, melton wool-blend.</span></li>
                        <li><span class="rust">■</span><span><strong>Kurasi terbatas</strong> — koleksi kecil, tiap piece diperhatikan.</span></li>
                        <li><span class="rust">■</span><span><strong>Potongan modern</strong> — oversized &amp; boxy yang tetap rapi.</span></li>
                    </ul>
                </div>
                <div class="about-visual" style="background: linear-gradient(160deg, #2E2E2E, #0A0A0A);"></div>
            </div>
        </div>
    </section>

    {{-- ===== QUOTE / SOCIAL PROOF ===== --}}
    <section class="quote-band">
        <div class="container">
            <div class="eyebrow" style="margin-bottom:20px;">Manifesto</div>
            <blockquote>&ldquo;Kalau semua orang pakai, itu bukan <span class="rust">identitas.</span> Itu seragam.&rdquo;</blockquote>
            <a href="https://instagram.com/{{ ltrim($globalSettings['instagram'], '@') }}" target="_blank" rel="noopener" class="ig-handle">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
                Ikuti {{ $globalSettings['instagram'] }}
            </a>
        </div>
    </section>

    {{-- ===== TESTIMONI ===== --}}
    <section class="section">
        <div class="container">
            <div class="section-head text-center" style="text-align:center;">
                <div class="eyebrow">Kata Mereka</div>
                <h2>Testimoni</h2>
            </div>
            <div class="testi-grid">
                @foreach($testimonials as $t)
                    <div class="testi-card">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $t['rating'] ? '' : 'off' }}">★</span>
                            @endfor
                        </div>
                        <p>&ldquo;{{ $t['text'] }}&rdquo;</p>
                        <div class="testi-who">{{ $t['name'] }} <span>— {{ $t['city'] }}</span></div>
                    </div>
                @endforeach
            </div>
            <p class="placeholder-note">
                ⚠︎ Testimoni di atas masih <strong>data contoh (placeholder)</strong> — ganti dengan testimoni pelanggan asli sebelum situs dipakai serius.
            </p>
        </div>
    </section>

@endsection
