<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PicAset extends Model
{
    use SoftDeletes;

    protected $table = 'pic_aset';

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
