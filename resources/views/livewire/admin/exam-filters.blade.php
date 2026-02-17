<div>
    <div class="dashboard-card filters-card">
        <div class="filters-row">
            <label class="field">
                <span>Course</span>
                <select wire:model.live="course">
                    <option value="">All Courses</option>
                    @foreach($courses as $courseItem)
                        <option value="{{ $courseItem->id }}">{{ $courseItem->name }}</option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span>Subject</span>
                <select wire:model.live="subject">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subjectItem)
                        <option value="{{ $subjectItem->id }}">{{ $subjectItem->name }}</option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span>Room</span>
                <select wire:model.live="room">
                    <option value="">All Rooms</option>
                    @foreach($rooms as $roomItem)
                        @php
                            $roomLabel = $roomItem->code;
                            if ($roomItem->building) {
                                $roomLabel .= ' • ' . $roomItem->building;
                            }
                            if ($roomItem->capacity) {
                                $roomLabel .= ' • ' . $roomItem->capacity . ' seats';
                            }
                        @endphp
                        <option value="{{ $roomItem->code }}">
                            {{ $roomLabel }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span>Date From</span>
                <input type="date" wire:model.live="dateFrom">
            </label>

            <label class="field">
                <span>Date To</span>
                <input type="date" wire:model.live="dateTo">
            </label>

            <div class="filters-actions">
                <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear</button>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Scheduled Exams</h3>
            <span class="chip">{{ $exams->total() }} entries</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td><strong>{{ $exam->subject?->name ?? 'N/A' }}</strong></td>
                            <td>
                                @php
                                    $examCourseNames = $exam->subject?->courses?->pluck('name')->join(', ');
                                    if (!$examCourseNames) {
                                        $examCourseNames = 'N/A';
                                    }
                                @endphp
                                {{ $examCourseNames }}
                            </td>
                            <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge">
                                    {{ substr($exam->start_time, 0, 5) }} - {{ substr($exam->end_time, 0, 5) }}
                                </span>
                            </td>
                            <td>{{ $exam->room ?? 'TBA' }}</td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('dashboard.admin.exams.edit', $exam) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('dashboard.admin.exams.destroy', $exam) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this exam?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-dark-secondary);">
                                No exams scheduled yet. <a href="{{ route('dashboard.admin.exams.create') }}">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exams->hasPages())
            <div class="pagination-wrapper">
                {{ $exams->links() }}
            </div>
        @endif
    </div>

    <style>
        .filters-card {
            margin-bottom: var(--spacing-lg);
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
</div>
