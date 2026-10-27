<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BolsaLaboralReportExport implements WithMultipleSheets
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

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new BolsaLaboralSummarySheet($this->users, $this->type, $this->logoPath, $this->institutionName),
            new BolsaLaboralApplicationsSheet($this->users, $this->type, $this->logoPath, $this->institutionName),
        ];
    }
}
