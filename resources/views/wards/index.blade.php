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

.attr-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 14px;
    margin-bottom: 1.5rem;
}

.attr-card {
    background: #03416E;
    border-radius: 12px;
    padding: 18px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(204,236,238,0.1);
}

.attr-card::before {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}

.attr-icon {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: rgba(204,236,238,0.12);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 10px;
    font-size: 1.2rem;
    color: #CCECEE;
}

.attr-label {
    font-size: 0.68rem;
    color: rgba(204,236,238,0.6);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 5px;
}

.attr-value { font-size: 1.8rem; font-weight: 700; color: #f0f7f8; line-height: 1; }
.attr-sub { font-size: 0.68rem; color: rgba(204,236,238,0.45); margin-top: 3px; }

.table-card {
    background: #032d4f;
    border-radius: 12px;
    border: 1px solid rgba(204,236,238,0.12);
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

.tc-title { font-size: 0.95rem; font-weight: 700; color: #f0f7f8; }
.tc-right { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

.tc-select {
    padding: 0.4rem 1.8rem 0.4rem 0.75rem;
    border: 1px solid rgba(204,236,238,0.2);
    border-radius: 7px;
    font-size: 0.8rem;
    color: #CCECEE;
    background: #021829;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23CCECEE' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    cursor: pointer;
    min-width: 120px;
    font-family: 'DM Sans', sans-serif;
}

.tc-select:focus { outline: none; border-color: #CCECEE; }

.btn-add {
    background: #03416E;
    color: #CCECEE;
    border: 1px solid rgba(204,236,238,0.3);
    border-radius: 10px;
    padding: 0.45rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    transition: background 0.2s, color 0.2s;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
}

.btn-add:hover {
    background: #CCECEE;
    color: #021829;
    text-decoration: none;
}

.data-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }

.data-table thead th {
    text-align: left;
    padding: 0.5rem 0.75rem;
    font-size: 0.68rem;
    font-weight: 600;
    color: rgba(204,236,238,0.5);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid rgba(204,236,238,0.1);
}

.data-table tbody tr {
    border-bottom: 1px solid rgba(204,236,238,0.07);
    transition: background 0.1s;
}
.data-table tbody tr:last-child { border-bottom: none; }
.data-table tbody tr:hover { background: rgba(3,65,110,0.3); }
.data-table tbody td { padding: 0.75rem 0.75rem; color: #f0f7f8; vertical-align: middle; }

.num-pill { font-weight: 700; color: #CCECEE; font-size: 0.9rem; }

.loc-badge {
    display: inline-block;
    padding: 0.18rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
}

.loc-a { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25); }
.loc-b { background: rgba(236,72,153,0.15); color: #f9a8d4; border: 1px solid rgba(236,72,153,0.25); }
.loc-c { background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.25); }
.loc-d { background: rgba(245,158,11,0.15); color: #fcd34d; border: 1px solid rgba(245,158,11,0.25); }
.loc-e { background: rgba(139,92,246,0.15); color: #c4b5fd; border: 1px solid rgba(139,92,246,0.25); }
.loc-f { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.25); }
.loc-default { background: rgba(204,236,238,0.08); color: #CCECEE; border: 1px solid rgba(204,236,238,0.15); }

.nurse-badge {
    display: inline-block;
    background: rgba(245,158,11,0.12);
    color: #fcd34d;
    border: 1px solid rgba(245,158,11,0.25);
    border-radius: 6px;
    padding: 0.18rem 0.55rem;
    font-size: 0.7rem;
    font-weight: 600;
}

.btn-edit {
    border: 1px solid rgba(251,191,36,0.3);
    color: #fbbf24;
    background: rgba(251,191,36,0.08);
    border-radius: 6px;
    padding: 0.2rem 0.6rem;
    font-size: 0.72rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s;
    display: inline-block;
    font-family: 'DM Sans', sans-serif;
}

.btn-edit:hover { background: rgba(251,191,36,0.15); color: #fbbf24; text-decoration: none; }

.btn-del {
    border: 1px solid rgba(248,113,113,0.3);
    color: #f87171;
    background: rgba(248,113,113,0.08);
    border-radius: 6px;
    padding: 0.2rem 0.6rem;
    font-size: 0.72rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s;
    font-family: 'DM Sans', sans-serif;
}

.btn-del:hover { background: rgba(248,113,113,0.15); }
.action-row { display: flex; gap: 0.4rem; }

.empty-state td { text-align: center; color: rgba(204,236,238,0.4); padding: 2rem; font-size: 0.85rem; }

.alert-success {
    background: rgba(16,185,129,0.1);
    border: 1px solid rgba(16,185,129,0.25);
    color: #6ee7b7;
    border-radius: 10px;
    padding: 0.7rem 1rem;
    margin-bottom: 1.2rem;
    font-size: 0.85rem;
    font-weight: 500;
}
</style>

<div class="wb-page">

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    <div class="attr-grid">
        <div class="attr-card">
            <div class="attr-icon"><i class="ti ti-building-hospital"></i></div>
            <div class="attr-label">Total Wards</div>
            <div class="attr-value">{{ $totalWards }}</div>
            <div class="attr-sub">All active</div>
        </div>
        <div class="attr-card">
            <div class="attr-icon"><i class="ti ti-bed"></i></div>
            <div class="attr-label">Total Beds Capacity</div>
            <div class="attr-value">{{ $totalBeds }}</div>
            <div class="attr-sub">Across all wards</div>
        </div>
        <div class="attr-card">
            <div class="attr-icon"><i class="ti ti-user-x"></i></div>
            <div class="attr-label">Occupied Beds</div>
            <div class="attr-value">{{ $occupiedBeds }}</div>
            <div class="attr-sub">Beds taken</div>
        </div>
        <div class="attr-card">
            <div class="attr-icon"><i class="ti ti-circle-check"></i></div>
            <div class="attr-label">Available Beds</div>
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
                    <th>Ward No.</th>
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
                    <td><span class="loc-badge {{ $bc }}">{{ $loc ?: 'N/A' }}</span></td>
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