<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KonfigurasiAbsensiTim extends Model
{
    use SoftDeletes;

    protected $table = 'konfigurasi_absensi_tim';

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

    public function konfigurasi_absensi()
    {
        return $this->belongsTo(KonfigurasiAbsensi::class, 'konfigurasi_absensi_id');
    }

    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id');
    }
}
