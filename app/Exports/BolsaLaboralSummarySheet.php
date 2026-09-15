<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BolsaLaboralSummarySheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected array $users;
    protected string $type;

    public function __construct(array $users, string $type = 'student')
    {
        $this->users = $users;
        $this->type = $type;
    }

    public function title(): string
    {
        return $this->type === 'graduate' ? 'Egresados' : 'Estudiantes';
    }

    public function array(): array
    {
        $roleLabel = $this->type === 'graduate' ? 'EGRESADOS' : 'ESTUDIANTES';
        $exportDate = date('d/m/Y H:i:s');
        $totalCount = count($this->users);

        $rows = [
            ["REPORTE INSTITUCIONAL DE BOLSA LABORAL - {$roleLabel}"],
            ["Fecha de emisión: {$exportDate} | Total de registros evaluados: {$totalCount}"],
            [],
            [
                'N°',
                'Tipo',
                'DNI / Doc',
                'Nombres y Apellidos',
                'Correo Electrónico',
                'Teléfono / Celular',
                'Programa de Estudio',
                'Total Postulaciones',
                'Ofertas Laborales Postuladas',
                'Estado(s) de Postulación',
                'Fecha Registro'
            ],
        ];

        $index = 1;
        foreach ($this->users as $u) {
            $person = $u['person'] ?? [];
            $apps = $u['applications'] ?? [];
            $totalApps = count($apps);

            // Resumen de ofertas
            $offersList = [];
            $statusList = [];

            if ($totalApps > 0) {
                foreach ($apps as $idx => $app) {
                    $itemNum = $idx + 1;
                    $offersList[] = "{$itemNum}. " . ($app['offer_title'] ?? 'Oferta') . ' (' . ($app['company_name'] ?? 'Empresa') . ')';
                    $statusList[] = "{$itemNum}. " . ($app['status_label'] ?? 'Postulado');
                }
                $offersText = implode("\n", $offersList);
                $statusText = implode("\n", $statusList);
            } else {
                $offersText = 'Sin postulaciones registradas';
                $statusText = 'Sin postulaciones';
            }

            $rows[] = [
                $index++,
                $this->type === 'graduate' ? 'Egresado' : 'Estudiante',
                $person['document_number'] ?? '-',
                $person['names'] ?? '-',
                $u['email'] ?? '-',
                $person['phone'] ?? '-',
                $person['study_program'] ?? 'Sin asignar',
                $totalApps,
                $offersText,
                $statusText,
                $u['created_at'] ?? '-',
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = max(count($this->users) + 4, 5);

        // Merge title rows
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');

        // Text wrap on offers and status columns
        $sheet->getStyle("I5:J{$highestRow}")->getAlignment()->setWrapText(true);

        // Borders for table data
        $sheet->getStyle("A4:K{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('D1D5DB'));

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 15, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '002741']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4B5563']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F3D5E']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // N°
            'B' => 13,  // Tipo
            'C' => 14,  // DNI
            'D' => 30,  // Nombres
            'E' => 30,  // Correo
            'F' => 16,  // Teléfono
            'G' => 28,  // Programa Estudio
            'H' => 14,  // Total Apps
            'I' => 38,  // Ofertas
            'J' => 22,  // Estados
            'K' => 16,  // Fecha Registro
        ];
    }
}
