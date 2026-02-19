<div class="classes-page">
    <div class="dashboard-card filters-card">
        <div class="filters-left">
            <label class="field">
                <span>Search classes</span>
                <input
                    type="text"
                    placeholder="Search by course or code"
                    wire:model.live="search"
                >
            </label>
            <label class="field">
                <span>Status</span>
                <select wire:model.live="status">
                    <option value="all">All classes</option>
                    <option value="active">Active only</option>
                </select>
            </label>
        </div>
        <div class="filters-actions">
            <button type="button" class="btn btn-secondary" wire:click="resetFilters">Reset</button>
            <a href="{{ route('dashboard.teacher.schedule') }}" class="btn btn-primary">Full schedule</a>
        </div>
    </div>

    @if($rosterData)
        <div class="modal-overlay" wire:click="closeRoster">
            <div class="modal-panel" wire:click.stop>
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title">{{ $rosterData['subject_name'] }}</h2>
                        <p class="modal-subtitle">{{ $rosterData['subject_code'] }} • {{ $rosterData['student_count'] }} students</p>
                    </div>
                    <button type="button" class="close-button" wire:click="closeRoster">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="modal-body">
                    @if($rosterData['students'])
                        <div class="roster-table">
                            <div class="roster-header">
                                <div class="col-name">Name</div>
                                <div class="col-email">Email</div>
                                <div class="col-status">Status</div>
                                <div class="col-grade">Grade</div>
                            </div>
                            @foreach($rosterData['students'] as $student)
                                <div class="roster-row">
                                    <div class="col-name">
                                        <strong>{{ $student['name'] }}</strong>
                                    </div>
                                    <div class="col-email">
                                        <span style="color: var(--text-dark-secondary); font-size: 0.875rem;">{{ $student['email'] }}</span>
                                    </div>
                                    <div class="col-status">
                                        <span style="background: {{ $student['status'] === 'active' ? 'rgba(16, 185, 129, 0.12)' : 'rgba(107, 114, 128, 0.12)' }}; color: {{ $student['status'] === 'active' ? '#10b981' : '#6b7280' }}; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem;">{{ ucfirst($student['status']) }}</span>
                                    </div>
                                    <div class="col-grade" style="text-align: right;">
                                        <div class="grade-input-wrapper">
                                            <input
                                                type="number"
                                                min="0"
                                                max="20"
                                                step="0.5"
                                                placeholder="—"
                                                value="{{ $student['final_grade'] ?? '' }}"
                                                wire:change="updateStudentGrade({{ $student['enrollment_id'] }}, $event.target.value)"
                                                class="grade-input"
                                            >
                                            <span class="grade-scale">/20</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p style="text-align: center; color: var(--text-dark-secondary); padding: var(--spacing-lg);">No students enrolled</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="dashboard-grid cards-grid">
        @forelse($teacherSubjects as $subject)
            @php
                $subjectCourses = $subject->courses;
                $courseNames = $subjectCourses->pluck('name')->join(', ');
                $studentCount = $subjectCourses->sum(fn($course) => $course->enrollments->count());
            @endphp
            <div class="dashboard-card class-card">
                <div class="card-header">
                    <div>
                        <p class="eyebrow">{{ $subject->code ?? 'N/A' }}</p>
                        <h3>{{ $subject->name }}</h3>
                    </div>
                    <span class="badge badge-success">{{ $studentCount }} students</span>
                </div>
                <p class="card-sub">{{ $courseNames ?: 'General Subject' }}</p>
                <div class="meta-row">
                    <div class="meta">
                        <span>Courses</span>
                        <strong>{{ $subjectCourses->count() }}</strong>
                    </div>
                    <div class="meta">
                        <span>Status</span>
                        <strong>{{ ucfirst($subject->pivot->status ?? 'active') }}</strong>
                    </div>
                </div>
                <div class="class-actions">
                    <a href="{{ route('dashboard.teacher.attendance', ['subject' => $subject->id]) }}" class="btn btn-secondary">Attendance</a>
                    <button type="button" wire:click="viewRoster({{ $subject->id }})" class="btn btn-primary">View roster</button>
                </div>
            </div>
        @empty
            <div class="dashboard-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <p style="color: var(--text-dark-secondary);">No classes assigned. Contact administration to assign courses.</p>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
.classes-page {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.filters-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-lg);
    flex-wrap: wrap;
}

.filters-left {
    display: flex;
    gap: var(--spacing-md);
    flex: 1;
    min-width: 300px;
}

.filters-actions {
    display: flex;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

/* Form Field Styles */
.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field span {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-dark-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.field input,
.field select {
    padding: 10px 12px;
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-size: 0.9375rem;
    font-family: inherit;
    transition: all 0.2s ease;
}

.field input::placeholder {
    color: var(--text-dark-secondary);
}

.field input:focus,
.field select:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--bg-dark);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.field select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 36px;
}

.field select:hover {
    border-color: var(--primary);
}

.field select option {
    background: var(--bg-dark-secondary);
    color: var(--text-dark);
    padding: 8px;
}

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--spacing-lg);
}

.class-card {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--spacing-md);
}

.card-header h3 {
    margin: 0;
    color: var(--text-dark);
}

.eyebrow {
    margin: 0 0 4px 0;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--text-dark-secondary);
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.card-sub {
    margin: 0;
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
}

.meta-row {
    display: flex;
    gap: var(--spacing-md);
    padding: var(--spacing-md) 0;
    border-top: 1px solid var(--border-dark);
    border-bottom: 1px solid var(--border-dark);
}

.meta {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.meta span {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--text-dark-secondary);
}

.meta strong {
    color: var(--text-dark);
    font-size: 1rem;
}

.class-actions {
    display: flex;
    gap: var(--spacing-md);
}

.class-actions .btn {
    flex: 1;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: var(--spacing-lg);
}

.modal-panel {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    max-width: 900px;
    width: 100%;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-lg);
    border-bottom: 1px solid var(--border-dark);
}

.modal-title {
    margin: 0;
    color: var(--text-dark);
    font-size: 1.5rem;
}

.modal-subtitle {
    margin: 4px 0 0 0;
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
}

.close-button {
    background: none;
    border: none;
    color: var(--text-dark-secondary);
    cursor: pointer;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-md);
    transition: all 0.2s ease;
}

.close-button:hover {
    background: var(--border-dark);
    color: var(--text-dark);
}

.modal-body {
    padding: var(--spacing-lg);
    overflow-y: auto;
}

.roster-table {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.roster-header,
.roster-row {
    display: grid;
    grid-template-columns: 1.5fr 2fr 1fr 1fr;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    align-items: center;
}

.roster-header {
    background: var(--bg-dark);
    border-radius: 4px 4px 0 0;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-dark-secondary);
    border-bottom: 2px solid var(--border-dark);
}

.roster-row {
    border-bottom: 1px solid var(--border-dark);
    transition: background 0.2s ease;
}

.roster-row:last-child {
    border-bottom: none;
    border-radius: 0 0 4px 4px;
}

.roster-row:hover {
    background: var(--bg-dark);
}

.col-name,
.col-email,
.col-status,
.col-grade {
    overflow: hidden;
}

/* Grade Input Styling */
.grade-input-wrapper {
    display: flex;
    align-items: center;
    gap: 4px;
    position: relative;
}

.grade-input {
    width: 60px;
    padding: 8px 10px;
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-size: 0.9375rem;
    font-weight: 600;
    text-align: center;
    transition: all 0.2s ease;
}

.grade-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.grade-input:hover {
    border-color: var(--primary);
}

.grade-input::placeholder {
    color: var(--text-dark-secondary);
}

.grade-scale {
    color: var(--text-dark-secondary);
    font-size: 0.875rem;
    white-space: nowrap;
}

/* Remove spinner buttons from number input */
.grade-input::-webkit-outer-spin-button,
.grade-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.grade-input[type=number] {
    -moz-appearance: textfield;
}

@media (max-width: 768px) {
    .filters-left {
        min-width: 100%;
    }

    .cards-grid {
        grid-template-columns: 1fr;
    }

    .roster-header,
    .roster-row {
        grid-template-columns: 1fr;
        gap: var(--spacing-sm);
    }

    .col-name,
    .col-email,
    .col-status,
    .col-grade {
        padding-left: var(--spacing-md);
    }

    .col-name::before,
    .col-email::before,
    .col-status::before,
    .col-grade::before {
        content: attr(data-label);
        font-weight: 600;
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-bottom: 4px;
        color: var(--text-dark-secondary);
    }
}
</style>
@endpush
