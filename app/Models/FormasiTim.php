<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FormasiTim extends Model
{
    use SoftDeletes;

    protected $table = 'formasi_tim';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function tim()
    {
        return $this->belongsTo(Tim::class);
    }

    public function pulau()
    {
        return $this->belongsTo(Pulau::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function koordinator()
    {
        return $this->belongsTo(User::class);
    }
}
