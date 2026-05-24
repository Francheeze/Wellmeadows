@extends('layouts.app')
@section('content')

<style>
    .form-page {
        padding: 2rem;
        font-family: 'Segoe UI', sans-serif;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 2rem;
        max-width: 560px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .form-card h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 1.5rem 0;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-group label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.6rem 0.9rem;
        font-size: 0.9rem;
        color: #111827;
        background: #f9fafb;
        transition: border 0.15s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #6366f1;
        background: #fff;
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-save {
        background: #1e293b;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.55rem 1.3rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-save:hover { background: #334155; }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0.55rem 1.3rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        color: #111827;
        text-decoration: none;
    }

    .alert-error {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.2rem;
        font-size: 0.875rem;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 1.2rem;
    }
</style>

<div class="form-page">
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
                        <option value="{{ $staff->staffNumber }}" {{ old('charge_nurse_number') == $staff->staffNumber ? 'selected' : '' }}>
                            {{ $staff->staffNumber }} - {{ $staff->firstName }} {{ $staff->lastName }}
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