<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'category_id');
    }
}
