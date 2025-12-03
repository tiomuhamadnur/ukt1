<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UnitKerja extends Model
{
    use SoftDeletes;

    protected $table = 'unit_kerja';

    protected $guarded = [];

    protected $relationChecks = [
        'seksis',
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

    public function seksis()
    {
        return $this->hasMany(Seksi::class, 'unit_kerja_id');
    }

    public function kinerjas()
    {
        return $this->hasMany(Kinerja::class, 'unit_kerja_id');
    }
}
