<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Aset extends Model
{
    use SoftDeletes;

    protected $table = 'aset';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function tipe_aset()
    {
        return $this->belongsTo(TipeAset::class);
    }

    public function fasilitas_asets()
    {
        return $this->hasMany(FasilitasAset::class, 'aset_id');
    }

    public function pic_asets()
    {
        return $this->hasMany(PicAset::class, 'aset_id');
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'aset_id');
    }
}
