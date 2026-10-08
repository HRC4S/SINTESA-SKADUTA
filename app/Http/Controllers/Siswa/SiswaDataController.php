<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DetailSiswa;
use Illuminate\Support\Facades\Auth;

class SiswaDataController extends Controller
{
    // =======================
    //  SHOW
    // =======================
    public function show()
{
    $siswa = Auth::guard('siswa')->user();
    return view('siswa.datasiswa.show', compact('siswa'));
}


    // =======================
    //  FORM EDIT
    // =======================
    public function edit($nis)
    {
        $siswa = Siswa::with('detailSiswa')->where('nis', $nis)->firstOrFail();

        return view('siswa.datasiswa.edit', compact('siswa'));
    }

    // =======================
    //  UPDATE DATA
    // =======================
    public function update(Request $request, $nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        // ===== VALIDASI =====
        $request->validate([
            'nisn'          => 'nullable|numeric',
            'nama_lengkap'  => 'required|string',
            'jenis_kelamin' => 'required|string',
            'email'         => 'nullable|email',
            'no_whatsapp'   => 'nullable|string',
            'rombel'        => 'nullable|string',
            'jurusan'       => 'nullable|string',
            'tempat_lahir'  => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'agama'         => 'nullable|string',
            'nama_ortu'     => 'nullable|string',
            'alamat'        => 'nullable|string',

            // Detail siswa
            'cita_cita'     => 'nullable|string',
            'hobi'          => 'nullable|string',
            'berat_badan'   => 'nullable|numeric',
            'tinggi_badan'  => 'nullable|numeric',
            'anak_ke'       => 'nullable|numeric',
            'jumlah_saudara'=> 'nullable|numeric',
            'tinggal_dengan'=> 'nullable|string',
            'jarak_rumah'   => 'nullable|string',
            'waktu_tempuh'  => 'nullable|string',
            'transportasi'  => 'nullable|string',

            // Alamat detail
            'nama_jalan'    => 'nullable|string',
            'rt'            => 'nullable|string',
            'rw'            => 'nullable|string',
            'dusun'         => 'nullable|string',
            'desa'          => 'nullable|string',
            'kode_pos'      => 'nullable|string',

            // Foto
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // =======================
        //  UPDATE FOTO
        // =======================
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/foto_siswa'), $namaFile);

            // hapus foto lama jika ada
            if ($siswa->foto && file_exists(public_path('uploads/foto_siswa/' . $siswa->foto))) {
                unlink(public_path('uploads/foto_siswa/' . $siswa->foto));
            }

            $siswa->foto = $namaFile;
        }

        // =======================
        //  UPDATE DATA UTAMA
        // =======================
        $siswa->update([
            'nisn'          => $request->nisn,
            'nama_lengkap'  => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'email'         => $request->email,
            'no_whatsapp'   => $request->no_whatsapp,
            'rombel'        => $request->rombel,
            'jurusan'       => $request->jurusan,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama'         => $request->agama,
            'nama_ortu'     => $request->nama_ortu,
            'alamat'        => $request->alamat,
        ]);

        // =======================
        //  UPDATE DETAIL SISWA
        // =======================
        $detail = DetailSiswa::where('nis', $nis)->first();

        if ($detail) {
            $detail->update([
                'cita_cita'      => $request->cita_cita,
                'hobi'           => $request->hobi,
                'berat_badan'    => $request->berat_badan,
                'tinggi_badan'   => $request->tinggi_badan,
                'anak_ke'        => $request->anak_ke,
                'jumlah_saudara' => $request->jumlah_saudara,
                'tinggal_dengan' => $request->tinggal_dengan,
                'jarak_rumah'    => $request->jarak_rumah,
                'waktu_tempuh'   => $request->waktu_tempuh,
                'transportasi'   => $request->transportasi,

                // alamat detail
                'nama_jalan'     => $request->nama_jalan,
                'rt'             => $request->rt,
                'rw'             => $request->rw,
                'dusun'          => $request->dusun,
                'desa'           => $request->desa,
                'kode_pos'       => $request->kode_pos,
            ]);
        }

        return redirect()->route('siswa.profil.show', $nis)
                         ->with('success', 'Biodata berhasil diperbarui!');
    }
}
