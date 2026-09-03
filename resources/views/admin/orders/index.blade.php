@extends('layouts.admin')

@section('title', 'Pesanan')
@section('heading', 'Pesanan')

@section('content')
<div class="filter-chips" style="margin-bottom:22px;">
    <a href="{{ route('admin.orders.index') }}" class="chip {{ !$currentStatus ? 'active' : '' }}">Semua</a>
    @foreach($statuses as $value => $label)
        <a href="{{ route('admin.orders.index', ['status' => $value]) }}" class="chip {{ $currentStatus === $value ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

<div class="panel">
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr><th>No. Pesanan</th><th>Pelanggan</th><th>Item</th><th>Total</th><th>Metode</th><th>Status</th><th>Tanggal</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="link-rust">{{ $order->order_number }}</a></td>
                        <td>{{ $order->customer_name }}<br><span class="muted" style="font-size:0.78rem;">{{ $order->customer_phone }}</span></td>
                        <td class="muted">{{ $order->items_count }} item</td>
                        <td>{{ $order->formattedTotal() }}</td>
                        <td class="muted">{{ $order->paymentMethodLabel() }}</td>
                        <td><span class="status-badge st-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                        <td class="muted">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="link-rust">Detail →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">Belum ada pesanan{{ $currentStatus ? ' dengan status ini' : '' }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
