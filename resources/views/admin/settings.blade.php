@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('heading', 'Pengaturan Toko')

@section('content')
@if($errors->any())
    <div class="flash flash-error">
        <ul style="margin:0 0 0 18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="post">
    @csrf @method('PATCH')

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:22px;" class="settings-grid">
        <div class="panel" style="padding:24px;">
            <h3 class="head" style="margin-bottom:18px;">Identitas Toko</h3>
            <div class="field">
                <label>Nama Toko <span class="req rust">*</span></label>
                <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" required>
            </div>
            <div class="field">
                <label>Tagline</label>
                <input type="text" name="store_tagline" value="{{ old('store_tagline', $settings['store_tagline']) }}">
            </div>
            <div class="field">
                <label>Teks Pengumuman (banner atas)</label>
                <input type="text" name="announcement" value="{{ old('announcement', $settings['announcement']) }}">
            </div>
        </div>

        <div class="panel" style="padding:24px;">
            <h3 class="head" style="margin-bottom:18px;">Kontak</h3>
            <div class="field">
                <label>Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}">
            </div>
            <div class="field">
                <label>Telepon / WhatsApp</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}">
            </div>
            <div class="field">
                <label>Instagram</label>
                <input type="text" name="instagram" value="{{ old('instagram', $settings['instagram']) }}" placeholder="@kelam.id">
            </div>
        </div>

        <div class="panel" style="padding:24px;">
            <h3 class="head" style="margin-bottom:18px;">Ongkir</h3>
            <div class="field">
                <label>Minimum Gratis Ongkir (Rp)</label>
                <input type="number" name="free_shipping_min" value="{{ old('free_shipping_min', $settings['free_shipping_min']) }}" min="0">
            </div>
            <div class="field">
                <label>Ongkir Flat (Rp)</label>
                <input type="number" name="shipping_cost" value="{{ old('shipping_cost', $settings['shipping_cost']) }}" min="0">
            </div>
            <p class="muted" style="font-size:0.78rem;">Kalau subtotal ≥ minimum di atas, ongkir jadi gratis otomatis.</p>
        </div>

        <div class="panel" style="padding:24px;">
            <h3 class="head" style="margin-bottom:18px;">Info Rekening (untuk halaman konfirmasi)</h3>
            <div class="field">
                <label>Nama Bank</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name']) }}">
            </div>
            <div class="field">
                <label>No. Rekening</label>
                <input type="text" name="bank_account" value="{{ old('bank_account', $settings['bank_account']) }}">
            </div>
            <div class="field">
                <label>Atas Nama</label>
                <input type="text" name="bank_holder" value="{{ old('bank_holder', $settings['bank_holder']) }}">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top:22px;">Simpan Pengaturan</button>
</form>
@endsection
