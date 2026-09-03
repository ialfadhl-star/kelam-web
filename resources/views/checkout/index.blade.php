@extends('layouts.app')

@section('title', 'Checkout — KELAM')

@section('content')
<div class="page-head">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('cart.index') }}">Keranjang</a> / Checkout</div>
        <h1>Checkout</h1>
    </div>
</div>

<div class="container">
    @if(session('error'))
        <div class="flash flash-error" style="margin-top:24px;">{{ session('error') }}</div>
    @endif

    <form action="{{ route('checkout.store') }}" method="post">
        @csrf
        <div class="checkout-layout">
            <div>
                {{-- ALAMAT --}}
                <div class="form-section">
                    <h3 class="head"><span class="num">01</span> Alamat Pengiriman</h3>
                    <div class="field-row">
                        <div class="field">
                            <label>Nama Lengkap <span class="req">*</span></label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
                            @error('customer_name')<div class="err">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>No. HP / WhatsApp <span class="req">*</span></label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="08xxxxxxxxxx">
                            @error('customer_phone')<div class="err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="field">
                        <label>Email <span class="muted">(opsional)</span></label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}">
                        @error('customer_email')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Alamat Lengkap <span class="req">*</span></label>
                        <textarea name="shipping_address" rows="3" required placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<div class="err">{{ $message }}</div>@enderror
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label>Kota / Kabupaten <span class="req">*</span></label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required>
                            @error('shipping_city')<div class="err">{{ $message }}</div>@enderror
                        </div>
                        <div class="field">
                            <label>Kode Pos <span class="muted">(opsional)</span></label>
                            <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}">
                        </div>
                    </div>
                    <div class="field">
                        <label>Catatan <span class="muted">(opsional)</span></label>
                        <textarea name="notes" rows="2" placeholder="Patokan alamat, permintaan khusus, dll.">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- PEMBAYARAN --}}
                <div class="form-section">
                    <h3 class="head"><span class="num">02</span> Metode Pembayaran</h3>
                    <div class="pay-options">
                        @php
                            $payMeta = [
                                'transfer_bank' => ['BANK', 'Transfer ke rekening, konfirmasi manual.'],
                                'qris' => ['QRIS', 'Scan kode QR dari e-wallet atau m-banking.'],
                                'cod' => ['COD', 'Bayar tunai saat barang sampai.'],
                            ];
                        @endphp
                        @foreach($paymentMethods as $value => $label)
                            <label class="pay-option {{ old('payment_method', 'transfer_bank') === $value ? 'selected' : '' }}">
                                <input type="radio" name="payment_method" value="{{ $value }}" {{ old('payment_method', 'transfer_bank') === $value ? 'checked' : '' }}>
                                <span class="pay-icon">{{ $payMeta[$value][0] ?? '' }}</span>
                                <span>
                                    <span class="pay-name">{{ $label }}</span>
                                    <span class="pay-desc">{{ $payMeta[$value][1] ?? '' }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method')<div class="err" style="margin-top:10px;">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- RINGKASAN --}}
            <aside>
                <div class="summary">
                    <h3 class="head">Pesanan Kamu</h3>
                    @foreach($items as $item)
                        <div class="mini-item">
                            <div class="mi-thumb" style="background: linear-gradient(140deg, {{ $item['gradient_from'] }}, {{ $item['gradient_to'] }});"></div>
                            <div>
                                <div class="mi-name">{{ $item['product_name'] }}</div>
                                <div class="mi-variant">{{ $item['color'] }} / {{ $item['size'] }} × {{ $item['quantity'] }}</div>
                            </div>
                            <div class="mi-price">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach

                    <div class="summary-row" style="margin-top:14px;"><span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    <div class="summary-row">
                        <span>Ongkir</span>
                        @if($shippingCost === 0)
                            <span class="free">GRATIS</span>
                        @else
                            <span>Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="summary-row total"><span>Total</span><span>Rp{{ number_format($total, 0, ',', '.') }}</span></div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top:20px;">Buat Pesanan</button>
                    <p class="disclaimer" style="margin-top:14px;">Pembayaran diselesaikan sesuai instruksi setelah pesanan dibuat. Ini simulasi checkout — belum terhubung ke payment gateway otomatis.</p>
                </div>
            </aside>
        </div>
    </form>
</div>
@endsection
