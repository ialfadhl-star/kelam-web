@extends('layouts.app')

@section('title', 'Kontak & FAQ — KELAM')

@section('content')
<div class="page-head">
    <div class="container">
        <div class="breadcrumb"><a href="{{ route('home') }}">Beranda</a> / Kontak</div>
        <h1>Kontak &amp; Bantuan</h1>
    </div>
</div>

<div class="container section">
    <div class="contact-grid" style="margin-bottom:56px;">
        <div class="contact-card">
            <div class="ico"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg></div>
            <div class="k">Instagram</div>
            <div class="v"><a href="https://instagram.com/{{ ltrim($globalSettings['instagram'], '@') }}" target="_blank" rel="noopener" class="rust">{{ $globalSettings['instagram'] }}</a></div>
        </div>
        <div class="contact-card">
            <div class="ico"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg></div>
            <div class="k">Email</div>
            <div class="v"><a href="mailto:{{ $globalSettings['contact_email'] }}" class="rust">{{ $globalSettings['contact_email'] }}</a></div>
        </div>
        @if(!empty($globalSettings['contact_phone']))
        <div class="contact-card">
            <div class="ico"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3-8.6A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg></div>
            <div class="k">WhatsApp / Telepon</div>
            <div class="v"><a href="tel:{{ $globalSettings['contact_phone'] }}" class="rust">{{ $globalSettings['contact_phone'] }}</a></div>
        </div>
        @endif
    </div>

    {{-- FAQ --}}
    <div id="faq" class="prose" style="max-width:820px; margin:0 auto;">
        <div class="eyebrow text-center" style="text-align:center;">Sering Ditanya</div>
        <h2 class="head text-center" style="text-align:center; font-size:2rem; margin:12px 0 30px;">FAQ</h2>

        <details class="faq-item">
            <summary>Berapa lama pengiriman? <span class="plus">+</span></summary>
            <p>Pesanan diproses 1–2 hari kerja setelah pembayaran diverifikasi. Estimasi pengiriman 2–5 hari kerja tergantung lokasi dan kurir.</p>
        </details>
        <details class="faq-item">
            <summary>Metode pembayaran apa saja yang diterima? <span class="plus">+</span></summary>
            <p>Transfer bank, QRIS, dan COD (bayar di tempat). Pilih saat checkout, instruksi menyusul di halaman konfirmasi pesanan.</p>
        </details>
        <details class="faq-item">
            <summary>Apakah ada gratis ongkir? <span class="plus">+</span></summary>
            <p>Ya. Gratis ongkir se-Indonesia untuk pembelian minimal Rp{{ number_format($globalSettings['free_shipping_min'], 0, ',', '.') }}.</p>
        </details>
        <details class="faq-item" id="retur">
            <summary>Bagaimana kebijakan pengembalian? <span class="plus">+</span></summary>
            <p>Penukaran ukuran/warna bisa dilakukan dalam 3 hari setelah barang diterima, selama produk belum dipakai dan label masih utuh. Hubungi kami via Instagram atau email untuk memulai proses.</p>
        </details>
        <details class="faq-item">
            <summary>Apakah ukuran fit sesuai standar? <span class="plus">+</span></summary>
            <p>Banyak produk kami berpotongan oversized/boxy. Cek deskripsi tiap produk untuk detail bahan dan potongan sebelum memilih ukuran.</p>
        </details>
    </div>
</div>
@endsection
