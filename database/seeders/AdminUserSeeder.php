<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'engineer.baqer.hussaini@gmail.com';

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Baqer Hussaini',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'portfolio')),
                'email_verified_at' => now(),
            ]
        );
    }
}
