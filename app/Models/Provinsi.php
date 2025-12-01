<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Provinsi extends Model
{
    use SoftDeletes;

    protected $table = 'provinsi';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function kotas()
    {
        return $this->hasMany(Kota::class, 'provinsi_id');
    }
}
