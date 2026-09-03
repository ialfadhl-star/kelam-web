@extends('layouts.app')

@section('title', 'Keranjang — KELAM')

@section('content')
<div class="page-head">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Keranjang</div>
        <h1>Keranjang</h1>
    </div>
</div>

<div class="container">
    @if(session('error'))
        <div class="flash flash-error" style="margin-top:24px;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="flash flash-success" style="margin-top:24px;">{{ session('success') }}</div>
    @endif

    @if(empty($items))
        <div class="empty-state">
            <div class="ico">
                <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <h2 class="head">Keranjang Kosong</h2>
            <p>Belum ada yang dipilih. Waktunya cari sesuatu yang bukan untuk semua.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary">Mulai Belanja</a>
        </div>
    @else
        @php
            $freeMin = $globalSettings['free_shipping_min'];
            $progress = $freeMin > 0 ? min(100, round($subtotal / $freeMin * 100)) : 100;
            $remaining = max(0, $freeMin - $subtotal);
        @endphp
        <div class="cart-layout">
            <div>
                @foreach($items as $item)
                    <div class="cart-item" data-cart-row="{{ $item['variant_id'] }}">
                        <a href="{{ route('catalog.show', $item['slug']) }}" class="ci-thumb" style="background: linear-gradient(140deg, {{ $item['gradient_from'] }}, {{ $item['gradient_to'] }});"></a>
                        <div>
                            <a href="{{ route('catalog.show', $item['slug']) }}" class="ci-name head">{{ $item['product_name'] }}</a>
                            <div class="ci-variant">{{ $item['color'] }} / {{ $item['size'] }}</div>
                            <div class="ci-price">Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                        </div>
                        <div class="ci-right">
                            <div class="qty-ctrl">
                                <button type="button" onclick="kelamCartUpdate({{ $item['variant_id'] }}, {{ $item['quantity'] - 1 }})" aria-label="Kurangi">−</button>
                                <input type="text" value="{{ $item['quantity'] }}" readonly>
                                <button type="button" onclick="kelamCartUpdate({{ $item['variant_id'] }}, {{ $item['quantity'] + 1 }})" aria-label="Tambah">+</button>
                            </div>
                            <button type="button" class="ci-remove" onclick="kelamCartRemove({{ $item['variant_id'] }})">Hapus</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="summary">
                <h3 class="head">Ringkasan</h3>

                @if($freeMin > 0)
                    <div class="progress"><div class="bar" style="width: {{ $progress }}%;"></div></div>
                    @if($remaining > 0)
                        <p class="ship-hint">Belanja <strong>Rp{{ number_format($remaining, 0, ',', '.') }}</strong> lagi untuk gratis ongkir.</p>
                    @else
                        <p class="ship-hint" style="color:#9fd39f;">✓ Kamu dapat gratis ongkir!</p>
                    @endif
                @endif

                <div class="summary-row"><span>Subtotal</span><span id="cart-subtotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Ongkir</span><span>Dihitung saat checkout</span></div>
                <div class="summary-row total"><span>Total</span><span id="cart-total">Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>

                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" style="margin-top:20px;">Lanjut ke Checkout</a>
                <a href="{{ route('catalog.index') }}" class="btn btn-ghost btn-block" style="margin-top:8px;">Lanjut Belanja</a>
            </aside>
        </div>
    @endif
</div>

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    async function kelamCartUpdate(variantId, quantity) {
        const res = await fetch('{{ route('cart.update') }}', {
            method: 'PATCH',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({ variant_id: variantId, quantity })
        });
        if (res.ok) location.reload();
    }
    async function kelamCartRemove(variantId) {
        const res = await fetch('{{ route('cart.remove') }}', {
            method: 'DELETE',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({ variant_id: variantId })
        });
        if (res.ok) location.reload();
    }
</script>
@endpush
@endsection
