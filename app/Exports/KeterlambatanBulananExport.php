<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KeterlambatanBulananExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $siswaList;
    protected array $rekap;
    protected ?string $rombel;
    protected int $bulan;
    protected int $tahun;

    public function __construct($siswaList, array $rekap, ?string $rombel, int $bulan, int $tahun)
    {
        $this->siswaList = $siswaList;
        $this->rekap = $rekap;
        $this->rombel = $rombel;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function headings(): array
    {
        return ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Izin Masuk (Terlambat)', 'Izin Keluar', 'Total'];
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;

        foreach ($this->siswaList as $siswa) {
            $rekap = $this->rekap[$siswa->nis] ?? ['masuk' => 0, 'keluar' => 0];
            $total = $rekap['masuk'] + $rekap['keluar'];

            if ($total === 0) {
                continue;
            }

            $rows[] = [
                $no++,
                $siswa->nis,
                $siswa->nama_lengkap,
                $siswa->rombel,
                $rekap['masuk'],
                $rekap['keluar'],
                $total,
            ];
        }

        return $rows;
    }

    public function title(): string
    {
        $namaBulan = \Carbon\Carbon::create()->month($this->bulan)->translatedFormat('F');
        $judul = 'Rekap Izin - ' . ($this->rombel ?: 'Semua Kelas') . ' - ' . $namaBulan . ' ' . $this->tahun;

        return substr($judul, 0, 31);
    }

    public function styles(Worksheet $sheet): array
    {
        $lastColumn = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '123B6B']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);
        $sheet->getStyle("A2:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:{$lastColumn}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
