@extends('layouts.app')
@section('content')

<style>
.wb-page { padding: 1.5rem; font-family: 'Segoe UI', sans-serif; }

.attr-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 14px;
    margin-bottom: 1.5rem;
}

.attr-card {
    background: #1a3a5c;
    border-radius: 12px;
    padding: 18px;
    position: relative;
    overflow: hidden;
}

.attr-card.green  { background: #2a7a50; }
.attr-card.purple { background: #534ab7; }
.attr-card.dark   { background: #3a4a5a; }

.attr-card::before {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}

.attr-icon {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: rgba(255,255,255,0.12);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 10px;
    font-size: 1.2rem;
    color: #fff;
}

.attr-label {
    font-size: 0.68rem;
    color: rgba(255,255,255,0.6);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 5px;
}

.attr-value { font-size: 1.8rem; font-weight: 700; color: #fff; line-height: 1; }
.attr-sub { font-size: 0.68rem; color: rgba(255,255,255,0.5); margin-top: 3px; }

.table-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #d0e4ed;
    padding: 1.2rem 1.5rem;
}

.tc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.tc-title { font-size: 0.95rem; font-weight: 700; color: #1a2a3a; }
.tc-right { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

.tc-select {
    padding: 0.4rem 1.8rem 0.4rem 0.75rem;
    border: 1px solid #d0e4ed;
    border-radius: 7px;
    font-size: 0.8rem;
    color: #1a2a3a;
    background: #f8fbfc;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    cursor: pointer;
    min-width: 130px;
}

.btn-add {
    background: #1a3a5c;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.45rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    transition: background 0.2s;
    cursor: pointer;
}

.btn-add:hover { background: #2a5a8c; color: #fff; text-decoration: none; }

.data-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }

.data-table thead th {
    text-align: left;
    padding: 0.5rem 0.75rem;
    font-size: 0.68rem;
    font-weight: 600;
    color: #8aabb8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #eef4f7;
}

.data-table tbody tr { border-bottom: 1px solid #f4f8fa; transition: background 0.1s; }
.data-table tbody tr:last-child { border-bottom: none; }
.data-table tbody tr:hover { background: #f8fbfc; }
.data-table tbody td { padding: 0.75rem 0.75rem; color: #2a3a4a; vertical-align: middle; }

.num-pill { font-weight: 700; color: #1a6fa8; font-size: 0.9rem; }

.ward-badge {
    display: inline-block;
    background: #eef2ff;
    color: #4338ca;
    border-radius: 6px;
    padding: 0.18rem 0.55rem;
    font-size: 0.7rem;
    font-weight: 600;
}

.shift-badge {
    display: inline-block;
    padding: 0.18rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
}

.shift-Early { background: #dbeafe; color: #1d4ed8; }
.shift-Late  { background: #ede9fe; color: #5b21b6; }
.shift-Night { background: #f3f4f6; color: #374151; }

.btn-edit {
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    padding: 0.2rem 0.6rem;
    font-size: 0.72rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s;
    display: inline-block;
}

.btn-edit:hover { background: #dcfce7; color: #15803d; text-decoration: none; }

.btn-del {
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
    border-radius: 6px;
    padding: 0.2rem 0.6rem;
    font-size: 0.72rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s;
}

.btn-del:hover { background: #ffe4e6; }
.action-row { display: flex; gap: 0.4rem; }

.empty-state td { text-align: center; color: #9ca3af; padding: 2rem; font-size: 0.85rem; }
</style>

<div class="wb-page">

    @if(session('success'))
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:10px;padding:0.7rem 1rem;margin-bottom:1.2rem;font-size:0.85rem;font-weight:500;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="attr-grid">
        <div class="attr-card">
            <div class="attr-icon"><i class="ti ti-users"></i></div>
            <div class="attr-label">Total staff allocated</div>
            <div class="attr-value">{{ $staffRotas->count() }}</div>
            <div class="attr-sub">This week</div>
        </div>
        <div class="attr-card green">
            <div class="attr-icon"><i class="ti ti-sun"></i></div>
            <div class="attr-label">Early shift</div>
            <div class="attr-value">{{ $staffRotas->where('shift','Early')->count() }}</div>
            <div class="attr-sub">On early duty</div>
        </div>
        <div class="attr-card purple">
            <div class="attr-icon"><i class="ti ti-clock"></i></div>
            <div class="attr-label">Late shift</div>
            <div class="attr-value">{{ $staffRotas->where('shift','Late')->count() }}</div>
            <div class="attr-sub">On late duty</div>
        </div>
        <div class="attr-card dark">
            <div class="attr-icon"><i class="ti ti-moon"></i></div>
            <div class="attr-label">Night shift</div>
            <div class="attr-value">{{ $staffRotas->where('shift','Night')->count() }}</div>
            <div class="attr-sub">On night duty</div>
        </div>
    </div>

    <div class="table-card">
        <div class="tc-header">
            <span class="tc-title">Staff allocation list</span>
            <div class="tc-right">
                <select class="tc-select" id="wardFilter" onchange="filterRota()">
                    <option value="all">All wards</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->ward_number }}">
                            Ward {{ $ward->ward_number }}{{ $ward->ward_name ? ' — '.$ward->ward_name : '' }}
                        </option>
                    @endforeach
                </select>
                <select class="tc-select" id="shiftFilter" onchange="filterRota()">
                    <option value="all">All shifts</option>
                    <option value="Early">Early</option>
                    <option value="Late">Late</option>
                    <option value="Night">Night</option>
                </select>
                <a href="{{ route('staff-rota.create') }}" class="btn-add">+ Add staff</a>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Staff no.</th>
                    <th>Ward no.</th>
                    <th>Shift</th>
                    <th>Week start</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="rota-tbody">
                @forelse($staffRotas as $rota)
                <tr class="rota-row" data-ward="{{ $rota->ward_number }}" data-shift="{{ $rota->shift }}">
                    <td><span class="num-pill">{{ $rota->staff_number }}</span></td>
                    <td><span class="ward-badge">Ward {{ $rota->ward_number }}</span></td>
                    <td><span class="shift-badge shift-{{ $rota->shift }}">{{ $rota->shift }}</span></td>
                    <td style="color:#8aabb8;">{{ \Carbon\Carbon::parse($rota->week_start_date)->format('d-M-y') }}</td>
                    <td>
                        <div class="action-row">
                            <a href="{{ route('staff-rota.edit', $rota->id) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('staff-rota.destroy', $rota->id) }}" method="POST"
                                  style="display:inline" onsubmit="return confirm('Remove this staff from rota?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state"><td colspan="5">No staff allocated yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterRota() {
    const ward  = document.getElementById('wardFilter').value;
    const shift = document.getElementById('shiftFilter').value;
    document.querySelectorAll('.rota-row').forEach(row => {
        const mw = ward  === 'all' || row.dataset.ward  === ward;
        const ms = shift === 'all' || row.dataset.shift === shift;
        row.style.display = (mw && ms) ? '' : 'none';
    });
}
</script>

@endsection