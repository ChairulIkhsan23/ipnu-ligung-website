<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_post_id',
        'session_id',
        'ip_address',
        'user_agent',
        'referrer',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}