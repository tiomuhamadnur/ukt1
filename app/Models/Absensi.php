<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Absensi extends Model
{
    use SoftDeletes;

    protected $table = 'absensi';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis_absensi()
    {
        return $this->belongsTo(JenisAbsensi::class);
    }

    public function diketahui_oleh()
    {
        return $this->belongsTo(User::class, 'diketahui_oleh_id');
    }

    public function disetujui_oleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh_id');
    }

    public function status_absensi()
    {
        return $this->belongsTo(StatusAbsensi::class);
    }
}
