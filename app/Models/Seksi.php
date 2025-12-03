<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Seksi extends Model
{
    use SoftDeletes;

    protected $table = 'seksi';

    protected $guarded = [];

    protected $relationChecks = [
        'tims',
        'kinerjas'
    ];

    protected static function booted()
    {
        static::deleting(function ($model) {
            foreach ($model->relationChecks as $relation) {
                if ($model->$relation()->exists()) {
                    throw new \Exception("Gagal. Masih digunakan di relasi yang lain! <br> Silakan hubungi Administrator");
                }
            }
        });
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function unit_kerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function tims()
    {
        return $this->hasMany(Tim::class, 'seksi_id');
    }

    public function kegiatans()
    {
        return $this->hasMany(Tim::class, 'seksi_id');
    }

    public function kinerjas()
    {
        return $this->hasMany(Kinerja::class, 'seksi_id');
    }
}
