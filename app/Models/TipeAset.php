<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TipeAset extends Model
{
    use SoftDeletes;

    protected $table = 'tipe_aset';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function asets()
    {
        return $this->hasMany(Aset::class, 'tipe_aset_id');
    }
}
