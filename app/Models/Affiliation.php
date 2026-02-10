<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    protected $fillable = [
        'name',
        'description',
        'logo',
        'external_url',
        'legal_document',
    ];

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getDocumentUrlAttribute()
    {
        return $this->legal_document ? asset('storage/' . $this->legal_document) : null;
    }
}
