<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuPelajarSetting extends Model
{
    protected $table = 'kartu_pelajar_settings';

    protected $fillable = [
        'angkatan',
        'bulan_terbit',
        'tahun_terbit',
        'nama_kepsek',
        'nip_kepsek',
        'tanda_tangan_path',
        'template_style',
    ];

    protected $casts = [
        'angkatan' => 'integer',
        'bulan_terbit' => 'integer',
        'tahun_terbit' => 'integer',
    ];

    public static function forAngkatan(int $angkatan): self
    {
        $setting = static::query()->firstOrNew(['angkatan' => $angkatan]);

        if (! $setting->exists) {
            $setting->fill([
                'bulan_terbit' => 7,
                'tahun_terbit' => $angkatan,
                'nama_kepsek' => 'Drs. Agus Waluyo, M.Eng.',
                'nip_kepsek' => '196512271994121002',
                'template_style' => $angkatan >= now()->year ? 'baru' : 'standar',
            ]);
        }

        return $setting;
    }

    public static function forStudent(Siswa $siswa): self
    {
        if (preg_match('/^(\d{2})/', trim((string) $siswa->nis), $matches) === 1) {
            return static::forAngkatan(2000 + (int) $matches[1]);
        }

        return static::forAngkatan(now()->year);
    }

    public function getNamaBulanAttribute(): string
    {
        return \Carbon\Carbon::create()->month($this->bulan_terbit)->locale('id')->translatedFormat('F');
    }

    public static function angkatanSiswa(): array
    {
        return Siswa::query()
            ->pluck('nis')
            ->map(function ($nis) {
                return preg_match('/^(\d{2})/', trim((string) $nis), $matches) === 1
                    ? 2000 + (int) $matches[1]
                    : null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public static function availableAngkatan(): array
    {
        return collect(static::angkatanSiswa())
            ->merge(static::query()->whereNotNull('angkatan')->pluck('angkatan')->all())
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values()
            ->all();
    }

    public function initializeForAngkatan(): self
    {
        $this->angkatan ??= now()->year;
        $this->bulan_terbit ??= 7;
        $this->tahun_terbit ??= $this->angkatan;
        $this->nama_kepsek ??= 'Drs. Agus Waluyo, M.Eng.';
        $this->nip_kepsek ??= '196512271994121002';
        $this->template_style ??= $this->angkatan >= now()->year ? 'baru' : 'standar';

        return $this;
    }
}
