<?php

namespace App\Models;

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
