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

class PresensiBulananExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected string $rombel;
    protected int $bulan;
    protected int $tahun;
    protected $siswaSekelas;
    protected array $tanggalList;
    protected array $grid;
    protected array $totalPerSiswa;

    public function __construct(string $rombel, int $bulan, int $tahun, $siswaSekelas, array $tanggalList, array $grid, array $totalPerSiswa)
    {
        $this->rombel = $rombel;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->siswaSekelas = $siswaSekelas;
        $this->tanggalList = $tanggalList;
        $this->grid = $grid;
        $this->totalPerSiswa = $totalPerSiswa;
    }

    public function headings(): array
    {
        $headings = ['No', 'NIS', 'Nama Siswa'];

        foreach ($this->tanggalList as $t) {
            $headings[] = (string) $t;
        }

        $headings[] = 'Hadir';
        $headings[] = 'Sakit';
        $headings[] = 'Izin';
        $headings[] = 'Alpa';

        return $headings;
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;

        $singkatan = ['hadir' => 'H', 'sakit' => 'S', 'izin' => 'I', 'alpa' => 'A'];

        foreach ($this->siswaSekelas as $s) {
            $row = [$no++, $s->nis, $s->nama_lengkap];

            foreach ($this->tanggalList as $t) {
                $status = $this->grid[$s->nis][$t] ?? null;
                $row[] = $status ? $singkatan[$status] : '';
            }

            $total = $this->totalPerSiswa[$s->nis];
            $row[] = $total['hadir'];
            $row[] = $total['sakit'];
            $row[] = $total['izin'];
            $row[] = $total['alpa'];

            $rows[] = $row;
        }

        return $rows;
    }

    public function title(): string
    {
        $namaBulan = \Carbon\Carbon::create()->month($this->bulan)->translatedFormat('F');
        return substr($this->rombel . ' - ' . $namaBulan . ' ' . $this->tahun, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // Header row bold + background biru + center + putih
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '123B6B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Semua sel data: center kolom tanggal & total, border tipis
        $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
        ]);
        $sheet->getStyle("A2:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D2:{$lastCol}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
