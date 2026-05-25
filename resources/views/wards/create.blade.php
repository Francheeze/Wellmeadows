@extends('layouts.app')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap');

.wb-page {
    padding: 1.5rem;
    font-family: 'DM Sans', sans-serif;
    background: #021829;
    min-height: 100vh;
    color: #f0f7f8;
}

.page-header {
    background: #032d4f;
    margin-left: -1.5rem;
    margin-right: -1.5rem;
    margin-top: -1.5rem;
    padding: 1.2rem 1.5rem 0;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid rgba(204,236,238,0.1);
}

.page-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #f0f7f8;
    margin-bottom: 1rem;
}

.tab-bar {
    display: flex;
    gap: 2.5rem;
    border-bottom: 1px solid rgba(204,236,238,0.15);
}

.tab-link {
    padding-bottom: 12px;
    font-size: 0.9rem;
    color: rgba(204,236,238,0.5);
    text-decoration: none;
    position: relative;
    font-weight: 500;
    transition: color 0.15s;
    font-family: 'DM Sans', sans-serif;
}

.tab-link:hover { color: #f0f7f8; text-decoration: none; }
.tab-link.active { color: #f0f7f8; font-weight: 700; }
.tab-link.active::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2.5px;
    background: #CCECEE;
    border-radius: 2px 2px 0 0;
}

.form-card {
    background: #032d4f;
    border: 1px solid rgba(204,236,238,0.12);
    border-radius: 14px;
    padding: 2rem;
    max-width: 560px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.3);
}

.form-card h5 {
    font-size: 1rem;
    font-weight: 700;
    color: #f0f7f8;
    margin: 0 0 1.5rem 0;
}

.form-group { margin-bottom: 1.2rem; }

.form-group label {
    display: block;
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(204,236,238,0.6);
    margin-bottom: 0.4rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.form-group input,
.form-group select {
    width: 100%;
    border: 1px solid rgba(204,236,238,0.15);
    border-radius: 8px;
    padding: 0.6rem 0.9rem;
    font-size: 0.9rem;
    color: #f0f7f8;
    background: #021829;
    transition: border 0.15s;
    font-family: 'DM Sans', sans-serif;
    box-sizing: border-box;
}

.form-group input::placeholder { color: rgba(204,236,238,0.3); }

.form-group select option {
    background: #021829;
    color: #f0f7f8;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #CCECEE;
    background: #032d4f;
}

.form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; }

.btn-save {
    background: #03416E;
    color: #CCECEE;
    border: 1px solid rgba(204,236,238,0.3);
    border-radius: 10px;
    padding: 0.55rem 1.3rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    font-family: 'DM Sans', sans-serif;
}

.btn-save:hover {
    background: #CCECEE;
    color: #021829;
}

.btn-cancel {
    background: rgba(204,236,238,0.05);
    color: rgba(204,236,238,0.7);
    border: 1px solid rgba(204,236,238,0.15);
    border-radius: 10px;
    padding: 0.55rem 1.3rem;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s;
    font-family: 'DM Sans', sans-serif;
}

.btn-cancel:hover {
    background: rgba(204,236,238,0.1);
    color: #f0f7f8;
    text-decoration: none;
}

.alert-error {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.25);
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
            <a href="{{ route('wards.index') }}"      class="tab-link active">Ward</a>
            <a href="{{ route('beds.index') }}"       class="tab-link">Bed</a>
            <a href="{{ route('staff-rota.index') }}" class="tab-link">Staff Allocation</a>
        </div>
    </div>

    <div class="form-card">
        <h5>Add New Ward</h5>

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('wards.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Ward Number</label>
                <input type="number" name="ward_number" value="{{ old('ward_number') }}" required placeholder="e.g. 16">
            </div>

            <div class="form-group">
                <label>Ward Name</label>
                <input type="text" name="ward_name" value="{{ old('ward_name') }}" required placeholder="e.g. Orthopaedic">
            </div>

            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Block A">
            </div>

            <div class="form-group">
                <label>Total Beds</label>
                <input type="number" name="total_beds" value="{{ old('total_beds') }}" placeholder="e.g. 20">
            </div>

            <div class="form-group">
                <label>Telephone Extension</label>
                <input type="text" name="telephone_extention" value="{{ old('telephone_extention') }}" placeholder="e.g. 1234">
            </div>

            <div class="form-group">
                <label>Charge Nurse Number</label>
                <select name="charge_nurse_number">
                    <option value="">-- Select Charge Nurse --</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->staff_number }}" {{ old('charge_nurse_number') == $staff->staff_number ? 'selected' : '' }}>
                            {{ $staff->staff_number }} - {{ $staff->first_name }} {{ $staff->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save Ward</button>
                <a href="{{ route('wards.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection