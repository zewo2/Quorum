@extends('layouts.be_master')

@section('title', 'Edit Profile - Quorum')
@section('page-title', 'Edit Profile')

@section('content')
<div class="edit-profile-page">
    <div class="form-container">
        <div class="dashboard-card form-card">
            <div class="card-header">
                <h2>Update Your Profile</h2>
                <a href="{{ route('profile.show', $user) }}" class="card-link">← Back to profile</a>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <h4>Please fix the following errors:</h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update', $user) }}" method="POST" enctype="multipart/form-data" class="form">
                @csrf
                @method('PATCH')

                <!-- Profile Picture Section -->
                <div class="form-section">
                    <h3>Profile Picture</h3>
                    <div class="picture-upload-group">
                        <div class="picture-preview">
                            @if($user->profile_picture && file_exists(storage_path('app/public/' . $user->profile_picture)))
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" id="previewImage">
                            @else
                                <div class="picture-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="picture-upload-controls">
                            <label for="profile_picture" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Choose Image
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="display: none;" onchange="previewFile(this)">
                            <p class="form-hint">Max 2MB. JPG, PNG, GIF formats accepted.</p>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3>Personal Information</h3>

                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control">
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" placeholder="+1 (555) 123-4567">
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nif">NIF/Tax ID</label>
                            <input type="text" id="nif" name="nif" value="{{ old('nif', $user->nif) }}" class="form-control" placeholder="123456789">
                            @error('nif')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="form-control" placeholder="123 Main Street, City, State">
                        @error('address')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" class="form-control">
                        @error('date_of_birth')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('profile.show', $user) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.edit-profile-page {
    display: flex;
    justify-content: center;
    padding: var(--spacing-lg) 0;
}

.form-container {
    width: 100%;
    max-width: 600px;
}

.form-card {
    padding: var(--spacing-lg);
}

.form-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border-dark);
}

.form-card h2 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--text-dark);
}

.form-section {
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--border-dark);
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    color: var(--text-dark);
    font-weight: 600;
    margin: 0 0 var(--spacing-md) 0;
    font-size: 1.125rem;
}

.form {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

/* Picture Upload */
.picture-upload-group {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: var(--spacing-lg);
    align-items: center;
}

.picture-preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 2px solid var(--border-dark);
    overflow: hidden;
    background: var(--bg-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.picture-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.picture-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.picture-upload-controls {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.picture-upload-controls .btn {
    width: fit-content;
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.form-hint {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
    margin: 0;
}

/* Form Groups */
.form-group {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.form-group label {
    color: var(--text-dark);
    font-weight: 500;
    font-size: 0.95rem;
}

.required {
    color: #ef4444;
}

.form-control {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-size: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-control::placeholder {
    color: var(--text-dark-secondary);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
}

.error-message {
    color: #ef4444;
    font-size: 0.875rem;
}

.alert {
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert h4 {
    margin: 0 0 var(--spacing-sm) 0;
    font-weight: 600;
}

.alert ul {
    margin: 0;
    padding-left: var(--spacing-lg);
}

.alert li {
    margin-bottom: var(--spacing-xs);
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: flex-end;
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--border-dark);
    margin-top: var(--spacing-lg);
}

.form-actions .btn {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-lg);
}

@media (max-width: 640px) {
    .picture-upload-group {
        grid-template-columns: 1fr;
    }

    .picture-preview {
        margin: 0 auto;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

<script>
function previewFile(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImage');
            if (preview) {
                preview.src = e.target.result;
            } else {
                const placeholder = document.querySelector('.picture-placeholder');
                if (placeholder) {
                    const img = document.createElement('img');
                    img.id = 'previewImage';
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    placeholder.parentElement.replaceChild(img, placeholder);
                }
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
