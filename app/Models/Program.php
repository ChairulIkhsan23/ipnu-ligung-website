<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use App\Enums\Visibility;

class Program extends Model
{
    protected $fillable = [
        'title',
        'short_description',
        'description',
        'meta_title',
        'meta_description',
        'category_id',
        'cover_image',
        'status',
        'start_date',
        'end_date',
        'visibility',
        'is_featured',
        'timeline',
        'documentation',
        'person_in_charge',
    ];

    protected $casts = [
        'documentation' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
        'visibility' => Visibility::class,
    ];

    // Relationship
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProgramCategory::class);
    }

    // Accessors
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }

    public function getTimelineUrlAttribute()
    {
        return $this->timeline ? Storage::url($this->timeline) : null;
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['upcoming', 'ongoing']);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', Visibility::PUBLIC);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
                    ->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
                    ->where('start_date', '<=', now())
                    ->where(function ($q) {
                    $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
                    });
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}