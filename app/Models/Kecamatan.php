<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kecamatan extends Model
{
    use SoftDeletes;

    protected $table = 'kecamatan';

    protected $guarded = [];

    protected $relationChecks = [
        'kelurahans',
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

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'kecamatan_id');
    }
}
