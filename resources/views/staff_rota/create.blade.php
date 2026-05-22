@extends('layouts.app')
@section('content')

<style>
.wb-page { padding: 1.5rem; font-family: 'Segoe UI', sans-serif; }

.page-header {
    background: #fff;
    margin-left: -1.5rem;
    margin-right: -1.5rem;
    margin-top: -1.5rem;
    padding: 1.2rem 1.5rem 0;
    margin-bottom: 1.5rem;
}

.page-title { font-size: 1.1rem; font-weight: 700; color: #1a2a3a; margin-bottom: 1rem; }
.tab-bar { display: flex; gap: 2.5rem; border-bottom: 1px solid #d0e4ed; }

.tab-link {
    padding-bottom: 12px;
    font-size: 0.9rem;
    color: #8aabb8;
    text-decoration: none;
    position: relative;
    font-weight: 500;
    transition: color 0.15s;
}

.tab-link:hover { color: #1a3a5c; text-decoration: none; }
.tab-link.active { color: #1a3a5c; font-weight: 700; }
.tab-link.active::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2.5px;
    background: #1a3a5c;
    border-radius: 2px 2px 0 0;
}

.form-card {
    background: #fff;
    border: 1px solid #d0e4ed;
    border-radius: 14px;
    padding: 2rem;
    max-width: 560px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}

.form-card h5 { font-size: 1rem; font-weight: 700; color: #1a2a3a; margin: 0 0 1.5rem 0; }

.form-group { margin-bottom: 1.2rem; }

.form-group label {
    display: block;
    font-size: 0.78rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.4rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.form-group input,
.form-group select {
    width: 100%;
    border: 1px solid #d0e4ed;
    border-radius: 8px;
    padding: 0.6rem 0.9rem;
    font-size: 0.9rem;
    color: #1a2a3a;
    background: #f8fbfc;
    transition: border 0.15s;
    font-family: 'Segoe UI', sans-serif;
    appearance: none;
}

.form-group select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.2rem;
    background-color: #f8fbfc;
}

.form-group input:focus,
.form-group select:focus { outline: none; border-color: #1a3a5c; background: #fff; }

.form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; }

.btn-save {
    background: #1a3a5c;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.55rem 1.3rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    font-family: 'Segoe UI', sans-serif;
}

.btn-save:hover { background: #2a5a8c; }

.btn-cancel {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d0e4ed;
    border-radius: 8px;
    padding: 0.55rem 1.3rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s;
}

.btn-cancel:hover { background: #e5e7eb; color: #111827; text-decoration: none; }

.alert-error {
    background: #fff1f2;
    border: 1px solid #fecdd3;
    color: #be123c;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.2rem;
    font-size: 0.875rem;
}

.alert-error ul { margin: 0; padding-left: 1.2rem; }
</style>

<div class="wb-page">

    <div class="page-header">
        <div class="page-title">Ward and Bed Management</div>
        <div class="tab-bar">
            <a href="{{ route('wards.index') }}"      class="tab-link">Ward</a>
            <a href="{{ route('beds.index') }}"       class="tab-link">Bed</a>
            <a href="{{ route('staff-rota.index') }}" class="tab-link active">Staff Allocation</a>
        </div>
    </div>

    <div class="form-card">
        <h5>Add Staff to Rota</h5>

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('staff-rota.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Ward Number</label>
                <select name="wardnumber" required>
                    <option value="">-- Select Ward --</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->wardnumber }}"
                            {{ old('wardnumber') == $ward->wardnumber ? 'selected' : '' }}>
                            {{ $ward->wardnumber }}{{ $ward->wardname ? ' — '.$ward->wardname : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Staff Number</label>
                <input type="text" name="staffnumber"
                       value="{{ old('staffnumber') }}"
                       required placeholder="e.g. S011">
            </div>

            <div class="form-group">
                <label>Shift</label>
                <select name="shift" required>
                    <option value="">-- Select Shift --</option>
                    <option value="Early" {{ old('shift') == 'Early' ? 'selected' : '' }}>Early</option>
                    <option value="Late"  {{ old('shift') == 'Late'  ? 'selected' : '' }}>Late</option>
                    <option value="Night" {{ old('shift') == 'Night' ? 'selected' : '' }}>Night</option>
                </select>
            </div>

            <div class="form-group">
                <label>Week Start Date</label>
                <input type="date" name="weekstartdate"
                       value="{{ old('weekstartdate') }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save</button>
                <a href="{{ route('staff-rota.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection