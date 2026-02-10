<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get all posts for the category
     */
    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    /**
     * Get active categories (with posts)
     */
    public function scopeActive($query)
    {
        return $query->has('posts');
    }

    /**
     * Get inactive categories (without posts)
     */
    public function scopeInactive($query)
    {
        return $query->doesntHave('posts');
    }

    /**
     * Get categories ordered by post count
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit($limit);
    }
}