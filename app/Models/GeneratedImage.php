<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;

class GeneratedImage extends Model
{
    use HasFactory;

    public const VISIBILITY_PUBLIC = 1;
    public const VISIBILITY_PRIVATE = 0;

    public function scopePublic($query)
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    public function canDownload()
    {
        $user = Auth::user();
        if (!$user) {
            if (Payment::where(['image_id' => $this->id, 'status' => Payment::STATUS_PAID, 'ip' => ipinfo()->ip])->exists()) return true;
            return false;
        }
        if ($user->subscription->isActive() == false) {
            if (Payment::where(['user_id' => $user->id, 'image_id' => $this->id, 'status' => Payment::STATUS_PAID])->exists()) return true;
            return false;
        }
        return true;
    }

    public function discountImages() {
        $imagesGeneratedTogether = GeneratedImage::where('generated_id', $this->generated_id)->whereNotNull('generated_id')->get();
        return $imagesGeneratedTogether;
    }

    public function price() {
        if ($this->canDownload())
            return 0;
        $setting = Settings::selectSettings("image");
        $imagesGeneratedTogether = GeneratedImage::where('generated_id', $this->generated_id)->whereNotNull('generated_id')->get();

        foreach ($imagesGeneratedTogether as $image) {
            if ($image->canDownload())
                return $setting->discountPrice;
        }

        return $setting->originalPrice;
    }

    public function isPublic()
    {
        return $this->visibility == self::VISIBILITY_PUBLIC;
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', self::VISIBILITY_PRIVATE);
    }

    public function isPrivate()
    {
        return $this->visibility == self::VISIBILITY_PRIVATE;
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expiry_at')
            ->where('expiry_at', '<', Carbon::now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($query) {
            $query->whereNull('expiry_at')
                ->orWhere('expiry_at', '>', Carbon::now());
        });
    }

    public function scopeUsers($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeGuests($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'storage_provider_id',
        'ip',
        'prompt',
        'size',
        'filename',
        'path',
        'expiry_at',
        'visibility',
        'generated_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expiry_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function storageProvider()
    {
        return $this->belongsTo(StorageProvider::class);
    }
}