@extends('layouts.be_master')

@section('title', 'Create Room - Quorum')
@section('page-title', 'Create Room')

@section('content')
<div class="admin-page">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Add Room</h1>
            <p>Create a new room record</p>
        </div>
        <a href="{{ route('dashboard.admin.rooms.index') }}" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="dashboard-card form-card">
        <form action="{{ route('dashboard.admin.rooms.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field-group">
                    <label class="field">
                        <span>Room Code <span class="required">*</span></span>
                        <input type="text" name="code" value="{{ old('code') }}" required maxlength="50" placeholder="e.g., A-101">
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Building</span>
                        <input type="text" name="building" value="{{ old('building') }}" maxlength="100" placeholder="e.g., Block A">
                    </label>
                </div>

                <div class="field-group">
                    <label class="field">
                        <span>Capacity</span>
                        <input type="number" name="capacity" value="{{ old('capacity') }}" min="1" max="1000" placeholder="e.g., 30">
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard.admin.rooms.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Room</button>
            </div>
        </form>
    </div>
</div>
@endsection
