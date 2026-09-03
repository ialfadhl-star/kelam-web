@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <div class="s-label">Total Produk</div>
        <div class="s-value">{{ $stats['products'] }}</div>
    </div>
    <div class="stat-card">
        <div class="s-label">Total Pesanan</div>
        <div class="s-value">{{ $stats['orders'] }}</div>
    </div>
    <div class="stat-card">
        <div class="s-label">Menunggu Bayar</div>
        <div class="s-value rust">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card">
        <div class="s-label">Pendapatan (diproses+)</div>
        <div class="s-value">Rp{{ number_format($stats['revenue'], 0, ',', '.') }}</div>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h3 class="head">Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders.index') }}" class="link-rust">Lihat semua →</a>
    </div>
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr><th>No. Pesanan</th><th>Pelanggan</th><th>Total</th><th>Metode</th><th>Status</th><th>Tanggal</th></tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="link-rust">{{ $order->order_number }}</a></td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->formattedTotal() }}</td>
                        <td>{{ $order->paymentMethodLabel() }}</td>
                        <td><span class="status-badge st-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                        <td class="muted">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($lowStock->isNotEmpty())
<div class="panel">
    <div class="panel-head"><h3 class="head">Stok Menipis (≤ 5)</h3></div>
    <div class="table-wrap">
        <table class="data">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Total Stok</th><th></th></tr></thead>
            <tbody>
                @foreach($lowStock as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td class="muted">{{ $p->category }}</td>
                        <td class="rust">{{ $p->totalStock() }} pcs</td>
                        <td><a href="{{ route('admin.products.edit', $p) }}" class="link-rust">Kelola →</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
