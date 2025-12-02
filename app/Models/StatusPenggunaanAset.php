<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StatusPenggunaanAset extends Model
{
    use SoftDeletes;

    protected $table = 'status_penggunaan_aset';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    // public function penggunaan_asets()
    // {
    //     return $this->hasMany(PenggunaanAset::class, 'status_penggunaan_aset_id');
    // }
}
