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

.attr-card.red   { background: #a83030; }
.attr-card.green { background: #2a7a50; }

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
    min-width: 120px;
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

.loc-badge {
    display: inline-block;
    padding: 0.18rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
}

.loc-a { background:#dbeafe; color:#1d4ed8; }
.loc-b { background:#fce7f3; color:#be185d; }
.loc-c { background:#d1fae5; color:#065f46; }
.loc-d { background:#fef3c7; color:#92400e; }
.loc-e { background:#ede9fe; color:#5b21b6; }
.loc-f { background:#fde8e8; color:#991b1b; }
.loc-default { background:#f3f4f6; color:#6b7280; }

.nurse-badge {
    display: inline-block;
    background: #fff4e8;
    color: #a05010;
    border: 1px solid #fed7aa;
    border-radius: 6px;
    padding: 0.18rem 0.55rem;
    font-size: 0.7rem;
    font-weight: 600;
}

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
            <div class="attr-icon"><i class="ti ti-building-hospital"></i></div>
            <div class="attr-label">Total wards</div>
            <div class="attr-value">{{ $totalWards }}</div>
            <div class="attr-sub">All active</div>
        </div>
        <div class="attr-card">
            <div class="attr-icon"><i class="ti ti-bed"></i></div>
            <div class="attr-label">Total beds capacity</div>
            <div class="attr-value">{{ $totalBeds }}</div>
            <div class="attr-sub">Across all wards</div>
        </div>
        <div class="attr-card red">
            <div class="attr-icon"><i class="ti ti-user-x"></i></div>
            <div class="attr-label">Occupied beds</div>
            <div class="attr-value">{{ $occupiedBeds }}</div>
            <div class="attr-sub">Beds taken</div>
        </div>
        <div class="attr-card green">
            <div class="attr-icon"><i class="ti ti-circle-check"></i></div>
            <div class="attr-label">Available beds</div>
            <div class="attr-value">{{ $availableBeds }}</div>
            <div class="attr-sub">Open now</div>
        </div>
    </div>

    <div class="table-card">
        <div class="tc-header">
            <span class="tc-title">Ward list</span>
            <div class="tc-right">
                <select class="tc-select" id="blockFilter" onchange="filterWards(this.value)">
                    <option value="all">All Blocks</option>
                    <option value="Block A">Block A</option>
                    <option value="Block B">Block B</option>
                    <option value="Block C">Block C</option>
                    <option value="Block D">Block D</option>
                    <option value="Block E">Block E</option>
                    <option value="Block F">Block F</option>
                </select>
                <a href="{{ route('wards.create') }}" class="btn-add">+ Add ward</a>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Ward no.</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Beds</th>
                    <th>Nurse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ward-tbody">
                @forelse($wards as $ward)
                @php
                    $loc = $ward->location ?? '';
                    if (str_contains($loc, 'A'))      $bc = 'loc-a';
                    elseif (str_contains($loc, 'B'))  $bc = 'loc-b';
                    elseif (str_contains($loc, 'C'))  $bc = 'loc-c';
                    elseif (str_contains($loc, 'D'))  $bc = 'loc-d';
                    elseif (str_contains($loc, 'E'))  $bc = 'loc-e';
                    elseif (str_contains($loc, 'F'))  $bc = 'loc-f';
                    else                               $bc = 'loc-default';
                @endphp
                <tr class="ward-row" data-location="{{ $loc }}">
                    <td><span class="num-pill">{{ $ward->ward_number }}</span></td>
                    <td>{{ $ward->ward_name }}</td>
                    <td><span class="loc-badge {{ $bc }}">{{ $loc }}</span></td>
                    <td>{{ $ward->total_beds }}</td>
                    <td><span class="nurse-badge">{{ $ward->charge_nurse_number ?? 'N/A' }}</span></td>
                    <td>
                        <div class="action-row">
                            <a href="{{ route('wards.edit', $ward->ward_number) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('wards.destroy', $ward->ward_number) }}" method="POST"
                                  style="display:inline" onsubmit="return confirm('Delete this ward?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-state"><td colspan="6">No wards found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterWards(val) {
    document.querySelectorAll('.ward-row').forEach(r => {
        r.style.display = (val === 'all' || r.dataset.location === val) ? '' : 'none';
    });
}
</script>

@endsection