<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'user_role',
        'phone_number',
        'registered_date',
        'last_login_time',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Full name accessor for Jetstream / Fortify forms that use `name`.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->first_name} {$this->last_name}"),
            set: function (?string $value) {
                $parts = preg_split('/\s+/', trim((string) $value), 2);
                $this->attributes['first_name'] = $parts[0] ?? '';
                $this->attributes['last_name'] = $parts[1] ?? '';
            },
        );
    }

    /**
     * Alias `role` to `user_role` for existing application code.
     */
    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user_role,
            set: fn (?string $value) => ['user_role' => $value],
        );
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'registered_date' => 'datetime',
            'last_login_time' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
