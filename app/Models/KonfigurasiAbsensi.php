<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KonfigurasiAbsensi extends Model
{
    use SoftDeletes;

    protected $table = 'konfigurasi_absensi';

    protected $guarded = [];

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

    public function jenis_absensi()
    {
        return $this->belongsTo(JenisAbsensi::class);
    }
}
