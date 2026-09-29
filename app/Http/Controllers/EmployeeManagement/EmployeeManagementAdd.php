<?php

namespace App\Http\Controllers\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeManagementAdd extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::query()
            ->when($request->q, fn($q, $s) => $q->where(function ($w) use ($s) {
                $w->where('firstname', 'like', "%$s%")
                    ->orWhere('lastname', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")
                    ->orWhere('employee_id', 'like', "%$s%");
            }))
            ->latest()->paginate(10)->withQueryString();

        $viewEmployee = $request->filled('view') ? Employee::find($request->view) : null;
        $editEmployee = $request->filled('edit') ? Employee::find($request->edit) : null;

        return view('employees', compact('employees', 'viewEmployee', 'editEmployee'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate($this->rules($employee->id));

        if ($request->hasFile('photo')) {
            if ($employee->photo)
                Storage::disk('public')->delete($employee->photo);
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        if ($request->hasFile('resume')) {
            if ($employee->resume)
                Storage::disk('public')->delete($employee->resume);
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo)
            Storage::disk('public')->delete($employee->photo);
        if ($employee->resume)
            Storage::disk('public')->delete($employee->resume);

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