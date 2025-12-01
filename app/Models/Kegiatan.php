<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kegiatan extends Model
{
    use SoftDeletes;

    protected $table = 'kegiatan';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function kinerjas()
    {
        return $this->hasMany(Kinerja::class, 'kegiatan_id');
    }
}
