<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use Exception;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class SiswaImportController extends Controller
{
    public function import(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:51200', // 50MB
        ]);

        try {
            // Import menggunakan Maatwebsite Excel
            Excel::import(new SiswaImport, $request->file('file'));

            return redirect()
                ->route('admin.datasiswa.index')
                ->with('success', '✅ Data siswa berhasil diimpor ke database.');
        } catch (ExcelValidationException $e) {
            $failures = $e->failures();
            $messages = [];
            foreach ($failures as $failure) {
                $messages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()
                ->back()
                ->with('error', '⚠️ Kesalahan pada data: ' . implode(' | ', $messages));
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
