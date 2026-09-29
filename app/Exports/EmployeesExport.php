<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Builder $query)
    {
    }

    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'employee_id',
            'firstname',
            'lastname',
            'date_of_birth',
            'education_qualification',
            'email',
            'phone',
            'address',
        ];
    }

    public function map($e): array
    {
        return [
            $e->employee_id,
            $e->firstname,
            $e->lastname,
            substr((string) $e->date_of_birth, 0, 10),
            $e->education_qualification,
            $e->email,
            $e->phone,
            $e->address,
        ];
    }
}