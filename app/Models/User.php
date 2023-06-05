<?php

namespace App\Models;

use App\Notifications\UserResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function isSubscribed()
    {
        return $this->subscription;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'username',
        'email',
        'mobile',
        'address',
        'avatar',
        'password',
        'google2fa_status',
        'google2fa_secret',
        'is_viewed',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'address' => 'object',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Send Password Reset Notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    /**
     * Send Email Verification Notification.
     */
    public function sendEmailVerificationNotification()
    {
        if (settings('actions')->email_verification_status) {
            $this->notify(new VerifyEmailNotification());
        }
    }

    /**
     * Relationships
     */
    public function logs()
    {
        return $this->hasMany(UserLog::class);
    }

    public function socialProviders()
    {
        return $this->hasMany(SocialProvider::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function generated_images()
    {
        return $this->hasMany(GeneratedImage::class);
    }
}