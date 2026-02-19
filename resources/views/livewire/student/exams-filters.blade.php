<div class="exams-page">
    <div class="dashboard-card header-section">
        <div class="header-left">
            <label class="field">
                <span>Filter by Status</span>
                <select wire:model.live="status">
                    <option value="all">All Courses</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                </select>
            </label>
            <label class="field">
                <span>Sort by</span>
                <select wire:model.live="sort">
                    <option value="status">Status</option>
                    <option value="course_name">Course Name</option>
                    <option value="grade">Grade</option>
                </select>
            </label>
            <div class="filters-actions">
                <button type="button" class="btn btn-secondary" wire:click="resetFilters">Clear</button>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary">Back to Subjects</a>
        </div>
    </div>

    <div class="exams-grid">
        @forelse($enrolledCourses as $enrollment)
            @php
                $nextExam = $nextExamByCourse->get($enrollment->course_id);
                $isUpcoming = $nextExam && $nextExam->exam_date->startOfDay()->gte($today);
            @endphp
            <div class="exam-card exam-{{ $enrollment->status }}">
                <div class="card-header-exam">
                    <div>
                        <h4>{{ $enrollment->course->name }}</h4>
                        <span class="course-code">{{ $enrollment->course->code ?? 'N/A' }}</span>
                    </div>
                    <span class="exam-badge exam-{{ $enrollment->status }}">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </div>

                <div class="exam-details">
                    <div class="detail-row">
                        <span class="detail-icon">📚</span>
                        <div>
                            <span class="detail-label">Department</span>
                            <p class="detail-value">{{ $enrollment->course->department }}</p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-icon">📊</span>
                        <div>
                            <span class="detail-label">Course Length</span>
                            <p class="detail-value">{{ $enrollment->course->total_years }} years</p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-icon">🏷️</span>
                        <div>
                            <span class="detail-label">Status</span>
                            <p class="detail-value">{{ ucfirst($enrollment->status) }}</p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-icon">📝</span>
                        <div>
                            <span class="detail-label">Description</span>
                            <p class="detail-value">{{ \Illuminate\Support\Str::limit($enrollment->course->description, 50) }}</p>
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-icon">🗓️</span>
                        <div>
                            <span class="detail-label">Next Exam</span>
                            @if($nextExam)
                                <p class="detail-value">
                                    {{ $nextExam->subject?->name ?? 'Subject' }} • {{ $nextExam->exam_date->format('M d, Y') }}
                                </p>
                            @else
                                <p class="detail-value">No exam scheduled</p>
                            @endif
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-icon">⏰</span>
                        <div>
                            <span class="detail-label">Exam Time</span>
                            @if($nextExam)
                                <p class="detail-value">
                                    {{ \Illuminate\Support\Carbon::parse($nextExam->start_time)->format('H:i') }} - {{ \Illuminate\Support\Carbon::parse($nextExam->end_time)->format('H:i') }}
                                    @if($nextExam->room)
                                        • {{ $nextExam->room }}
                                    @endif
                                </p>
                            @else
                                <p class="detail-value">TBA</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($enrollment->final_grade)
                    <div class="result-section">
                        <div class="result-header">
                            <span>Grade</span>
                            <span class="grade-badge">{{ $enrollment->final_grade }}/20</span>
                        </div>
                        <div class="result-bar">
                            <div class="result-fill" style="width: {{ ($enrollment->final_grade / 20) * 100 }}%;"></div>
                        </div>
                    </div>
                @else
                    <div class="prep-section">
                        <div class="prep-header">
                            <span class="prep-label">Exam Status</span>
                            <span class="prep-percent">{{ $isUpcoming ? 'Upcoming' : 'TBA' }}</span>
                        </div>
                        <div class="prep-tips">
                            <a href="{{ route('dashboard.student.schedule') }}" class="action-link">View schedule →</a>
                            <a href="{{ route('dashboard.student.subjects') }}" class="action-link">View subject →</a>
                        </div>
                    </div>
                @endif

                <div class="card-footer-exam">
                    <a href="{{ route('dashboard.student.subjects') }}" class="btn btn-secondary btn-small">Details</a>
                    @if($enrollment->final_grade)
                        <span class="btn btn-secondary btn-small" style="background: rgba(16, 185, 129, 0.1); color: #10b981; border: none; cursor: default;">Graded</span>
                    @else
                        <span class="btn btn-secondary btn-small" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: none; cursor: default;">Pending</span>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: var(--spacing-lg); color: var(--text-dark-secondary); text-align: center; grid-column: 1 / -1;">
                No enrolled courses
            </div>
        @endforelse
    </div>

    <div class="dashboard-card stats-section">
        <div class="card-header">
            <h3>Summary</h3>
            <span class="chip">All Courses</span>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <p class="stat-label">Total Courses</p>
                <p class="stat-value">{{ $enrolledCourses->count() }}</p>
                <span class="stat-meta">Enrolled courses</span>
            </div>
            <div class="stat-box">
                <p class="stat-label">Graded</p>
                <p class="stat-value">{{ $enrolledCourses->filter(fn($e) => $e->final_grade !== null)->count() }}</p>
                <span class="stat-meta">With grades</span>
            </div>
            <div class="stat-box">
                <p class="stat-label">Pending</p>
                <p class="stat-value">{{ $enrolledCourses->filter(fn($e) => $e->final_grade === null)->count() }}</p>
                <span class="stat-meta">Awaiting grades</span>
            </div>
            <div class="stat-box">
                <p class="stat-label">Average</p>
                <p class="stat-value">{{ $enrolledCourses->filter(fn($e) => $e->final_grade !== null)->count() > 0 ? round($enrolledCourses->filter(fn($e) => $e->final_grade !== null)->avg('final_grade'), 1) : 'N/A' }}</p>
                <span class="stat-meta">Out of 20</span>
            </div>
        </div>
    </div>
</div>
