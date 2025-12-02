<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reservasi extends Model
{
    use SoftDeletes;

    protected $table = 'reservasi';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function status_reservasi()
    {
        return $this->belongsTo(StatusReservasi::class);
    }
}
