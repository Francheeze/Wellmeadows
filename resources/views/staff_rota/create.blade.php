@extends('layouts.app')
@section('content')

<style>
.wb-page { padding: 1.5rem; font-family: 'Segoe UI', sans-serif; }

.page-header {
    margin-left: -1.5rem;
    margin-right: -1.5rem;
    margin-top: -1.5rem;
    padding: 1.2rem 1.5rem 0;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.page-title { font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 1rem; }
.tab-bar { display: flex; gap: 2.5rem; }

.tab-link {
    padding-bottom: 12px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    position: relative;
    font-weight: 500;
    transition: color 0.15s;
}

.tab-link:hover { color: #fff; text-decoration: none; }
.tab-link.active { color: #fff; font-weight: 700; }
.tab-link.active::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2.5px;
    background: #1a73e8;
    border-radius: 2px 2px 0 0;
}

/* CHANGED TO DARK NAVY CONTAINER COMPONENT */
.form-card {
    background: #0c2340;
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 14px;
    padding: 2rem;
    max-width: 560px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.form-card h5 { font-size: 1rem; font-weight: 700; color: #fff; margin: 0 0 1.5rem 0; }

.form-group { margin-bottom: 1.2rem; }

.form-group label {
    display: block;
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 0.4rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* DARK CONTEXT INPUT FIELDS */
.form-group input,
.form-group select {
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 0.6rem 0.9rem;
    font-size: 0.9rem;
    color: #fff;
    background: #08182b;
    transition: border 0.15s, background 0.15s;
    font-family: 'Segoe UI', sans-serif;
    appearance: none;
}

.form-group select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.2rem;
}

.form-group input:focus,
.form-group select:focus { outline: none; border-color: #1a73e8; background: #0c2340; }

.form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; }

.btn-save {
    background: #1a3a5c;
    color: #fff;
    border: 1px solid rgba(255,255,255,0.1);
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
    background: transparent;
    color: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    padding: 0.55rem 1.3rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s, color 0.2s;
}

.btn-cancel:hover { background: rgba(255, 255, 255, 0.05); color: #fff; text-decoration: none; }

.alert-error {
    background: rgba(185, 28, 28, 0.2);
    border: 1px solid rgba(185, 28, 28, 0.4);
    color: #fca5a5;
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
            <a href="{{ route('wards.index') }}"       class="tab-link">Ward</a>
            <a href="{{ route('beds.index') }}"        class="tab-link">Bed</a>
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
                <select name="ward_number" required>
                    <option value="">-- Select Ward --</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->ward_number }}"
                            {{ old('ward_number') == $ward->ward_number ? 'selected' : '' }}>
                            Ward {{ $ward->ward_number }}{{ $ward->ward_name ? ' — '.$ward->ward_name : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Staff Number</label>
                <select name="staff_number" required>
                    <option value="">-- Select Staff --</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->staff_number }}"
                            {{ old('staff_number') == $staff->staff_number ? 'selected' : '' }}>
                            {{ $staff->staff_number }} — {{ $staff->first_name }} {{ $staff->last_name }}
                        </option>
                    @endforeach
                </select>
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
                <input type="date" name="week_start_date" value="{{ old('week_start_date') }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save Allocation</button>
                <a href="{{ route('staff-rota.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection