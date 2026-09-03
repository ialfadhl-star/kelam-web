@extends('layouts.admin')

@php $editing = $product->exists; @endphp

@section('title', $editing ? 'Edit Produk' : 'Tambah Produk')
@section('heading', $editing ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div style="margin-bottom:18px;"><a href="{{ route('admin.products.index') }}" class="link-rust">← Kembali ke daftar produk</a></div>

@if($errors->any())
    <div class="flash flash-error">
        <strong>Periksa lagi:</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<form action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" method="post">
    @csrf
    @if($editing) @method('PUT') @endif

    <div style="display:grid; grid-template-columns: 1fr 320px; gap:22px;" class="product-form-grid">
        <div>
            <div class="panel" style="padding:24px;">
                <h3 class="head" style="margin-bottom:18px;">Info Produk</h3>
                <div class="field">
                    <label>Nama Produk <span class="req rust">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Kategori <span class="req rust">*</span></label>
                        <select name="category" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $product->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Harga (Rp) <span class="req rust">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required>
                    </div>
                </div>
                <div class="field">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="field">
                    <label>Bahan / Material</label>
                    <input type="text" name="material" value="{{ old('material', $product->material) }}" placeholder="mis. Fleece cotton 380 gsm">
                </div>
            </div>

            {{-- VARIAN --}}
            <div class="panel" style="padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 class="head">Varian (Warna / Ukuran / Stok)</h3>
                    <button type="button" class="btn btn-outline btn-sm" id="add-variant">+ Baris</button>
                </div>
                <div class="variant-editor" id="variant-editor">
                    <div class="vh">
                        <span>Warna</span><span>Hex</span><span>Ukuran</span><span>Stok</span><span></span>
                    </div>
                    <div id="variant-rows">
                        @php
                            $oldVariants = old('variants');
                            $variantRows = $oldVariants ?? ($editing ? $product->variants->map(fn($v)=>['color'=>$v->color,'color_hex'=>$v->color_hex,'size'=>$v->size,'stock'=>$v->stock])->toArray() : []);
                        @endphp
                        @forelse($variantRows as $i => $v)
                            <div class="variant-row">
                                <input name="variants[{{ $i }}][color]" value="{{ $v['color'] ?? '' }}" placeholder="Onyx Black">
                                <input type="color" name="variants[{{ $i }}][color_hex]" value="{{ $v['color_hex'] ?? '#0A0A0A' }}">
                                <input name="variants[{{ $i }}][size]" value="{{ $v['size'] ?? '' }}" placeholder="S/M/L/XL">
                                <input type="number" name="variants[{{ $i }}][stock]" value="{{ $v['stock'] ?? 0 }}" min="0">
                                <button type="button" class="v-remove" title="Hapus">&times;</button>
                            </div>
                        @empty
                            <div class="variant-row">
                                <input name="variants[0][color]" placeholder="Onyx Black">
                                <input type="color" name="variants[0][color_hex]" value="#0A0A0A">
                                <input name="variants[0][size]" placeholder="S/M/L/XL">
                                <input type="number" name="variants[0][stock]" value="0" min="0">
                                <button type="button" class="v-remove" title="Hapus">&times;</button>
                            </div>
                        @endforelse
                    </div>
                </div>
                <p class="muted" style="font-size:0.78rem; margin-top:10px;">Kombinasi warna + ukuran harus unik. Baris kosong diabaikan. Untuk aksesoris gunakan ukuran "All Size".</p>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div>
            <div class="panel" style="padding:24px;">
                <h3 class="head" style="margin-bottom:16px;">Tampilan</h3>
                <div class="field">
                    <label>Gradient Dari</label>
                    <input type="color" id="gradient_from" name="gradient_from" value="{{ old('gradient_from', $product->gradient_from) }}" style="height:42px; padding:3px;">
                </div>
                <div class="field">
                    <label>Gradient Ke</label>
                    <input type="color" id="gradient_to" name="gradient_to" value="{{ old('gradient_to', $product->gradient_to) }}" style="height:42px; padding:3px;">
                </div>
                <div class="gradient-preview" id="gradient-preview"></div>
                <p class="muted" style="font-size:0.75rem; margin-top:8px;">Placeholder foto: gradient dipakai sampai foto asli diunggah.</p>
            </div>

            <div class="panel" style="padding:24px;">
                <h3 class="head" style="margin-bottom:16px;">Pengaturan</h3>
                <div class="field" style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="width:auto;">
                    <label for="is_featured" style="margin:0; text-transform:none; letter-spacing:0;">Produk Unggulan</label>
                </div>
                <div class="field" style="display:flex; align-items:center; gap:10px;">
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }} style="width:auto;">
                    <label for="is_active" style="margin:0; text-transform:none; letter-spacing:0;">Aktif (tampil di toko)</label>
                </div>
                <div class="field">
                    <label>Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">{{ $editing ? 'Simpan Perubahan' : 'Tambah Produk' }}</button>
        </div>
    </div>
</form>
@endsection
