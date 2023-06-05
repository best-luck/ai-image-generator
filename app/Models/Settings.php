<?php

namespace App\Models;

use App\Http\Methods\UnicodeModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends UnicodeModel
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'object',
    ];

    /**
     * Get settings by key
     */
    public static function selectSettings($key)
    {
        $setting = Settings::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }
        return false;
    }

    /**
     * Update settings from table.
     */
    public static function updateSettings($key, $value)
    {
        $setting = Settings::where('key', $key)->first();
        if ($setting) {
            if (empty(array_diff_key_objects($value, $setting->value))) {
                $setting->value = $value;
                return $setting->save();
            }
        }
        return false;
    }
}