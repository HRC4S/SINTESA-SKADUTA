<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PresensiTotalExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $siswaList;
    protected $rekap;
    protected ?string $rombel;
    protected ?string $dari;
    protected ?string $sampai;

    public function __construct($siswaList, $rekap, ?string $rombel, ?string $dari, ?string $sampai)
    {
        $this->siswaList = $siswaList;
        $this->rekap = $rekap;
        $this->rombel = $rombel;
        $this->dari = $dari;
        $this->sampai = $sampai;
    }

    public function headings(): array
    {
        return ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Total Hari', 'Hadir', 'Sakit', 'Izin', 'Alpa', '% Kehadiran'];
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;

        foreach ($this->siswaList as $s) {
            $r = $this->rekap->get($s->nis);
            $totalHari = $r->total_hari ?? 0;
            $persen = $totalHari > 0 ? round(($r->total_hadir / $totalHari) * 100, 1) : 0;

            $rows[] = [
                $no++,
                $s->nis,
                $s->nama_lengkap,
                $s->rombel,
                $totalHari,
                $r->total_hadir ?? 0,
                $r->total_sakit ?? 0,
                $r->total_izin ?? 0,
                $r->total_alpa ?? 0,
                $totalHari > 0 ? $persen . '%' : '-',
            ];
        }

        return $rows;
    }

    public function title(): string
    {
        $judul = 'Rekap Total' . ($this->rombel ? ' - ' . $this->rombel : ' - Semua Kelas');
        return substr($judul, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '123B6B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
        ]);
        $sheet->getStyle("A2:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:{$lastCol}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
