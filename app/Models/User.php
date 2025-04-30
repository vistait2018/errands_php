<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class);
    }


    public function bvn(): HasOne
    {
        return $this->hasOne(Bvn::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class,'receiver_id');
    }

    public function level(): HasOne
    {
        return $this->hasOne(Level::class);
    }

    public function nin(): HasOne
    {
        return $this->hasOne(Nin::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class,'receiver_id');
    }

    public function sendEmailVerificationNotification()
    {
        Log::info('Sending email verification to ' . $this->email);
        $this->notify(new VerifyEmailNotification);
    }
}
