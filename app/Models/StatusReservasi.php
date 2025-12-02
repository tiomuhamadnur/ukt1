<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StatusReservasi extends Model
{
    use SoftDeletes;

    protected $table = 'status_reservasi';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'status_reservasi_id');
    }
}
