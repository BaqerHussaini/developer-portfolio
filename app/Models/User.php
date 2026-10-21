<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function maskedEmail(): string
    {
        $email = (string) $this->email;
        $at = strrpos($email, '@');

        if ($at === false) {
            return '••••••';
        }

        $local = Str::substr($email, 0, $at);
        $domain = Str::substr($email, $at);
        $length = Str::length($local);

        if ($length < 2) {
            return '••••'.$domain;
        }

        return Str::substr($local, 0, 1)
            .str_repeat('•', max(3, $length - 2))
            .Str::substr($local, -1)
            .$domain;
    }
}
