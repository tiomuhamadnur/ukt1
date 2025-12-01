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

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function unit_kerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function tims()
    {
        return $this->hasMany(Tim::class, 'seksi_id');
    }

    public function kinerjas()
    {
        return $this->hasMany(Kinerja::class, 'seksi_id');
    }
}
