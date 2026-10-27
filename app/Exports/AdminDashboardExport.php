<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AdminDashboardExport implements FromArray, WithStyles, WithColumnWidths, WithDrawings
{
    protected array $data;
    protected ?string $logoPath;
    protected string $institutionName;

    public function __construct(
        array $data,
        ?string $logoPath = null,
        string $institutionName = 'Bolsa Laboral Institucional'
    ) {
        $this->data = $data;
        $this->logoPath = $logoPath;
        $this->institutionName = $institutionName;
    }

    protected function hasLogo(): bool
    {
        return !empty($this->logoPath) && is_file($this->logoPath) && is_readable($this->logoPath);
    }

    public function drawings(): array
    {
        if (!$this->hasLogo()) {
            return [];
        }

        try {
            $drawing = new Drawing();
            $drawing->setName('Logo Institucional');
            $drawing->setDescription($this->institutionName);
            $drawing->setPath($this->logoPath);
            $drawing->setHeight(44);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(6);

            return [$drawing];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function array(): array
    {
        $exportDate = date('d/m/Y H:i:s');
        $instUpper = mb_strtoupper($this->institutionName);

        $rows = [
            ["{$instUpper} — REPORTE DE RESUMEN DE ADMINISTRACIÓN"],
            ["Institución: {$this->institutionName} | Fecha de emisión: {$exportDate}"],
            ['Indicador', 'Valor'],
            ['Total usuarios', $this->data['totalUsers']],
            ['Empresas pendientes', $this->data['pendingCompanies']],
            ['Ofertas activas', $this->data['activeOffers']],
            ['Postulaciones totales', $this->data['totalApplications']],
            [],
            ['Registros de Empresas Recientes'],
            ['Nombre de Empresa', 'RUC', 'Fecha', 'Estado'],
        ];

        foreach ($this->data['recentCompanies'] as $company) {
            $rows[] = [
                $company->name,
                $company->ruc,
                $company->formatted_date,
                $company->status_label
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->getRowDimension(1)->setRowHeight(34);
        $sheet->getRowDimension(2)->setRowHeight(22);

        $indent = $this->hasLogo() ? 9 : 1;

        return [
            // Title row
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '002741']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => $indent],
            ],
            // Subtitle row
            2 => [
                'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4B5563']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => $indent],
            ],
            // Indicators Header
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '002741']]],
            // Recent Companies Section Header
            9 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '006B60']]],
            // Recent Companies Headers
            10 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '006B60']]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 20,
            'C' => 20,
            'D' => 20,
        ];
    }
}
