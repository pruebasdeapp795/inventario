<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Administrador',
            'username' => 'administrador',
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('Administrador');
    }
}
