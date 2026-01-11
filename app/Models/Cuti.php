<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getFormattedTanggalAwalAttribute()
    {
        return Carbon::parse($this->tanggal_awal)
                ->locale('id')
                ->translatedFormat('d F Y');
    }

    public function getFormattedTanggalAkhirAttribute()
    {
        return Carbon::parse($this->tanggal_akhir)
                ->locale('id')
                ->translatedFormat('d F Y');
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
