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

class BolsaLaboralSummarySheet implements FromArray, WithTitle, WithStyles, WithColumnWidths, WithDrawings
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
        return match($this->type) {
            'graduate' => 'Egresados',
            'student'  => 'Estudiantes',
            default    => 'Todos (Estudiantes-Egresados)',
        };
    }

    public function array(): array
    {
        $roleLabel = match($this->type) {
            'graduate' => 'EGRESADOS',
            'student'  => 'ESTUDIANTES',
            default    => 'ESTUDIANTES Y EGRESADOS',
        };
        $exportDate = date('d/m/Y H:i:s');
        $totalCount = count($this->users);
        $instUpper = mb_strtoupper($this->institutionName);
        $titleText = "{$instUpper} — REPORTE DE BOLSA LABORAL ({$roleLabel})";
        $subtitleText = "Institución: {$this->institutionName} | Fecha de emisión: {$exportDate} | Total de registros evaluados: {$totalCount}";

        $rows = [
            // Row 1: Main Title
            $this->hasLogo() ? ['', '', $titleText] : [$titleText],
            // Row 2: Subtitle / Metadata
            $this->hasLogo() ? ['', '', $subtitleText] : [$subtitleText],
            // Row 3: Table Headers
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

            // Determinar rol específico
            $userRole = ($u['rol_id'] ?? null) == 5 ? 'Egresado' : 'Estudiante';
            if ($this->type === 'graduate') $userRole = 'Egresado';
            if ($this->type === 'student')  $userRole = 'Estudiante';

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

            // Data rows start at index 4 (Excel row 4)
            $rows[] = [
                $index++,
                $userRole,
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

        if (count($this->users) === 0) {
            $rows[] = [
                '-',
                '-',
                '-',
                'No se encontraron registros con los filtros seleccionados',
                '-',
                '-',
                '-',
                0,
                '-',
                '-',
                '-'
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $totalDataRows = count($this->users);
        $highestRow = max($totalDataRows + 3, 4);

        // Merge title, subtitle, and logo box if present
        if ($this->hasLogo()) {
            $sheet->mergeCells('A1:B2');
            $sheet->mergeCells('C1:K1');
            $sheet->mergeCells('C2:K2');
        } else {
            $sheet->mergeCells('A1:K1');
            $sheet->mergeCells('A2:K2');
        }

        // Row Heights
        $sheet->getRowDimension(1)->setRowHeight(36);
        $sheet->getRowDimension(2)->setRowHeight(24);
        $sheet->getRowDimension(3)->setRowHeight(28);

        // Freeze pane at Row 4 (keeps headers sticky)
        $sheet->freezePane('A4');

        // Text wrap on offers and status columns
        $sheet->getStyle("I4:J{$highestRow}")->getAlignment()->setWrapText(true);

        // Vertical alignments: Top for multi-line text, center for titles/headers
        $sheet->getStyle("A4:K{$highestRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        // Column Horizontal Alignments for data
        $sheet->getStyle("A4:C{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D4:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("F4:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G4:G{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("H4:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("I4:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("J4:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Make Total Postulaciones bold
        $sheet->getStyle("H4:H{$highestRow}")->getFont()->setBold(true);

        // Borders for table data (from row 3 to highestRow)
        $sheet->getStyle("A3:K{$highestRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('D1D5DB'));

        // Soft Zebra Striping for alternating data rows (starting at row 4)
        for ($r = 4; $r <= $highestRow; $r++) {
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:K{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }
        }

        // AutoFilter on Header Row 3
        $sheet->setAutoFilter("A3:K{$highestRow}");

        $titleRange = $this->hasLogo() ? 'C1:K1' : 'A1:K1';
        $subtitleRange = $this->hasLogo() ? 'C2:K2' : 'A2:K2';

        $styles = [
            // Row 1: Institutional Header
            $titleRange => [
                'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '002741']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ],
            // Row 2: Metadata / Subtitle
            $subtitleRange => [
                'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '4B5563']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER, 'indent' => 1],
            ],
            // Row 3: TABLE HEADERS (Navy / Dark Slate Header)
            3 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F3D5E']],
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
            'C' => 14,  // DNI
            'D' => 32,  // Nombres
            'E' => 30,  // Correo
            'F' => 16,  // Teléfono
            'G' => 28,  // Programa Estudio
            'H' => 16,  // Total Apps
            'I' => 42,  // Ofertas
            'J' => 24,  // Estados
            'K' => 16,  // Fecha Registro
        ];
    }
}
