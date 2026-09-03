@extends('layouts.app')

@section('title', 'Pesanan ' . $order->order_number . ' — KELAM')

@section('content')
<div class="confirm-hero">
    <div class="container">
        <div class="confirm-check">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        <div class="eyebrow">Pesanan Tercatat</div>
        <h1>Terima kasih, {{ Str::before($order->customer_name, ' ') }}.</h1>
        <p class="muted">Pesanan kamu sudah masuk. Selesaikan pembayaran sesuai instruksi di bawah.</p>
        <div class="order-no">{{ $order->order_number }}</div>
    </div>
</div>

<div class="container">
    <div class="confirm-grid">
        {{-- INSTRUKSI BAYAR --}}
        <div>
            @if($order->payment_method === 'transfer_bank')
                <div class="pay-instruction">
                    <h3 class="head">Instruksi — Transfer Bank</h3>
                    <p class="muted" style="margin-bottom:16px;">Transfer <strong style="color:var(--off-white);">{{ $order->formattedTotal() }}</strong> ke rekening berikut:</p>
                    <div class="bank-row"><span class="k">Bank</span><span class="v">{{ $bank['name'] }}</span></div>
                    <div class="bank-row"><span class="k">No. Rekening</span><span class="v">{{ $bank['account'] }}</span></div>
                    <div class="bank-row"><span class="k">Atas Nama</span><span class="v">{{ $bank['holder'] }}</span></div>
                    <div class="bank-row"><span class="k">Jumlah</span><span class="v rust">{{ $order->formattedTotal() }}</span></div>
                    <p class="muted" style="margin-top:16px; font-size:0.85rem;">Setelah transfer, konfirmasi via WhatsApp {{ $globalSettings['contact_phone'] ?: 'admin' }} dengan menyertakan nomor pesanan.</p>
                </div>
            @elseif($order->payment_method === 'qris')
                <div class="pay-instruction">
                    <h3 class="head">Instruksi — QRIS</h3>
                    <p class="muted" style="margin-bottom:8px;">Scan kode di bawah dengan e-wallet atau m-banking apa pun:</p>
                    <div class="qr-box">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20v.01"/></svg>
                        <span style="font-size:0.72rem;">QR Placeholder</span>
                    </div>
                    <div class="bank-row"><span class="k">Jumlah</span><span class="v rust">{{ $order->formattedTotal() }}</span></div>
                    <p class="muted" style="margin-top:16px; font-size:0.85rem;">Kode QR di atas masih placeholder — pada implementasi asli akan digenerate dari penyedia QRIS.</p>
                </div>
            @else
                <div class="pay-instruction">
                    <h3 class="head">Instruksi — COD (Bayar di Tempat)</h3>
                    <p class="muted" style="margin-bottom:16px;">Siapkan uang tunai sejumlah <strong style="color:var(--off-white);">{{ $order->formattedTotal() }}</strong> saat kurir mengantar barang ke alamat kamu.</p>
                    <div class="bank-row"><span class="k">Bayar saat</span><span class="v">Barang diterima</span></div>
                    <div class="bank-row"><span class="k">Jumlah</span><span class="v rust">{{ $order->formattedTotal() }}</span></div>
                    <p class="muted" style="margin-top:16px; font-size:0.85rem;">Pastikan nomor HP aktif agar kurir bisa menghubungi saat pengiriman.</p>
                </div>
            @endif

            <p class="disclaimer">Pesanan kamu sudah tercatat, silakan selesaikan pembayaran sesuai instruksi di atas. Status pesanan akan diperbarui oleh admin setelah pembayaran diverifikasi. <br><em>(Demo: pembayaran belum diproses otomatis oleh payment gateway.)</em></p>

            {{-- DETAIL PENGIRIMAN --}}
            <div class="form-section" style="margin-top:24px;">
                <h3 class="head">Detail Pengiriman</h3>
                <div class="bank-row"><span class="k">Penerima</span><span>{{ $order->customer_name }}</span></div>
                <div class="bank-row"><span class="k">Telepon</span><span>{{ $order->customer_phone }}</span></div>
                <div class="bank-row"><span class="k">Alamat</span><span style="text-align:right; max-width:60%;">{{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</span></div>
                <div class="bank-row" style="border:0;"><span class="k">Status</span><span class="status-badge st-{{ $order->status }}">{{ $order->statusLabel() }}</span></div>
            </div>

            <div style="margin-top:24px;">
                <a href="{{ route('catalog.index') }}" class="btn btn-outline">Lanjut Belanja</a>
            </div>
        </div>

        {{-- RINGKASAN ITEM --}}
        <aside>
            <div class="summary">
                <h3 class="head">Ringkasan Pesanan</h3>
                @foreach($order->items as $item)
                    <div class="mini-item">
                        <div>
                            <div class="mi-name">{{ $item->product_name }}</div>
                            <div class="mi-variant">{{ $item->color }} / {{ $item->size }} × {{ $item->quantity }}</div>
                        </div>
                        <div class="mi-price">Rp{{ number_format($item->line_total, 0, ',', '.') }}</div>
                    </div>
                @endforeach
                <div class="summary-row" style="margin-top:14px;"><span>Subtotal</span><span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="summary-row">
                    <span>Ongkir</span>
                    @if($order->shipping_cost === 0)<span class="free">GRATIS</span>@else<span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>@endif
                </div>
                <div class="summary-row total"><span>Total</span><span>{{ $order->formattedTotal() }}</span></div>
                <div class="summary-row"><span>Metode</span><span>{{ $order->paymentMethodLabel() }}</span></div>
            </div>
        </aside>
    </div>
</div>
@endsection
