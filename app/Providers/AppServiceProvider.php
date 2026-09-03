<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class, fn () => new CartService());
    }

    public function boot(): void
    {
        // Jaring pengaman: di production (di belakang proxy Railway), paksa semua
        // URL yang di-generate memakai https supaya tidak ada Mixed Content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Bagikan data yang dibutuhkan semua halaman (navbar, footer, banner).
        // Defensif: kalau DB/tabel settings belum siap (mis. sebelum migrasi atau
        // saat render halaman error), pakai default supaya halaman tetap tampil.
        View::composer('*', function ($view) {
            $cartCount = 0;
            $settings = [
                'store_name' => 'KELAM',
                'store_tagline' => 'Kelam. Bukan untuk semua.',
                'announcement' => '',
                'instagram' => '@kelam.id',
                'contact_email' => 'halo@kelam.id',
                'contact_phone' => '',
                'free_shipping_min' => 500000,
            ];

            try {
                $cartCount = app(CartService::class)->count();
                $settings = [
                    'store_name' => Setting::get('store_name', 'KELAM'),
                    'store_tagline' => Setting::get('store_tagline', 'Kelam. Bukan untuk semua.'),
                    'announcement' => Setting::get('announcement', ''),
                    'instagram' => Setting::get('instagram', '@kelam.id'),
                    'contact_email' => Setting::get('contact_email', 'halo@kelam.id'),
                    'contact_phone' => Setting::get('contact_phone', ''),
                    'free_shipping_min' => (int) Setting::get('free_shipping_min', '500000'),
                ];
            } catch (\Throwable $e) {
                // diamkan — pakai default di atas
            }

            $view->with('globalCartCount', $cartCount);
            $view->with('globalSettings', $settings);
        });
    }
}
