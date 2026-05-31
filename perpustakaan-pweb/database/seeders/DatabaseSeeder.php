<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 akun admin default — tidak bisa dibuat lewat register
        User::firstOrCreate(
            ['email' => 'admin@perpustakaan.ac.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        $this->call([BukuSeeder::class]);
    }
}
