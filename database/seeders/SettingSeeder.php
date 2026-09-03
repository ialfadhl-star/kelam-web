<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'store_name'        => 'KELAM',
            'store_tagline'     => 'Kelam. Bukan untuk semua.',
            'contact_email'     => 'halo@kelam.id',
            'contact_phone'     => '+62 812-0000-0000',
            'instagram'         => '@kelam.id',
            'announcement'      => 'Gratis ongkir se-Indonesia untuk pembelian min. Rp500.000',
            'free_shipping_min' => '500000',
            'shipping_cost'     => '25000',
            'bank_name'         => 'Bank BCA',
            'bank_account'      => '1234567890',
            'bank_holder'       => 'PT Kelam Indonesia',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::flushCache();
    }
}
