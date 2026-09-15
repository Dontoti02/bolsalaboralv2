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

class BolsaLaboralApplicationsSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
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
        return 'Detalle de Postulaciones';
    }

    public function array(): array
    {
        $roleLabel = $this->type === 'graduate' ? 'EGRESADOS' : 'ESTUDIANTES';
        $exportDate = date('d/m/Y H:i:s');

        $rows = [
            ["DETALLE DE POSTULACIONES A OFERTAS LABORALES - {$roleLabel}"],
            ["Fecha de emisión: {$exportDate} | Registro cronológico por postulación individual"],
            [],
            [
                'N°',
                'Tipo Usuario',
                'DNI / Documento',
                'Postulante',
                'Correo Electrónico',
                'Teléfono / Celular',
                'Programa de Estudio',
                'Oferta Laboral',
                'Empresa Empleadora',
                'Estado de Postulación',
                'Fecha de Postulación',
                'Retroalimentación / Feedback'
            ],
        ];

        $index = 1;
        $totalFound = 0;

        foreach ($this->users as $u) {
            $person = $u['person'] ?? [];
            $apps = $u['applications'] ?? [];

            foreach ($apps as $app) {
                $totalFound++;
                $rows[] = [
                    $index++,
                    $this->type === 'graduate' ? 'Egresado' : 'Estudiante',
                    $person['document_number'] ?? '-',
                    $person['names'] ?? '-',
                    $u['email'] ?? '-',
                    $person['phone'] ?? '-',
                    $person['study_program'] ?? 'Sin asignar',
                    $app['offer_title'] ?? 'Oferta',
                    $app['company_name'] ?? 'Empresa',
                    mb_strtoupper($app['status_label'] ?? 'POSTULADO'),
                    $app['created_at'] ?? '-',
                    $app['feedback'] ?? 'Sin observaciones registradas',
                ];
            }
        }

        if ($totalFound === 0) {
            $rows[] = [
                '-',
                '-',
                '-',
                'No se registran postulaciones para los criterios seleccionados',
                '-',
                '-',
                '-',
                '-',
                '-',
                '-',
                '-',
                '-'
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = count($this->array());
        $highestRow = max($rowCount, 5);

        // Merge title rows
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');

        // Borders
        $sheet->getStyle("A4:L{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('D1D5DB'));

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 15, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '006B60']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4B5563']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F4F6']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '008575']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // N°
            'B' => 13,  // Tipo
            'C' => 15,  // DNI
            'D' => 30,  // Postulante
            'E' => 30,  // Email
            'F' => 16,  // Teléfono
            'G' => 28,  // Programa
            'H' => 30,  // Oferta
            'I' => 28,  // Empresa
            'J' => 22,  // Estado
            'K' => 18,  // Fecha
            'L' => 35,  // Feedback
        ];
    }
}
