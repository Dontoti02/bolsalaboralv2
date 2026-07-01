<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class AdminDashboardExport implements FromArray, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['Reporte de Resumen de Administración Global'],
            [],
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
        return [
            // Title row
            1 => ['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '002741']]],
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
