<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

@php
    $showModal = $errors->any() || $viewEmployee || $editEmployee;
    $fields = [
        'employee_id' => ['Employee ID', 'text', 'EMP-001'],
        'firstname' => ['First name', 'text', 'John'],
        'lastname' => ['Last name', 'text', 'Doe'],
        'date_of_birth' => ['Date of birth', 'date', ''],
        'education_qualification' => ['Education', 'text', 'B.E. Computer Science'],
        'email' => ['Email', 'email', 'john@company.com'],
        'phone' => ['Phone', 'text', '+91 98765 43210'],
    ];

    $initials = fn($e) => strtoupper(substr($e->firstname, 0, 1) . substr($e->lastname, 0, 1));
@endphp

<body class="min-h-screen bg-slate-100 text-slate-700 antialiased {{ $showModal ? 'overflow-hidden' : '' }}">

    <div class="mx-auto max-w-6xl px-4 py-8 sm:py-10">
        <div class="mb-7 flex flex-col gap-5 border-b border-slate-200 pb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Employees</h1>
                <p class="mt-1.5 text-sm text-slate-500">
                    {{ $employees->total() }} {{ Str::plural('employee', $employees->total()) }} in total
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('employees.export', request()->query()) }}"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">
                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3" />
                    </svg>
                    Export
                </a>

                <form method="POST" action="{{ route('employees.import') }}" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" class="hidden"
                        aria-label="Choose spreadsheet to import">
                    <button type="button" id="importButton"
                        class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100 disabled:cursor-wait disabled:opacity-75"
                        aria-busy="false">
                        <svg id="importIcon" class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4 4 4M5 14v5h14v-5" />
                        </svg>
                        <svg id="importSpinner" class="hidden h-4 w-4 animate-spin text-amber-600" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        <span id="importLabel">Import</span>
                    </button>
                </form>

                <button type="button" id="addEmployee"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-amber-500 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" d="M12 5v14M5 12h14" />
                    </svg>
                    Add Employee
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-lg px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-200/70 hover:text-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-200">
                        Log out
                    </button>
                </form>
            </div>
        </div>

        {{-- Card --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

            <div class="border-b border-slate-100 p-4">
                <form method="GET" action="{{ route('employees.index') }}" class="flex flex-wrap items-end gap-3">

                    <div class="relative min-w-[220px] flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7" />
                            <path stroke-linecap="round" d="m20 20-3.5-3.5" />
                        </svg>
                        <input name="q" value="{{ request('q') }}" placeholder="Search name, email, ID..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm placeholder-slate-400 transition focus:border-amber-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-amber-100">
                    </div>

                    <select name="education"
                        class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100">
                        <option value="">All education</option>
                        @foreach ($educations as $edu)
                            <option value="{{ $edu }}" @selected(request('education') === $edu)>{{ $edu }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex items-center gap-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-500">Created from</label>
                            <input type="date" name="created_from" value="{{ request('created_from') }}"
                                class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-500">Created to</label>
                            <input type="date" name="created_to" value="{{ request('created_to') }}"
                                class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100">
                        </div>
                    </div>

                    <button
                        class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">
                        Filter
                    </button>

                    @if (request()->hasAny(['q', 'education', 'created_from', 'created_to']))
                        <a href="{{ route('employees.index') }}"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-slate-500 transition hover:bg-slate-50">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
            @if (session('success'))
                <div
                    class="mx-4 mt-4 flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mx-4 mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200">
                    <p class="mb-1 font-semibold">Please fix the following:</p>
                    <ul class="list-inside list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <th class="px-6 py-3.5">Employee</th>
                            <th class="px-6 py-3.5">ID</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5">Phone</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($employees as $employee)
                            <tr class="transition hover:bg-amber-50/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($employee->photo)
                                            <img src="{{ asset('storage/' . $employee->photo) }}"
                                                class="h-10 w-10 rounded-full object-cover ring-2 ring-white shadow">
                                        @else
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-xs font-bold text-white shadow">
                                                {{ $initials($employee) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-slate-900">
                                            {{ $employee->firstname }} {{ $employee->lastname }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="rounded-md bg-slate-100 px-2 py-1 font-mono text-xs text-slate-600">{{ $employee->employee_id }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $employee->email }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $employee->phone }}</td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-1">
                                        <a title="View"
                                            href="{{ route('employees.index', ['view' => $employee->id, 'page' => request('page'), 'q' => request('q')]) }}"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>
                                        <a title="Edit"
                                            href="{{ route('employees.index', ['edit' => $employee->id, 'page' => request('page'), 'q' => request('q')]) }}"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.9 3.6 3.5 3.5M4 20l4.2-.8L19.4 8 16 4.6 4.8 15.8 4 20Z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('employees.destroy', $employee) }}"
                                            onsubmit="return confirm('Delete this employee?')">
                                            @csrf
                                            @method('DELETE')
                                            <button title="Delete"
                                                class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M9 7V4h6v3" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div
                                        class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.6"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17 20v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm11 10v-2a4 4 0 0 0-3-3.9M16 2.1a4 4 0 0 1 0 7.8" />
                                        </svg>
                                    </div>
                                    <p class="font-medium text-slate-700">No employees found</p>
                                    <p class="mt-1 text-sm text-slate-400">Click "Add Employee" to create the first
                                        one.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($employees->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="employeeModal" data-server="{{ $showModal ? 1 : 0 }}"
        class="{{ $showModal ? 'flex' : 'hidden' }} fixed inset-0 z-50 items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">

        <div class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
            <div
                class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-6 py-4">
                <h2 class="text-lg font-bold text-slate-900">
                    @if ($viewEmployee)
                        Employee Details
                    @elseif ($editEmployee)
                        Edit Employee
                    @else
                        Add Employee
                    @endif
                </h2>
                <button type="button" id="closeModal"
                    class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                @if ($viewEmployee)
                    <div class="mb-6 flex items-center gap-4">
                        @if ($viewEmployee->photo)
                            <img src="{{ asset('storage/' . $viewEmployee->photo) }}"
                                class="h-20 w-20 rounded-2xl object-cover shadow">
                        @else
                            <div
                                class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-2xl font-bold text-white shadow">
                                {{ $initials($viewEmployee) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-xl font-bold text-slate-900">
                                {{ $viewEmployee->firstname }} {{ $viewEmployee->lastname }}
                            </p>
                            <p
                                class="mt-1 inline-block rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs text-slate-600">
                                {{ $viewEmployee->employee_id }}
                            </p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach ([
        'Email' => $viewEmployee->email,
        'Phone' => $viewEmployee->phone,
        'Date of birth' => substr((string) $viewEmployee->date_of_birth, 0, 10),
        'Education' => $viewEmployee->education_qualification,
    ] as $label => $value)
                            <div class="rounded-xl bg-slate-50 p-3.5">
                                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                    {{ $label }}</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-800">{{ $value ?: '—' }}</dd>
                            </div>
                        @endforeach

                        <div class="rounded-xl bg-slate-50 p-3.5 sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Address</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-800">{{ $viewEmployee->address ?: '—' }}
                            </dd>
                        </div>
                    </dl>

                    @if ($viewEmployee->resume)
                        <a href="{{ asset('storage/' . $viewEmployee->resume) }}" target="_blank"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v11m0 0-4-4m4 4 4-4M5 20h14" />
                            </svg>
                            Download Resume
                        </a>
                    @endif
                @else
                    {{-- ===== ADD / EDIT ===== --}}
                    <form method="POST" enctype="multipart/form-data"
                        action="{{ $editEmployee ? route('employees.update', $editEmployee) : route('employees.store') }}"
                        class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @csrf
                        @if ($editEmployee)
                            @method('PUT')
                        @endif

                        @foreach ($fields as $name => [$label, $type, $placeholder])
                            @php
                                $current = $editEmployee?->{$name};
                                if ($name === 'date_of_birth') {
                                    $current = substr((string) $current, 0, 10);
                                }
                            @endphp
                            <div>
                                <label for="f_{{ $name }}"
                                    class="mb-1.5 block text-sm font-medium text-slate-700">{{ $label }}</label>
                                <input id="f_{{ $name }}" type="{{ $type }}"
                                    name="{{ $name }}" placeholder="{{ $placeholder }}"
                                    value="{{ old($name, $current) }}" required
                                    class="w-full rounded-xl border px-3.5 py-2.5 text-sm transition focus:outline-none focus:ring-4
                                    {{ $errors->has($name) ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-amber-400 focus:ring-amber-100' }}">
                                @error($name)
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div class="sm:col-span-2">
                            <label for="f_address"
                                class="mb-1.5 block text-sm font-medium text-slate-700">Address</label>
                            <textarea id="f_address" name="address" rows="3" placeholder="Street, city, state, pincode" required
                                class="w-full rounded-xl border px-3.5 py-2.5 text-sm transition focus:outline-none focus:ring-4
                                {{ $errors->has('address') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : 'border-slate-200 focus:border-amber-400 focus:ring-amber-100' }}">{{ old('address', $editEmployee?->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Photo</label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 p-2 text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-amber-600">
                            @error('photo')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Resume</label>
                            <input type="file" name="resume" accept=".pdf,.doc,.docx"
                                class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50 p-2 text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-800 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-slate-700">
                            @error('resume')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-2 flex justify-end gap-2 border-t border-slate-100 pt-5 sm:col-span-2">
                            <button type="button" id="cancelModal"
                                class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                                Cancel
                            </button>
                            <button type="submit"
                                class="rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm shadow-amber-500/30 transition hover:bg-amber-600 focus:outline-none focus:ring-4 focus:ring-amber-200">
                                {{ $editEmployee ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(function() {
            const $modal = $('#employeeModal');

            function openModal() {
                $modal.removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
            }

            function closeModal() {
                if ($modal.data('server')) {
                    window.location.href = "{{ route('employees.index') }}";
                } else {
                    $modal.addClass('hidden').removeClass('flex');
                    $('body').removeClass('overflow-hidden');
                }
            }

            $('#addEmployee').on('click', openModal);
            $('#closeModal, #cancelModal').on('click', closeModal);

            const importFile = document.getElementById('importFile');
            const importButton = document.getElementById('importButton');

            importButton.addEventListener('click', () => importFile.click());
            importFile.addEventListener('change', () => {
                if (!importFile.files.length) return;

                importButton.disabled = true;
                importButton.setAttribute('aria-busy', 'true');
                document.getElementById('importIcon').classList.add('hidden');
                document.getElementById('importSpinner').classList.remove('hidden');
                document.getElementById('importLabel').textContent = 'Uploading...';
                document.getElementById('importForm').requestSubmit();
            });

            $modal.on('click', function(e) {
                if (e.target === this) closeModal();
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $modal.hasClass('flex')) closeModal();
            });
        });
    </script>

</body>

</html>
