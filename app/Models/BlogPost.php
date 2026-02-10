<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'meta_title',
        'meta_description',
        'views',
        'views_today',
        'views_this_week',
        'views_this_month',
        'last_viewed_at',
        'view_velocity',
        'og_image',
        'category_id',
        'author_id',
        'published_at',
        'status',
        'is_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'views' => 'integer',
        'views_today' => 'integer',
        'views_this_week' => 'integer',
        'views_this_month' => 'integer',
        'last_viewed_at' => 'datetime',
        'view_velocity' => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeLatestPosts($query, $limit = 3)
    {
        return $query->published()
                    ->latest('published_at')
                    ->take($limit);
    }

    public function viewLogs(): HasMany
    {
        return $this->hasMany(ViewLog::class);
    }

    public function getRecentViewsAttribute()
    {
        return $this->viewLogs()
            ->where('created_at', '>', now()->subMinutes(5))
            ->count();
    }

    public function getHourlyViewsAttribute()
    {
        return $this->viewLogs()
            ->where('created_at', '>', now()->subHour())
            ->count();
    }
}
