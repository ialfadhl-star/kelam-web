@extends('layouts.app')

@section('title', 'Tentang — KELAM')

@section('content')
<div class="page-head">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Tentang</div>
        <h1>Tentang KELAM</h1>
    </div>
</div>

<div class="container section">
    <div class="about-grid">
        <div class="prose">
            <div class="eyebrow">Bukan untuk semua</div>
            <h2 class="head" style="font-size:2rem; margin:12px 0 20px;">Kami rilis sedikit. Dengan sengaja.</h2>
            <p>KELAM lahir dari satu keyakinan sederhana: pakaian yang bagus tidak harus ada di mana-mana. Justru karena tidak semua orang punya, ia jadi berarti.</p>
            <p>Kami tidak mengejar volume. Tiap koleksi dikurasi kecil — hoodie yang jatuhnya benar, jaket yang bahannya terasa di tangan, celana yang kantongnya benar-benar dipakai. Tidak ada yang dibuat asal ramai.</p>
            <h2>Bahan dulu, baru gaya</h2>
            <p>Fleece 380 gsm, cotton combed 24s, melton wool-blend, ripstop. Kami mulai dari material, karena itu yang bikin sebuah piece bertahan bertahun-tahun, bukan cuma semusim.</p>
            <h2>Untuk yang jalan sendiri</h2>
            <p>KELAM bukan seragam. Kalau semua orang pakai, itu bukan identitas. Ini buat kamu yang tidak butuh validasi ramai.</p>
        </div>
        <div>
            <div class="about-visual" style="background: linear-gradient(160deg, #33351F, #0A0A0A);"></div>
            <ul class="about-features">
                <li><span class="rust">■</span><span><strong>Kurasi terbatas</strong> — koleksi kecil, kualitas terjaga.</span></li>
                <li><span class="rust">■</span><span><strong>Bahan bergramasi tinggi</strong> — terasa premium, tahan lama.</span></li>
                <li><span class="rust">■</span><span><strong>Dibuat di Indonesia</strong> — mendukung produksi lokal.</span></li>
            </ul>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary" style="margin-top:24px;">Lihat Koleksi</a>
        </div>
    </div>
</div>
@endsection
