@extends('layouts.admin')

@section('title', 'Pesanan ' . $order->order_number)
@section('heading', 'Detail Pesanan')

@section('content')
<div style="margin-bottom:18px;"><a href="{{ route('admin.orders.index') }}" class="link-rust">← Kembali ke daftar pesanan</a></div>

<div style="display:grid; grid-template-columns: 1fr 340px; gap:22px;" class="order-detail-grid">
    <div>
        <div class="panel">
            <div class="panel-head">
                <h3 class="head">{{ $order->order_number }}</h3>
                <span class="status-badge st-{{ $order->status }}">{{ $order->statusLabel() }}</span>
            </div>
            <div class="table-wrap">
                <table class="data">
                    <thead><tr><th>Produk</th><th>Varian</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="muted">{{ $item->color }} / {{ $item->size }}</td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp{{ number_format($item->line_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:18px 22px; border-top:1px solid var(--line);">
                <div class="summary-row"><span>Subtotal</span><span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Ongkir</span><span>{{ $order->shipping_cost === 0 ? 'GRATIS' : 'Rp'.number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                <div class="summary-row total"><span>Total</span><span>{{ $order->formattedTotal() }}</span></div>
            </div>
        </div>

        <div class="panel" style="padding:24px;">
            <h3 class="head" style="margin-bottom:16px;">Pengiriman</h3>
            <div class="bank-row"><span class="k">Nama</span><span>{{ $order->customer_name }}</span></div>
            <div class="bank-row"><span class="k">Telepon</span><span>{{ $order->customer_phone }}</span></div>
            @if($order->customer_email)<div class="bank-row"><span class="k">Email</span><span>{{ $order->customer_email }}</span></div>@endif
            <div class="bank-row"><span class="k">Alamat</span><span style="text-align:right; max-width:60%;">{{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</span></div>
            @if($order->notes)<div class="bank-row"><span class="k">Catatan</span><span style="text-align:right; max-width:60%;">{{ $order->notes }}</span></div>@endif
            <div class="bank-row" style="border:0;"><span class="k">Metode Bayar</span><span>{{ $order->paymentMethodLabel() }}</span></div>
        </div>
    </div>

    {{-- UPDATE STATUS --}}
    <div>
        <div class="panel" style="padding:24px;">
            <h3 class="head" style="margin-bottom:16px;">Update Status</h3>
            <form action="{{ route('admin.orders.update', $order) }}" method="post">
                @csrf @method('PATCH')
                <div class="field">
                    <label>Status Pesanan</label>
                    <select name="status">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Kurir</label>
                    <input type="text" name="courier" value="{{ old('courier', $order->courier) }}" placeholder="JNE / J&T / SiCepat">
                </div>
                <div class="field">
                    <label>No. Resi</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Nomor resi pengiriman">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Simpan</button>
            </form>
        </div>

        <div class="panel" style="padding:24px;">
            <h3 class="head" style="margin-bottom:12px;">Info</h3>
            <div class="bank-row"><span class="k">Dibuat</span><span>{{ $order->created_at->format('d M Y H:i') }}</span></div>
            <div class="bank-row" style="border:0;"><span class="k">Update terakhir</span><span>{{ $order->updated_at->format('d M Y H:i') }}</span></div>
        </div>
    </div>
</div>
@endsection
