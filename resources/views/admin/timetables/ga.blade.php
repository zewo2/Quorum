@extends('layouts.be_master')

@section('title', 'GA Timetable Scheduling - Quorum')
@section('page-title', 'GA Timetable Scheduling')

@section('content')
<div class="admin-page">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="page-header">
        <div>
            <h1>Genetic Algorithm Scheduling</h1>
            <p>Generate month-wide schedules for selected courses and selected classes.</p>
        </div>
        <a href="{{ route('dashboard.admin.timetables.index') }}" class="btn btn-secondary">Back to Timetables</a>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Generation Setup</h3>
        </div>
        <div class="card-body" style="padding: 1rem 1.5rem;">
            <p><strong>Days:</strong> Monday to Saturday</p>
            <p><strong>Slots:</strong> 09:00-11:00, 11:00-13:00, 14:00-16:00, 16:00-18:00</p>
            <p><strong>ECTS Rule:</strong> 3 ECTS = 50h, 6 ECTS = 100h (others scaled proportionally).</p>

            <form method="POST" action="{{ route('dashboard.admin.timetables.ga.generate') }}" style="margin-top: 1rem; display: grid; gap: 1rem;">
                @csrf
                <label class="field">
                    <span>Month</span>
                    <input type="month" name="month" value="{{ old('month', $selectedMonth ?? now()->format('Y-m')) }}" required>
                </label>

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
                        <div id="ga-classes-container" class="class-options">
                            @foreach($teacherSubjects as $teacherSubject)
                                @php
                                    $meta = $hoursMeta[$teacherSubject->id] ?? null;
                                    $remaining = $meta['remaining_hours'] ?? 0;
                                    $scheduled = $meta['scheduled_hours'] ?? 0;
                                    $maxHours = $meta['max_hours'] ?? 0;
                                    $disabled = !$meta || !$meta['selectable'];

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
                                <label class="ga-class-row" data-course-ids="{{ implode(',', $courseIds) }}" style="display: flex; align-items: center; gap: 0.5rem; opacity: {{ $disabled ? '0.55' : '1' }};">
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
                                        {{ $teacherSubject->teacher?->name ?? 'N/A' }}
                                        ({{ $scheduled }}/{{ $maxHours }}h, remaining {{ $remaining }}h)
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </details>
                    <small style="color: var(--text-dark-secondary);">Classes with no remaining legal hours are disabled.</small>
                </div>

                <button type="submit" class="btn btn-primary">Generate Schedule</button>
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
                            <td>{{ $entry['day_of_week'] }}</td>
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
        const courseDropdown = document.getElementById('gaCourseDropdown');
        const courseDropdownLabel = document.getElementById('gaCourseDropdownLabel');
        const classDropdownLabel = document.getElementById('gaClassDropdownLabel');
        const courseCheckboxes = Array.from(document.querySelectorAll('.ga-course-checkbox'));
        const classCheckboxes = Array.from(document.querySelectorAll('.ga-class-checkbox'));
        const classRows = Array.from(document.querySelectorAll('.ga-class-row'));

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

                const matchesSelectedCourse = rowCourseIds.some((id) => selectedCourseIds.has(id));
                const shouldShow = selectedCourseIds.size === 0 || matchesSelectedCourse;

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

        classCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateClassDropdownLabel);
        });

        applyCourseFilter();
    });
</script>
@endpush
