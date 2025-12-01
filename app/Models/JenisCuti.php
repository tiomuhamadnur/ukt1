<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JenisCuti extends Model
{
    use SoftDeletes;

    protected $table = 'jenis_cuti';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'jenis_id');
    }
}
