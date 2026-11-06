<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $poinPerKeterlambatan = Pengaturan::where('key', Pengaturan::POIN_PER_KETERLAMBATAN)
            ->value('value') ?? 5;

        return view('admin.pengaturan.index', compact('poinPerKeterlambatan'));
    }

    public function updatePoin(Request $request)
    {
        $validated = $request->validate([
            'poin_per_keterlambatan' => ['required', 'integer', 'min:0', 'max:1000'],
        ]);

        Pengaturan::updateOrCreate(
            ['key' => Pengaturan::POIN_PER_KETERLAMBATAN],
            ['value' => $validated['poin_per_keterlambatan']]
        );

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan poin pelanggaran berhasil disimpan.');
    }
}