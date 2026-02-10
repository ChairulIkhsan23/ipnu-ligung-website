<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\HasNomorAnggota;

class ManagementStructure extends Model
{
    use HasNomorAnggota;
    
    protected $fillable = [
        'nomor_anggota',
        'name',
        'position',
        'photo',
        'alamat',
        'no_telp',
        'motto',
        'start_year',
        'end_year',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Generate nomor anggota otomatis saat create
            if (!$model->nomor_anggota) {
                $model->nomor_anggota = self::generateNomorAnggota($model->position);
            }
        });
    }
}
