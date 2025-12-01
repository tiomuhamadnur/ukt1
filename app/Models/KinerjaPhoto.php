<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KinerjaPhoto extends Model
{
    use SoftDeletes;

    protected $table = 'kinerja_photo';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function kinerja()
    {
        return $this->belongsTo(Kinerja::class);
    }
}
