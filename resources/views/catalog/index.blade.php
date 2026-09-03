@extends('layouts.app')

@section('title', 'Katalog — KELAM')

@section('content')
<div class="page-head">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Katalog</div>
        <h1>{{ $category ?? 'Semua Produk' }}</h1>
    </div>
</div>

<div class="container">
    <div class="catalog-layout">
        {{-- ===== FILTER ===== --}}
        <aside class="filters">
            <div class="filter-group">
                <h4 class="head">Kategori</h4>
                <ul class="filter-list">
                    <li><a href="{{ route('catalog.index', array_filter(['warna'=>$color,'ukuran'=>$size,'urut'=>$sort!=='default'?$sort:null])) }}" class="{{ !$category ? 'active' : '' }}">Semua</a></li>
                    @foreach($categories as $cat)
                        <li><a href="{{ route('catalog.index', array_filter(['kategori'=>$cat,'warna'=>$color,'ukuran'=>$size,'urut'=>$sort!=='default'?$sort:null])) }}" class="{{ $category === $cat ? 'active' : '' }}">{{ $cat }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Filter warna & ukuran — UPGRADE dari baseline Paket Starter --}}
            <div class="filter-group">
                <h4 class="head">Warna</h4>
                <div class="filter-chips">
                    @foreach($colors as $c)
                        <a href="{{ route('catalog.index', array_filter(['kategori'=>$category,'warna'=>$color===$c?null:$c,'ukuran'=>$size,'urut'=>$sort!=='default'?$sort:null])) }}" class="chip {{ $color === $c ? 'active' : '' }}">{{ $c }}</a>
                    @endforeach
                </div>
            </div>

            <div class="filter-group">
                <h4 class="head">Ukuran</h4>
                <div class="filter-chips">
                    @foreach($sizes as $s)
                        <a href="{{ route('catalog.index', array_filter(['kategori'=>$category,'warna'=>$color,'ukuran'=>$size===$s?null:$s,'urut'=>$sort!=='default'?$sort:null])) }}" class="chip {{ $size === $s ? 'active' : '' }}">{{ $s }}</a>
                    @endforeach
                </div>
            </div>

            @if($category || $color || $size)
                <a href="{{ route('catalog.index') }}" class="filter-reset">✕ Reset filter</a>
            @endif

            <p class="upgrade-note">Catatan builder: filter warna &amp; ukuran ini di luar baseline Paket Starter (upgrade), sesuai keputusan yang diminta.</p>
        </aside>

        {{-- ===== HASIL ===== --}}
        <div>
            <div class="catalog-topbar">
                <button class="btn btn-outline btn-sm mobile-filter-toggle" data-filter-toggle>Filter</button>
                <span class="catalog-count">{{ $products->count() }} produk</span>
                <form method="get" style="margin-left:auto;">
                    @if($category)<input type="hidden" name="kategori" value="{{ $category }}">@endif
                    @if($color)<input type="hidden" name="warna" value="{{ $color }}">@endif
                    @if($size)<input type="hidden" name="ukuran" value="{{ $size }}">@endif
                    <select name="urut" class="sort-select" onchange="this.form.submit()">
                        <option value="default" {{ $sort==='default'?'selected':'' }}>Urutan Default</option>
                        <option value="terbaru" {{ $sort==='terbaru'?'selected':'' }}>Terbaru</option>
                        <option value="termurah" {{ $sort==='termurah'?'selected':'' }}>Harga Termurah</option>
                        <option value="termahal" {{ $sort==='termahal'?'selected':'' }}>Harga Termahal</option>
                    </select>
                </form>
            </div>

            @if($products->isEmpty())
                <div class="empty-state">
                    <h2 class="head">Tidak ada produk</h2>
                    <p>Tidak ada produk yang cocok dengan filter ini.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline">Reset Filter</a>
                </div>
            @else
                <div class="product-grid">
                    @foreach($products as $product)
                        @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
