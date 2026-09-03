<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@kelam.id');

        // Kalau admin sudah ada, jangan diubah / jangan reset password.
        if (User::where('email', $email)->exists()) {
            $this->command->warn("Admin {$email} sudah ada — dilewati (password tidak diubah).");
            return;
        }

        // Password: pakai ADMIN_PASSWORD dari .env kalau di-set, kalau tidak generate acak.
        $plain = env('ADMIN_PASSWORD');
        $generated = false;
        if (empty($plain)) {
            $plain = bin2hex(random_bytes(6)); // 12 char acak
            $generated = true;
        }

        User::create([
            'name'     => 'Admin KELAM',
            'email'    => $email,
            'password' => Hash::make($plain), // di-hash, tidak pernah disimpan plaintext
        ]);

        $this->command->info('====================================================');
        $this->command->info(' Akun admin KELAM berhasil dibuat:');
        $this->command->info('   Email    : ' . $email);
        if ($generated) {
            $this->command->info('   Password : ' . $plain . '   <-- CATAT SEKARANG, hanya ditampilkan sekali');
        } else {
            $this->command->info('   Password : (dari ADMIN_PASSWORD di .env)');
        }
        $this->command->info('====================================================');
    }
}
