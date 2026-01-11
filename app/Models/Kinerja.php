<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Kinerja extends Model
{
    use SoftDeletes;

    protected $table = 'kinerja';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getFormattedTanggalAttribute()
    {
        return Carbon::parse($this->tanggal)
                ->locale('id')
                ->translatedFormat('d F Y');
    }

    public function getHariAttribute()
    {
        return $this->tanggal
            ? Carbon::parse($this->tanggal)->locale('id')->dayName
            : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function unit_kerja()
    {
        return $this->belongsTo(UnitKerja::class);
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class);
    }

    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }

    public function formasi_tim()
    {
        return $this->belongsTo(FormasiTim::class);
    }

    public function pulau()
    {
        return $this->belongsTo(Pulau::class);
    }

    public function kinerja_photos()
    {
        return $this->hasMany(KinerjaPhoto::class, 'kinerja_id');
    }
}
