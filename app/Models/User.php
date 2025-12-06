<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Mchev\Banhammer\Traits\Bannable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles, Bannable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'uuid',
        'nik',
        'nip',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'photo',
        'ttd',
        'bio',
        'is_plt',
        'user_type_id',
        'gender_id',
        'pulau_id',
        'kelurahan_id',
        'jabatan_id',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

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

    public function formasi_tim()
    {
        return $this->hasOne(FormasiTim::class, 'user_id')
                    ->where('periode', now()->year);
    }

    public function konfigurasi_cuti()
    {
        return $this->hasOne(KonfigurasiCuti::class, 'user_id')
                    ->where('periode', now()->year);
    }

    public function user_type()
    {
        return $this->belongsTo(UserType::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function pulau()
    {
        return $this->belongsTo(Pulau::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'user_id');
    }

    public function diketahui_oleh_cutis()
    {
        return $this->hasMany(Cuti::class, 'diketahui_oleh_id');
    }

    public function disetujui_oleh_cutis()
    {
        return $this->hasMany(Cuti::class, 'disetujui_oleh_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    public function kinerjas()
    {
        return $this->hasMany(Kinerja::class, 'user_id');
    }
}
