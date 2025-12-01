<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UnitKerja extends Model
{
    use SoftDeletes;

    protected $table = 'unit_kerja';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function seksis()
    {
        return $this->hasMany(Seksi::class, 'unit_kerja_id');
    }
}
