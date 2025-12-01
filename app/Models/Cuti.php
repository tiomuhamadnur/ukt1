<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Cuti extends Model
{
    use SoftDeletes;

    protected $table = 'cuti';

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

    public function jenis_cuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    public function diketahui_oleh()
    {
        return $this->belongsTo(User::class, 'diketahui_oleh_id');
    }

    public function disetujui_oleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh_id');
    }

    public function status_cuti()
    {
        return $this->belongsTo(StatusCuti::class);
    }
}
