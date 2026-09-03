@extends('layouts.app')

@section('title', $product->name . ' — KELAM')
@section('meta_description', Str::limit(strip_tags($product->description), 150))

@section('content')
<div class="container">
    <div class="pdp">
        {{-- ===== GALLERY (placeholder gradient) ===== --}}
        <div class="pdp-gallery">
            <div class="pdp-main-img" style="background: linear-gradient(150deg, {{ $product->gradient_from }}, {{ $product->gradient_to }});">
                <span class="thumb-label">{{ $product->name }}</span>
            </div>
            <div class="pdp-thumbs">
                @for($i = 0; $i < 3; $i++)
                    <div class="t" style="background: linear-gradient({{ 120 + $i*40 }}deg, {{ $product->gradient_from }}, {{ $product->gradient_to }});"></div>
                @endfor
            </div>
            <div class="placeholder-tag">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                Foto placeholder — ganti dengan foto produk asli
            </div>
        </div>

        {{-- ===== INFO + OPSI ===== --}}
        <div class="pdp-info">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Beranda</a> /
                <a href="{{ route('catalog.index', ['kategori' => $product->category]) }}">{{ $product->category }}</a>
            </div>
            <h1>{{ $product->name }}</h1>
            <div class="pdp-price">{{ $product->formattedPrice() }}</div>
            <p class="pdp-desc">{{ $product->description }}</p>

            @if(session('success'))
                <div class="flash flash-success">{{ session('success') }}</div>
            @endif

            @php
                $variantData = $product->variants->map(fn($v) => [
                    'id' => $v->id, 'color' => $v->color, 'size' => $v->size, 'stock' => $v->stock,
                ])->values();
            @endphp
            <div id="pdp-options" data-variants="{{ json_encode($variantData) }}">
                {{-- Warna --}}
                <div class="opt-block">
                    <div class="opt-label">Warna: <span class="opt-value" id="selected-color-label">—</span></div>
                    <div class="opt-row">
                        @foreach($product->colors() as $variant)
                            <button type="button" class="opt-color" data-color="{{ $variant->color }}"
                                    style="background: {{ $variant->color_hex }};" title="{{ $variant->color }}"></button>
                        @endforeach
                    </div>
                </div>

                {{-- Ukuran --}}
                <div class="opt-block">
                    <div class="opt-label">Ukuran</div>
                    <div class="opt-row">
                        @foreach($product->sizes() as $size)
                            <button type="button" class="opt-size" data-size="{{ $size }}">{{ $size }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Stok --}}
                <div class="stock-line stock-out" id="stock-line">
                    <span class="stock-dot"></span>Pilih warna &amp; ukuran
                </div>

                {{-- Qty + Add --}}
                <form action="{{ route('cart.add') }}" method="post" data-add-to-cart>
                    @csrf
                    <input type="hidden" name="variant_id" id="selected-variant-id" value="">
                    <div class="qty-row">
                        <div class="qty-ctrl">
                            <button type="button" data-qty="dec" aria-label="Kurangi">−</button>
                            <input type="text" name="quantity" id="qty-input" value="1" inputmode="numeric" readonly>
                            <button type="button" data-qty="inc" aria-label="Tambah">+</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" id="add-btn" disabled>Tambah ke Keranjang</button>
                </form>
            </div>

            {{-- Detail bahan --}}
            <div class="pdp-meta-list">
                <div class="row"><span class="k">Bahan</span><span>{{ $product->material ?? '—' }}</span></div>
                <div class="row"><span class="k">Kategori</span><span>{{ $product->category }}</span></div>
                <div class="row"><span class="k">Total stok</span><span>{{ $product->totalStock() }} pcs</span></div>
            </div>
        </div>
    </div>

    {{-- ===== PRODUK TERKAIT ===== --}}
    @if($related->isNotEmpty())
        <section class="section">
            <div class="section-head">
                <div class="eyebrow">Lihat Juga</div>
                <h2>Dari Kategori yang Sama</h2>
            </div>
            <div class="product-grid cols-3">
                @foreach($related as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
