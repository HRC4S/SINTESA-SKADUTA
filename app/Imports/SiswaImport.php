<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\DetailSiswa;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER);

        // Pakai transaksi biar aman, kalau 1 gagal, semua rollback
        return DB::transaction(function () use ($row) {
            // --- 1️⃣ Buat data siswa utama
            $siswa = Siswa::updateOrCreate(
                ['nis' => $row['nis']],
                [
                    'nisn'           => $row['nisn'] ?? null,
                    'nama_lengkap'   => $row['nama_lengkap'] ?? null,
                    'jenis_kelamin'  => $row['jenis_kelamin'] ?? null,
                    'email'          => $row['email'] ?? null,
                    'no_whatsapp'    => $row['no_whatsapp'] ?? null,
                    'rombel'         => $row['rombel'] ?? null,
                    'jurusan'        => $row['jurusan'] ?? null,
                    'tempat_lahir'   => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir'  => $this->parseDate($row['tanggal_lahir'] ?? null),
                    'agama'          => $row['agama'] ?? null,
                    'alamat_lengkap' => $row['alamat_lengkap'] ?? null,
                    'password'       => Hash::make('siswa123'),
                ]
            );

            // --- 2️⃣ Buat detail siswa (relasi by nis)
            DetailSiswa::updateOrCreate(
                ['nis' => $row['nis']],
                [
                    'cita_cita'      => $row['cita_cita'] ?? null,
                    'hobi'           => $row['hobi'] ?? null,
                    'berat_badan'    => $row['berat_badan'] ?: null,
                    'tinggi_badan'   => $row['tinggi_badan'] ?: null,
                    'anak_ke'        => $row['anak_ke'] ?: null,
                    'jumlah_saudara' => $row['jumlah_saudara'] ?: null,
                    'tinggal_dengan' => $row['tinggal_dengan'] ?? null,
                    'jarak_rumah'    => $row['jarak_rumah'] ?: null,
                    'waktu_tempuh'   => $row['waktu_tempuh'] ?: null,
                    'transportasi'   => $row['transportasi'] ?: null,
                    'nama_jalan'     => $row['nama_jalan'] ?? null,
                    'rt'             => $row['rt'] ?: null,
                    'rw'             => $row['rw'] ?: null,
                    'dusun'          => $row['dusun'] ?? null,
                    'desa'           => $row['desa_kelurahan'] ?? null,
                    'kode_pos'       => $row['kode_pos'] ?: null,
                ]
            );

            // --- 3️⃣ Buat data orang tua (relasi by nis)
            OrangTua::updateOrCreate(
                ['nis' => $row['nis']],
                [
                    // 🔹 Data Ayah
                    'nama_ayah'          => $row['nama_ayah'] ?? null,
                    'nik_ayah'           => $row['nik_ayah'] ?? null,
                    'tahun_lahir_ayah'   => $row['tahun_lahir_ayah'] ?? null,
                    'pendidikan_ayah'    => $row['pendidikan_ayah'] ?? null,
                    'pekerjaan_ayah'     => $row['pekerjaan_ayah'] ?? null,
                    'penghasilan_ayah'   => $row['penghasilan_ayah'] ?? null,
                    'status_hidup_ayah'  => $row['status_hidup_ayah'] ?? null,
                    'no_telp_ayah'       => $row['no_telp_ayah'] ?? null,

                    // 🔹 Data Ibu
                    'nama_ibu'           => $row['nama_ibu'] ?? null,
                    'nik_ibu'            => $row['nik_ibu'] ?? null,
                    'tahun_lahir_ibu'    => $row['tahun_lahir_ibu'] ?? null,
                    'pendidikan_ibu'     => $row['pendidikan_ibu'] ?? null,
                    'pekerjaan_ibu'      => $row['pekerjaan_ibu'] ?? null,
                    'penghasilan_ibu'    => $row['penghasilan_ibu'] ?? null,
                    'status_hidup_ibu'   => $row['status_hidup_ibu'] ?? null,
                    'no_telp_ibu'        => $row['no_telp_ibu'] ?? null,

                    // 🔹 Data Wali
                    'nama_wali'          => $row['nama_wali'] ?? null,
                    'nik_wali'           => $row['nik_wali'] ?? null,
                    'tahun_lahir_wali'   => $row['tahun_lahir_wali'] ?? null,
                    'pendidikan_wali'    => $row['pendidikan_wali'] ?? null,
                    'pekerjaan_wali'     => $row['pekerjaan_wali'] ?? null,
                    'penghasilan_wali'   => $row['penghasilan_wali'] ?? null,
                    'status_hidup_wali'  => $row['status_hidup_wali'] ?? null,
                    'no_telp_wali'       => $row['no_telp_wali'] ?? null,
                ]
            );

            return $siswa;
        });
    }

    private function parseDate($value)
    {
        if (!$value) return null;

        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        try {
            return date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            '*.nis'           => ['required'],
            '*.nama_lengkap'  => ['required'],
            '*.email'         => ['nullable', 'email'],
        ];
    }
}
