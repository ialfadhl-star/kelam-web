@php
    $inStock = $product->inStock();
@endphp
<a href="{{ route('catalog.show', $product) }}" class="product-card">
    <div class="thumb" style="background: linear-gradient(135deg, {{ $product->gradient_from }}, {{ $product->gradient_to }});">
        @if($product->is_featured)
            <span class="badge badge-rust">Unggulan</span>
        @elseif(!$inStock)
            <span class="badge badge-out">Habis</span>
        @endif
        <span class="thumb-label">{{ $product->name }}</span>
    </div>
    <div class="product-meta">
        <div class="p-cat">{{ $product->category }}</div>
        <div class="p-name head">{{ $product->name }}</div>
        <div class="p-price">{{ $product->formattedPrice() }}</div>
        @if($product->colors()->count() > 0)
            <div class="swatches">
                @foreach($product->colors() as $variant)
                    <span class="swatch" style="background: {{ $variant->color_hex }};" title="{{ $variant->color }}"></span>
                @endforeach
            </div>
        @endif
    </div>
</a>
