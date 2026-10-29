<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\KartuPelajarController;
use App\Models\KartuPelajarSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class KartuPelajarSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_and_updates_settings_for_an_angkatan(): void
    {
        $controller = app(KartuPelajarController::class);
        $settings = [
            'angkatan' => 2026,
            'bulan_terbit' => 7,
            'tahun_terbit' => 2026,
            'nama_kepsek' => 'Nama Kepala Sekolah',
            'nip_kepsek' => '1234567890',
            'template_style' => 'baru',
        ];

        $controller->updatePengaturan(Request::create('/admin/kartupelajar/pengaturan', 'PUT', $settings));

        $this->assertDatabaseHas('kartu_pelajar_settings', [
            'angkatan' => 2026,
            'nama_kepsek' => 'Nama Kepala Sekolah',
            'template_style' => 'baru',
        ]);

        $settings['nama_kepsek'] = 'Nama Kepala Sekolah Baru';
        $controller->updatePengaturan(Request::create('/admin/kartupelajar/pengaturan', 'PUT', $settings));

        $this->assertSame(
            'Nama Kepala Sekolah Baru',
            KartuPelajarSetting::where('angkatan', 2026)->value('nama_kepsek')
        );
        $this->assertSame(1, KartuPelajarSetting::where('angkatan', 2026)->count());
    }
}
