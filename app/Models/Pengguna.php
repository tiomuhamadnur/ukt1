<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pengguna extends Model
{
    use SoftDeletes;

    protected $table = 'pengguna';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function pulau()
    {
        return $this->belongsTo(Pulau::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'pengguna_id');
    }
}
