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
        appearance: none;
    }

    .form-group select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2.2rem;
    }

    .form-group input:focus,
    .form-group select:focus {
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
        <h5>Add New Bed</h5>

        @if($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('beds.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Bed Number</label>
                <input type="text" name="bednumber" value="{{ old('bednumber') }}" required placeholder="e.g. 91">
            </div>

            <div class="form-group">
                <label>Ward Number</label>
                <select name="wardnumber" required>
                    <option value="">-- Select Ward --</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->wardnumber }}" {{ old('wardnumber') == $ward->wardnumber ? 'selected' : '' }}>
                            {{ $ward->wardnumber }} — {{ $ward->wardname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied"  {{ old('status') == 'occupied'  ? 'selected' : '' }}>Occupied</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Save Bed</button>
                <a href="{{ route('beds.index') }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection