@extends('layouts.be_master')

@section('title', 'GA Timetable Scheduling - Quorum')
@section('page-title', 'GA Timetable Scheduling')

@section('content')
<div class="admin-page">
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    @if(($gaRun?->status ?? null) === 'running' || ($gaRun?->status ?? null) === 'queued')
        <div class="alert alert-success" id="gaRunStatusAlert" style="background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.35); color: #bfdbfe;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 6v6l4 2"></path>
            </svg>
            <span id="gaRunStatusText">Generation is {{ $gaRun->status }}... {{ (int) $gaRun->progress }}%</span>
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Genetic Algorithm Scheduler</h1>
            <p>Generate optimal month-wide schedules using AI</p>
        </div>
        <a href="{{ route('dashboard.admin.timetables.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Timetables
        </a>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Schedule Parameters</h3>
        </div>
        <div class="card-body">
            <div class="setup-info" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="padding: 1rem; background: rgba(99, 102, 241, 0.1); border-radius: 0.5rem;">
                    <p style="color: var(--text-dark-secondary); font-size: 0.875rem; margin: 0 0 0.25rem 0; text-transform: uppercase; letter-spacing: 0.05em;">Schedule Days</p>
                    <p style="color: var(--text-dark); font-weight: 600; margin: 0;">Monday - Saturday</p>
                </div>
                <div style="padding: 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 0.5rem;">
                    <p style="color: var(--text-dark-secondary); font-size: 0.875rem; margin: 0 0 0.25rem 0; text-transform: uppercase; letter-spacing: 0.05em;">Time Slots</p>
                    <p style="color: var(--text-dark); font-weight: 600; margin: 0;">4 slots/day (2h each)</p>
                </div>
                <div style="padding: 1rem; background: rgba(245, 158, 11, 0.1); border-radius: 0.5rem;">
                    <p style="color: var(--text-dark-secondary); font-size: 0.875rem; margin: 0 0 0.25rem 0; text-transform: uppercase; letter-spacing: 0.05em;">ECTS Rule</p>
                    <p style="color: var(--text-dark); font-weight: 600; margin: 0;">50h per 3 ECTS</p>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.admin.timetables.ga.generate') }}" class="ga-params-form" style="display: grid; gap: 1rem;">
                @csrf
                <div class="ga-inline-fields" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    <label class="field">
                        <span>Month</span>
                        <input type="month" name="month" value="{{ old('month', $selectedMonth ?? now()->format('Y-m')) }}" required>
                    </label>

                    <div class="field ga-mode-field">
                        <span>GA Mode</span>
                        @php
                            $selectedModeValue = old('mode', $selectedMode ?? 'normal');
                        @endphp
                        <select name="mode" required>
                            <option value="relaxed" {{ $selectedModeValue === 'relaxed' ? 'selected' : '' }}>Relaxed mode (20h/week soft target)</option>
                            <option value="normal" {{ $selectedModeValue === 'normal' ? 'selected' : '' }}>Normal mode (40h/week soft target)</option>
                            <option value="emergency" {{ $selectedModeValue === 'emergency' ? 'selected' : '' }}>Emergency mode (max fill, no conflicts)</option>
                        </select>
                    </div>
                </div>

                <div class="ga-inline-fields" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    <label class="field">
                        <span>Course Year</span>
                        <select name="selected_year">
                            <option value="">All years</option>
                            @for($year = 1; $year <= 4; $year++)
                                <option value="{{ $year }}" {{ (int) old('selected_year', $selectedYear ?? 0) === $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </label>

                    <label class="field">
                        <span>Semester</span>
                        <select name="selected_semester">
                            <option value="">All semesters</option>
                            <option value="1" {{ (int) old('selected_semester', $selectedSemester ?? 0) === 1 ? 'selected' : '' }}>1</option>
                            <option value="2" {{ (int) old('selected_semester', $selectedSemester ?? 0) === 2 ? 'selected' : '' }}>2</option>
                        </select>
                    </label>
                </div>

                <div>
                    <p style="margin-bottom: 0.5rem;"><strong>Select Courses</strong></p>
                    @php
                        $checkedCourses = old('selected_course_ids', $selectedCourseIds ?? []);
                    @endphp
                    <details class="courses-dropdown @error('selected_course_ids') is-invalid @enderror" id="gaCourseDropdown">
                        <summary>
                            <span id="gaCourseDropdownLabel">
                                {{ count($checkedCourses) > 0 ? count($checkedCourses) . ' course(s) selected' : 'Select one or more courses' }}
                            </span>
                        </summary>
                        <div class="courses-options">
                            @foreach($courses as $course)
                                <label class="course-option">
                                    <input type="checkbox" class="ga-course-checkbox" name="selected_course_ids[]" value="{{ $course->id }}" {{ in_array($course->id, $checkedCourses) ? 'checked' : '' }}>
                                    <span>{{ $course->name }} ({{ $course->code }})</span>
                                </label>
                            @endforeach
                        </div>
                    </details>
                    @error('selected_course_ids')
                        <span class="error-message" style="display: block; margin-top: 0.35rem;">{{ $message }}</span>
                    @enderror
                    @error('selected_course_ids.*')
                        <span class="error-message" style="display: block; margin-top: 0.35rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <p style="margin-bottom: 0.5rem;"><strong>Select Classes (optional)</strong></p>
                    @php
                        $checkedClasses = old('selected_teacher_subject_ids', $selectedTeacherSubjectIds ?? []);
                    @endphp
                    <details class="courses-dropdown" id="gaClassDropdown">
                        <summary>
                            <span id="gaClassDropdownLabel">
                                {{ count($checkedClasses) > 0 ? count($checkedClasses) . ' class(es) selected' : 'Select classes (optional)' }}
                            </span>
                        </summary>
                        <div id="ga-classes-container" class="courses-options">
                            @foreach($teacherSubjects as $teacherSubject)
                                @php
                                    $meta = $hoursMeta[$teacherSubject->id] ?? null;
                                    $remaining = $meta['remaining_hours'] ?? 0;
                                    $scheduled = $meta['scheduled_hours'] ?? 0;
                                    $maxHours = $meta['max_hours'] ?? 0;
                                    $disabled = !$meta || !$meta['selectable'];
                                    $subjectYear = (int) ($teacherSubject->subject?->year ?? 0);
                                    $subjectSemester = (int) ($teacherSubject->subject?->semester ?? 0);

                                    // Collect course IDs from both pivot and legacy mapping
                                    $courseIds = $teacherSubject->subject?->courses?->pluck('id')->map(fn ($id) => (int) $id)->all() ?? [];
                                    $primaryCourseId = (int) ($teacherSubject->subject?->course_id ?? 0);
                                    if ($primaryCourseId > 0 && !in_array($primaryCourseId, $courseIds, true)) {
                                        $courseIds[] = $primaryCourseId;
                                    }

                                    // Collect course names from both pivot and legacy
                                    $courseNames = $teacherSubject->subject?->courses?->pluck('name')->all() ?? [];
                                    $primaryCourseName = $teacherSubject->subject?->course?->name;
                                    if ($primaryCourseName && !in_array($primaryCourseName, $courseNames, true)) {
                                        $courseNames[] = $primaryCourseName;
                                    }
                                    $courseNames = implode(', ', $courseNames);
                                    if (!$courseNames) {
                                        $courseNames = 'N/A';
                                    }
                                @endphp
                                <label class="ga-class-row" data-course-ids="{{ implode(',', $courseIds) }}" data-subject-year="{{ $subjectYear }}" data-subject-semester="{{ $subjectSemester }}" style="display: flex; align-items: center; gap: 0.5rem; opacity: {{ $disabled ? '0.55' : '1' }};">
                                    <input
                                        type="checkbox"
                                        class="ga-class-checkbox"
                                        name="selected_teacher_subject_ids[]"
                                        value="{{ $teacherSubject->id }}"
                                        {{ in_array($teacherSubject->id, $checkedClasses) ? 'checked' : '' }}
                                        {{ $disabled ? 'disabled' : '' }}
                                    >
                                    <span>
                                        {{ $courseNames }} ·
                                        {{ $teacherSubject->subject?->name ?? 'N/A' }} ·
                                        Y{{ $subjectYear }} / S{{ $subjectSemester }} ·
                                        {{ $teacherSubject->teacher?->name ?? 'N/A' }}
                                        ({{ $scheduled }}/{{ $maxHours }}h, remaining {{ $remaining }}h)
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </details>
                    <small style="color: var(--text-dark-secondary);">Classes with no remaining legal hours are disabled.</small>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="gaGenerateButton"
                    {{ (($gaRun?->status ?? null) === 'running' || ($gaRun?->status ?? null) === 'queued') ? 'disabled' : '' }}
                >
                    {{ (($gaRun?->status ?? null) === 'running' || ($gaRun?->status ?? null) === 'queued') ? 'Generation in progress...' : 'Generate Schedule' }}
                </button>
            </form>
        </div>
    </div>

    @if(!empty($stats))
        <div class="dashboard-card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>Generation Summary</h3>
            </div>
            <div class="card-body" style="padding: 1rem 1.5rem;">
                <p><strong>Generated entries:</strong> {{ $stats['generated'] ?? 0 }}</p>
                <p><strong>Selected courses:</strong> {{ $stats['courses_selected'] ?? 0 }}</p>
                <p><strong>Selected classes:</strong> {{ $stats['classes_selected'] ?? 0 }}</p>
                <p><strong>Month:</strong> {{ $stats['month'] ?? '-' }}</p>
                <p><strong>Mode:</strong> {{ ucfirst($stats['mode'] ?? ($selectedMode ?? 'normal')) }}</p>
                <p><strong>Year/Semester:</strong> {{ $stats['year'] ?? ($selectedYear ?? 'All') }} / {{ $stats['semester'] ?? ($selectedSemester ?? 'All') }}</p>
            </div>
        </div>
    @endif

    <div class="dashboard-card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h3>Preview</h3>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($preview as $entry)
                        <tr>
                            <td>{{ $entry['course'] }}</td>
                            <td>{{ $entry['teacher'] }}</td>
                            <td>{{ $entry['subject'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($entry['class_date'])->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($entry['class_date'])->format('j') }} - {{ $entry['day_of_week'] }}</td>
                            <td>
                                <span class="badge">
                                    {{ $entry['start_time'] }} - {{ $entry['end_time'] }}
                                </span>
                            </td>
                            <td>{{ $entry['room'] ?? 'TBD' }}</td>
                            <td>{{ $entry['capacity'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-dark-secondary);">
                                No generated schedule yet. Click "Generate Schedule" to start.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(!empty($preview))
            <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; gap: 1rem;">
                <form method="POST" action="{{ route('dashboard.admin.timetables.ga.apply') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Apply Schedule</button>
                </form>
                <form method="POST" action="{{ route('dashboard.admin.timetables.ga.generate') }}">
                    @csrf
                    <input type="hidden" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}">
                    <input type="hidden" name="mode" value="{{ $selectedMode ?? 'normal' }}">
                    <input type="hidden" name="selected_year" value="{{ $selectedYear ?? '' }}">
                    <input type="hidden" name="selected_semester" value="{{ $selectedSemester ?? '' }}">
                    @foreach(($selectedCourseIds ?? []) as $courseId)
                        <input type="hidden" name="selected_course_ids[]" value="{{ $courseId }}">
                    @endforeach
                    @foreach(($selectedTeacherSubjectIds ?? []) as $teacherSubjectId)
                        <input type="hidden" name="selected_teacher_subject_ids[]" value="{{ $teacherSubjectId }}">
                    @endforeach
                    <button type="submit" class="btn btn-secondary">Regenerate</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .ga-params-form .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
        color: var(--text-dark-secondary);
        font-size: 0.9rem;
    }

    .ga-params-form .field input,
    .ga-params-form .field select {
        padding: var(--spacing-sm) var(--spacing-md);
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        border-radius: var(--radius-md);
        color: var(--text-dark);
        width: 100%;
        font-family: inherit;
        font-size: 0.95rem;
    }

    .ga-params-form .field input:focus,
    .ga-params-form .field select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .ga-params-form .field select option {
        background: var(--bg-dark);
        color: var(--text-dark);
        padding: 8px;
    }

    .ga-params-form .field select option:checked {
        background: var(--primary);
        color: white;
    }

    .courses-dropdown {
        border: 1px solid var(--border-dark);
        border-radius: var(--radius-md);
        background: var(--bg-dark);
    }

    .courses-dropdown summary {
        list-style: none;
        cursor: pointer;
        padding: var(--spacing-sm) var(--spacing-md);
        color: var(--text-dark);
    }

    .courses-dropdown summary::-webkit-details-marker {
        display: none;
    }

    .courses-dropdown[open] summary {
        border-bottom: 1px solid var(--border-dark);
    }

    .courses-options {
        max-height: 180px;
        overflow: auto;
        padding: var(--spacing-sm) var(--spacing-md);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xs);
    }

    .class-options {
        max-height: 280px;
        overflow: auto;
        padding: var(--spacing-sm) var(--spacing-md);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xs);
    }

    .course-option {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        font-size: 0.875rem;
    }

    .courses-dropdown.is-invalid {
        border-color: var(--danger);
    }

    .error-message {
        color: var(--danger);
        font-size: 0.8125rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const gaRunStatus = @json($gaRun->status ?? null);
        const gaStatusUrl = @json(route('dashboard.admin.timetables.ga.status'));
        const gaRunStatusText = document.getElementById('gaRunStatusText');

        const courseDropdown = document.getElementById('gaCourseDropdown');
        const courseDropdownLabel = document.getElementById('gaCourseDropdownLabel');
        const classDropdownLabel = document.getElementById('gaClassDropdownLabel');
        const courseCheckboxes = Array.from(document.querySelectorAll('.ga-course-checkbox'));
        const classCheckboxes = Array.from(document.querySelectorAll('.ga-class-checkbox'));
        const classRows = Array.from(document.querySelectorAll('.ga-class-row'));
        const selectedYearSelect = document.querySelector('select[name="selected_year"]');
        const selectedSemesterSelect = document.querySelector('select[name="selected_semester"]');

        const updateClassDropdownLabel = () => {
            if (!classDropdownLabel) return;
            const selectedClasses = classCheckboxes.filter((checkbox) => checkbox.checked).length;
            classDropdownLabel.textContent = selectedClasses > 0
                ? `${selectedClasses} class(es) selected`
                : 'Select classes (optional)';
        };

        const applyCourseFilter = () => {
            const selectedCourseIds = new Set(
                courseCheckboxes
                    .filter((checkbox) => checkbox.checked)
                    .map((checkbox) => checkbox.value)
            );

            classRows.forEach((row) => {
                const rowCourseIds = (row.getAttribute('data-course-ids') || '')
                    .split(',')
                    .filter((id) => id !== '');
                const rowYear = row.getAttribute('data-subject-year') || '';
                const rowSemester = row.getAttribute('data-subject-semester') || '';

                const selectedYear = selectedYearSelect?.value || '';
                const selectedSemester = selectedSemesterSelect?.value || '';

                const matchesSelectedCourse = rowCourseIds.some((id) => selectedCourseIds.has(id));
                const matchesYear = selectedYear === '' || rowYear === selectedYear;
                const matchesSemester = selectedSemester === '' || rowSemester === selectedSemester;
                const shouldShow = (selectedCourseIds.size === 0 || matchesSelectedCourse) && matchesYear && matchesSemester;

                row.style.display = shouldShow ? 'flex' : 'none';

                if (!shouldShow) {
                    const classCheckbox = row.querySelector('.ga-class-checkbox');
                    if (classCheckbox) {
                        classCheckbox.checked = false;
                    }
                }
            });

            if (courseDropdownLabel) {
                courseDropdownLabel.textContent = selectedCourseIds.size > 0
                    ? `${selectedCourseIds.size} course(s) selected`
                    : 'Select one or more courses';
            }

            updateClassDropdownLabel();
        };

        courseCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', applyCourseFilter);
        });

        if (selectedYearSelect) {
            selectedYearSelect.addEventListener('change', applyCourseFilter);
        }

        if (selectedSemesterSelect) {
            selectedSemesterSelect.addEventListener('change', applyCourseFilter);
        }

        classCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateClassDropdownLabel);
        });

        applyCourseFilter();

        if (gaRunStatus === 'queued' || gaRunStatus === 'running') {
            const pollStatus = () => {
                fetch(gaStatusUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (gaRunStatusText && (data.status === 'queued' || data.status === 'running')) {
                            gaRunStatusText.textContent = `Generation is ${data.status}... ${data.progress}%`;
                        }

                        if (data.status === 'completed') {
                            window.location.reload();
                            return;
                        }

                        if (data.status === 'failed') {
                            const message = data.error_message || 'Generation failed. Please try again.';
                            if (gaRunStatusText) {
                                gaRunStatusText.textContent = message;
                            }
                            return;
                        }

                        setTimeout(pollStatus, 2500);
                    })
                    .catch(() => {
                        setTimeout(pollStatus, 4000);
                    });
            };

            setTimeout(pollStatus, 1200);
        }
    });
</script>
@endpush
