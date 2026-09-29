<?php

namespace App\Imports;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row): Model|array|null
    {
        $dob = is_numeric($row['date_of_birth'])
            ? Date::excelToDateTimeObject($row['date_of_birth'])->format('Y-m-d')
            : Carbon::parse($row['date_of_birth'])->format('Y-m-d');
        return Employee::updateOrCreate(
            ['employee_id' => $row['employee_id']],
            [
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'date_of_birth' => $dob,
                'education_qualification' => $row['education_qualification'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'address' => $row['address'],
            ]
        );
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'date_of_birth' => 'required',
            'education_qualification' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ];
    }
}