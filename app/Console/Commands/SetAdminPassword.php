<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetAdminPassword extends Command
{
    protected $signature = 'admin:password
                            {--email= : The admin account to update}
                            {--password= : The new password (prompted if omitted)}';

    protected $description = 'Set or change the password for the portfolio admin account';

    public function handle(): int
    {
        $email = $this->option('email') ?: env('ADMIN_EMAIL', 'engineer.baqer.hussaini@gmail.com');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No admin account found for {$email}.");

            return self::FAILURE;
        }

        $password = $this->option('password') ?: $this->secret('New password');

        if (! is_string($password) || strlen($password) < 8) {
            $this->error('The password must be at least 8 characters long.');

            return self::FAILURE;
        }

        $user->forceFill(['password' => Hash::make($password)])->save();

        $this->info("Password updated for {$user->email}.");

        return self::SUCCESS;
    }
}
