<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeManagementAdd extends Controller
{

    private function filtered(Request $request)
    {
        $like = \DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';

        return Employee::query()
            ->when($request->q, fn($q, $s) => $q->where(function ($w) use ($s, $like) {
                $w->where('firstname', $like, "%$s%")
                    ->orWhere('lastname', $like, "%$s%")
                    ->orWhere('email', $like, "%$s%")
                    ->orWhere('employee_id', $like, "%$s%")
                    ->orWhereRaw("CONCAT(firstname, ' ', lastname) $like ?", ["%$s%"]);
            }))
            ->when($request->education, fn($q, $v) => $q->where('education_qualification', $v))
            ->when($request->created_from, fn($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->created_to, fn($q, $v) => $q->whereDate('created_at', '<=', $v));
    }
    public function index(Request $request)
    {
        $employees = $this->filtered($request)->latest()->paginate(10)->withQueryString();

        $educations = Employee::query()->distinct()
            ->orderBy('education_qualification')->pluck('education_qualification');

        $viewEmployee = $request->filled('view') ? Employee::find($request->view) : null;
        $editEmployee = $request->filled('edit') ? Employee::find($request->edit) : null;

        return view('employees', compact('employees', 'educations', 'viewEmployee', 'editEmployee'));
    }

    private function disk(): string
    {
        $d = config('filesystems.default');
        return $d === 'local' ? 'public' : $d;
    }
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());


        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', $this->disk());
        }
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('resumes', $this->disk());
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate($this->rules($employee->id));
        if ($request->hasFile('photo')) {
            if ($employee->photo)
                Storage::disk($this->disk())->delete($employee->photo);
            $data['photo'] = $request->file('photo')->store('photos', $this->disk());
        }
        if ($request->hasFile('resume')) {
            if ($employee->resume)
                Storage::disk($this->disk())->delete($employee->resume);
            $data['resume'] = $request->file('resume')->store('resumes', $this->disk());
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function export(Request $request)
    {
        return Excel::download(
            new EmployeesExport($this->filtered($request)->latest()),
            'employees-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));
        } catch (ValidationException $e) {
            $msgs = collect($e->failures())
                ->map(fn($f) => 'Row ' . $f->row() . ': ' . implode(', ', $f->errors()))
                ->take(5)->implode(' | ');
            return redirect()->route('employees.index')->with('error', $msgs);
        }

        return redirect()->route('employees.index')->with('success', 'Employees imported successfully.');
    }
    public function destroy(Employee $employee)
    {
        if ($employee->photo)
            Storage::disk($this->disk())->delete($employee->photo);
        if ($employee->resume)
            Storage::disk($this->disk())->delete($employee->resume);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    private function rules($id = null): array
    {
        return [
            'employee_id' => ['required', 'string', Rule::unique('employees', 'employee_id')->ignore($id)],
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'education_qualification' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('employees', 'email')->ignore($id)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ];
    }
}