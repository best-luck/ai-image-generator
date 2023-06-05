<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    use HasFactory;

    public $timestamps = false;

    private const UNDISABLE_TEMPLATES = [
        'password_reset',
        'email_verification',
    ];

    public function undisable()
    {
        return in_array($this->alias, self::UNDISABLE_TEMPLATES);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'lang',
        'subject',
        'body',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'shortcodes' => 'object',
    ];

    /**
     * Relationships
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'code');
    }
}
