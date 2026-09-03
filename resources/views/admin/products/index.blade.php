@extends('layouts.admin')

@section('title', 'Produk')
@section('heading', 'Produk')

@section('content')
<div class="panel">
    <div class="panel-head">
        <h3 class="head">Semua Produk ({{ $products->count() }})</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Tambah Produk</a>
    </div>
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Varian</th><th>Stok</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td style="display:flex; align-items:center; gap:12px;">
                            <span style="width:36px; height:44px; border-radius:2px; border:1px solid var(--line); background: linear-gradient(140deg, {{ $product->gradient_from }}, {{ $product->gradient_to }});"></span>
                            <span>
                                {{ $product->name }}
                                @if($product->is_featured)<span class="rust" style="font-size:0.7rem;"> ★ Unggulan</span>@endif
                            </span>
                        </td>
                        <td class="muted">{{ $product->category }}</td>
                        <td>{{ $product->formattedPrice() }}</td>
                        <td class="muted">{{ $product->variants->count() }} varian</td>
                        <td class="{{ $product->totalStock() <= 5 ? 'rust' : '' }}">{{ $product->totalStock() }}</td>
                        <td>
                            @if($product->is_active)
                                <span class="status-badge st-selesai">Aktif</span>
                            @else
                                <span class="status-badge st-dibatalkan">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.products.edit', $product) }}" class="link-rust">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="post" onsubmit="return confirm('Hapus produk {{ $product->name }}? Tindakan ini tidak bisa dibatalkan.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="link-danger" style="background:none; border:0; cursor:pointer;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">Belum ada produk. <a href="{{ route('admin.products.create') }}" class="link-rust">Tambah sekarang</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
