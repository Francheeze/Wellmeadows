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

.form-card {
    background: #032d4f;
    border-radius: 12px;
    border: 1px solid rgba(204,236,238,0.12);
    padding: 2rem;
    max-width: 560px;
}

.form-card h5 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #f0f7f8;
    margin: 0 0 1.5rem 0;
}

.form-group {
    margin-bottom: 1.2rem;
}

.form-group label {
    display: block;
    font-size: 0.68rem;
    font-weight: 600;
    color: rgba(204,236,238,0.6);
    margin-bottom: 0.4rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.form-group input,
.form-group select {
    width: 100%;
    border: 1px solid rgba(204,236,238,0.2);
    border-radius: 8px;
    padding: 0.6rem 0.9rem;
    font-size: 0.85rem;
    color: #f0f7f8;
    background: #021829;
    transition: border-color 0.15s;
    font-family: 'DM Sans', sans-serif;
    appearance: none;
}

.form-group select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23CCECEE' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.2rem;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #CCECEE;
}

::placeholder {
    color: rgba(204,236,238,0.3) !important;
    opacity: 1;
}

.form-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

.btn-save {
    background: #03416E;
    color: #CCECEE;
    border: 1px solid rgba(204,236,238,0.3);
    border-radius: 8px;
    padding: 0.55rem 1.3rem;
    font-size: 0.8rem;
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
    background: transparent;
    color: rgba(204,236,238,0.6);
    border: 1px solid rgba(204,236,238,0.15);
    border-radius: 8px;
    padding: 0.55rem 1.3rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: background 0.2s, color 0.2s;
    font-family: 'DM Sans', sans-serif;
}

.btn-cancel:hover {
    background: rgba(204,236,238,0.08);
    color: #CCECEE;
    text-decoration: none;
}

.alert-error {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.25);
    color: #fca5a5;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.2rem;
    font-size: 0.82rem;
}

.alert-error ul {
    margin: 0;
    padding-left: 1.2rem;
}
</style>

<div class="wb-page">
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
                <input type="text" name="bed_number" value="{{ old('bed_number') }}" required placeholder="e.g. 91">
            </div>

            <div class="form-group">
                <label>Ward Number</label>
                <select name="ward_number" required>
                    <option value="">-- Select Ward --</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->ward_number }}" {{ old('ward_number') == $ward->ward_number ? 'selected' : '' }}>
                            {{ $ward->ward_number }} — {{ $ward->ward_name }}
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