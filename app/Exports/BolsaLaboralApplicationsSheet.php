<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BolsaLaboralApplicationsSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths, WithDrawings
{
    protected array $users;
    protected string $type;
    protected ?string $logoPath;
    protected string $institutionName;

    public function __construct(
        array $users,
        string $type = 'student',
        ?string $logoPath = null,
        string $institutionName = 'Bolsa Laboral Institucional'
    ) {
        $this->users = $users;
        $this->type = $type;
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
            $drawing->setHeight(48);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(12);
            $drawing->setOffsetY(6);

            return [$drawing];
        } catch (\Throwable $e) {
            return [];
        }
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
        $instUpper = mb_strtoupper($this->institutionName);
        $titleText = "{$instUpper} — DETALLE DE POSTULACIONES ({$roleLabel})";
        $subtitleText = "Institución: {$this->institutionName} | Fecha de emisión: {$exportDate} | Registro cronológico por postulación individual";

        $rows = [
            // Row 1: Main Title
            $this->hasLogo() ? ['', '', $titleText] : [$titleText],
            // Row 2: Subtitle
            $this->hasLogo() ? ['', '', $subtitleText] : [$subtitleText],
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

        // Merge title, subtitle, and logo box if present
        if ($this->hasLogo()) {
            $sheet->mergeCells('A1:B2');
            $sheet->mergeCells('C1:L1');
            $sheet->mergeCells('C2:L2');
        } else {
            $sheet->mergeCells('A1:L1');
            $sheet->mergeCells('A2:L2');
        }

        // Row Heights
        $sheet->getRowDimension(1)->setRowHeight(36);
        $sheet->getRowDimension(2)->setRowHeight(24);
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

        $titleRange = $this->hasLogo() ? 'C1:L1' : 'A1:L1';
        $subtitleRange = $this->hasLogo() ? 'C2:L2' : 'A2:L2';

        $styles = [
            $titleRange => [
                'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '006B60']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ],
            $subtitleRange => [
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

        if ($this->hasLogo()) {
            $styles['A1:B2'] = [
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFFFF']],
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ];
        }

        return $styles;
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
