@extends('layouts.be_master')

@section('title', 'Room Management - Quorum')
@section('page-title', 'Room Management')

@section('content')
<div class="admin-page">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Room Management</h1>
            <p>Manage room codes, buildings, and capacity</p>
        </div>
        <a href="{{ route('dashboard.admin.rooms.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Room
        </a>
    </div>

    <div class="dashboard-card filters-card">
        <form method="GET" action="{{ route('dashboard.admin.rooms.index') }}" class="filters-form">
            <div class="filters-row">
                <label class="field">
                    <span>Search Code</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="e.g., A-101">
                </label>
                <label class="field">
                    <span>Building</span>
                    <select name="building">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building }}" {{ request('building') === $building ? 'selected' : '' }}>
                                {{ $building }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="field">
                    <span>Capacity Min</span>
                    <input type="number" name="capacity_min" value="{{ request('capacity_min') }}" min="1" max="1000" placeholder="e.g., 20">
                </label>
                <label class="field">
                    <span>Capacity Max</span>
                    <input type="number" name="capacity_max" value="{{ request('capacity_max') }}" min="1" max="1000" placeholder="e.g., 100">
                </label>
                <div class="filters-actions">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="{{ route('dashboard.admin.rooms.index') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Rooms</h3>
            <span class="chip">{{ $rooms->total() }} rooms</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Building</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td><strong>{{ $room->code }}</strong></td>
                            <td>{{ $room->building ?? 'N/A' }}</td>
                            <td>{{ $room->capacity ?? 'N/A' }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('dashboard.admin.rooms.edit', $room) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('dashboard.admin.rooms.destroy', $room) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this room?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--text-dark-secondary);">
                                No rooms created yet. <a href="{{ route('dashboard.admin.rooms.create') }}">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rooms->hasPages())
            <div class="pagination-wrapper">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .filters-card {
        margin-bottom: var(--spacing-lg);
    }

    .filters-form {
        width: 100%;
    }

    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: var(--spacing-md);
        align-items: end;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        color: var(--text-dark-secondary);
    }

    .field input,
    .field select {
        padding: var(--spacing-sm) var(--spacing-md);
        border: 1px solid var(--border-dark);
        border-radius: var(--radius-md);
        background: var(--bg-dark);
        color: var(--text-dark);
    }

    .filters-actions {
        display: flex;
        gap: var(--spacing-sm);
        flex-wrap: wrap;
    }

    @media (max-width: 960px) {
        .filters-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@endsection
