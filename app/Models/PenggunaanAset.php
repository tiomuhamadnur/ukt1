<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PenggunaanAset extends Model
{
    use SoftDeletes;

    protected $table = 'penggunaan_aset';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function status_penggunaan_aset()
    {
        return $this->belongsTo(StatusPenggunaanAset::class);
    }
}
