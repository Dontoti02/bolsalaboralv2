<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BolsaLaboralReportExport implements WithMultipleSheets
{
    protected array $users;
    protected string $type;

    public function __construct(array $users, string $type = 'student')
    {
        $this->users = $users;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new BolsaLaboralSummarySheet($this->users, $this->type),
            new BolsaLaboralApplicationsSheet($this->users, $this->type),
        ];
    }
}
