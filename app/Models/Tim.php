<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tim extends Model
{
    use SoftDeletes;

    protected $table = 'tim';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class);
    }

    public function formasi_tims()
    {
        return $this->hasMany(FormasiTim::class, 'tim_id');
    }
}
