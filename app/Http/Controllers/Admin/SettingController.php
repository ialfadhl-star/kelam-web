<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected array $keys = [
        'store_name', 'store_tagline', 'contact_email', 'contact_phone',
        'instagram', 'announcement', 'free_shipping_min', 'shipping_cost',
        'bank_name', 'bank_account', 'bank_holder',
    ];

    public function edit()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::get($key);
        }
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:80'],
            'store_tagline' => ['nullable', 'string', 'max:160'],
            'contact_email' => ['nullable', 'email', 'max:120'],
            'contact_phone' => ['nullable', 'string', 'max:40'],
            'instagram' => ['nullable', 'string', 'max:60'],
            'announcement' => ['nullable', 'string', 'max:200'],
            'free_shipping_min' => ['nullable', 'integer', 'min:0'],
            'shipping_cost' => ['nullable', 'integer', 'min:0'],
            'bank_name' => ['nullable', 'string', 'max:60'],
            'bank_account' => ['nullable', 'string', 'max:40'],
            'bank_holder' => ['nullable', 'string', 'max:80'],
        ]);

        foreach ($this->keys as $key) {
            Setting::put($key, (string) ($data[$key] ?? ''));
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
