<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kelurahan extends Model
{
    use SoftDeletes;

    protected $table = 'kelurahan';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function pulaus()
    {
        return $this->hasMany(Pulau::class, 'kelurahan_id');
    }
}
