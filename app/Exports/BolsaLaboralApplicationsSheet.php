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
        $roleLabel = match($this->type) {
            'graduate' => 'EGRESADOS',
            'student'  => 'ESTUDIANTES',
            default    => 'ESTUDIANTES Y EGRESADOS',
        };
        $exportDate = date('d/m/Y H:i:s');

        $rows = [
            // Row 1: Main Title
            ["DETALLE DE POSTULACIONES A OFERTAS LABORALES - {$roleLabel}"],
            // Row 2: Subtitle
            ["Fecha de emisión: {$exportDate} | Registro cronológico por postulación individual"],
            // Row 3: Table Headers
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

            $userRole = ($u['rol_id'] ?? null) == 5 ? 'Egresado' : 'Estudiante';
            if ($this->type === 'graduate') $userRole = 'Egresado';
            if ($this->type === 'student')  $userRole = 'Estudiante';

            foreach ($apps as $app) {
                $totalFound++;
                $rows[] = [
                    $index++,
                    $userRole,
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
        $highestRow = max($rowCount, 4);

        // Merge title rows
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');

        // Row Heights
        $sheet->getRowDimension(1)->setRowHeight(34);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(3)->setRowHeight(28);

        // Freeze pane at Row 4
        $sheet->freezePane('A4');

        // Text wrap on offers, enterprise, and feedback
        $sheet->getStyle("H4:I{$highestRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("L4:L{$highestRow}")->getAlignment()->setWrapText(true);

        // Alignments
        $sheet->getStyle("A4:L{$highestRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $sheet->getStyle("A4:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D4:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("F4:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G4:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("J4:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("L4:L{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Borders
        $sheet->getStyle("A3:L{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('D1D5DB'));

        // Soft Zebra Striping for alternating data rows
        for ($r = 4; $r <= $highestRow; $r++) {
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:L{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }
        }

        // AutoFilter on Header Row 3
        $sheet->setAutoFilter("A3:L{$highestRow}");

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '006B60']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4B5563']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ],
            3 => [
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
            'B' => 14,  // Tipo
            'C' => 15,  // DNI
            'D' => 30,  // Postulante
            'E' => 30,  // Email
            'F' => 16,  // Teléfono
            'G' => 28,  // Programa
            'H' => 32,  // Oferta
            'I' => 28,  // Empresa
            'J' => 22,  // Estado
            'K' => 18,  // Fecha
            'L' => 35,  // Feedback
        ];
    }
}
