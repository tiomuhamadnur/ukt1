<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KonfigurasiCuti extends Model
{
    use SoftDeletes;

    protected $table = 'konfigurasi_cuti';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function jenis_cuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
