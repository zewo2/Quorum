@extends('layouts.be_master')

@section('title', 'Course Management - Quorum')
@section('page-title', 'Course Management')

@section('content')
<div class="courses-page">
    <div class="dashboard-grid stats-grid">
        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Total Courses</p>
                <p class="stat-value">87</p>
                <span class="stat-meta">12 new this semester</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M16 12c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Active Enrollments</p>
                <p class="stat-value">5,420</p>
                <span class="stat-meta">+8% vs last term</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="6" width="20" height="12" rx="2" ry="2"></rect>
                    <path d="M6 10h.01"></path>
                    <path d="M10 10h.01"></path>
                    <path d="M14 10h.01"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Departments</p>
                <p class="stat-value">9</p>
                <span class="stat-meta">Science, Tech, Business...</span>
            </div>
        </div>

        <div class="dashboard-card stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #f87171);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <path d="M9 22V12h6v10"></path>
                </svg>
            </div>
            <div>
                <p class="stat-label">Rooms Linked</p>
                <p class="stat-value">156</p>
                <span class="stat-meta">38 labs • 118 classrooms</span>
            </div>
        </div>
    </div>

    <div class="dashboard-card filters-card">
        <div class="filters-left">
            <label class="field">
                <span>Search courses</span>
                <input type="text" placeholder="e.g. Web Development" value="Web" aria-label="Search courses">
            </label>
            <label class="field">
                <span>Department</span>
                <select>
                    <option>All</option>
                    <option>Computer Science</option>
                    <option>Business</option>
                    <option>Engineering</option>
                </select>
            </label>
            <label class="field">
                <span>Status</span>
                <select>
                    <option>Active</option>
                    <option>Draft</option>
                    <option>Archived</option>
                </select>
            </label>
        </div>
        <div class="filters-actions">
            <button class="btn btn-secondary">Reset</button>
            <button class="btn btn-primary">Add Course</button>
        </div>
    </div>

    <div class="dashboard-card table-card">
        <div class="card-header">
            <h3>Course Catalog</h3>
            <span class="chip">Showing 8 of 87</span>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Code</th>
                        <th>Department</th>
                        <th>Instructor</th>
                        <th>Students</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="item-main">
                                <div class="item-dot" style="background: #22c55e;"></div>
                                <div>
                                    <p class="item-title">Web Development II</p>
                                    <span class="item-sub">Front-end frameworks</span>
                                </div>
                            </div>
                        </td>
                        <td>WD202</td>
                        <td>Computer Science</td>
                        <td>Laura Mendes</td>
                        <td>132</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td class="row-actions">
                            <button class="icon-btn" title="View">👁</button>
                            <button class="icon-btn" title="Edit">✏️</button>
                            <button class="icon-btn danger" title="Archive">🗑</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="item-main">
                                <div class="item-dot" style="background: #0ea5e9;"></div>
                                <div>
                                    <p class="item-title">Data Structures</p>
                                    <span class="item-sub">Algorithms & complexity</span>
                                </div>
                            </div>
                        </td>
                        <td>CS210</td>
                        <td>Computer Science</td>
                        <td>Rui Costa</td>
                        <td>118</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td class="row-actions">
                            <button class="icon-btn" title="View">👁</button>
                            <button class="icon-btn" title="Edit">✏️</button>
                            <button class="icon-btn danger" title="Archive">🗑</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="item-main">
                                <div class="item-dot" style="background: #f97316;"></div>
                                <div>
                                    <p class="item-title">Corporate Finance</p>
                                    <span class="item-sub">Capital budgeting</span>
                                </div>
                            </div>
                        </td>
                        <td>FIN310</td>
                        <td>Business</td>
                        <td>Ines Silva</td>
                        <td>96</td>
                        <td><span class="badge badge-warning">Draft</span></td>
                        <td class="row-actions">
                            <button class="icon-btn" title="View">👁</button>
                            <button class="icon-btn" title="Edit">✏️</button>
                            <button class="icon-btn danger" title="Archive">🗑</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="item-main">
                                <div class="item-dot" style="background: #a855f7;"></div>
                                <div>
                                    <p class="item-title">Human-Computer Interaction</p>
                                    <span class="item-sub">UX research</span>
                                </div>
                            </div>
                        </td>
                        <td>UX330</td>
                        <td>Design</td>
                        <td>Andre Sousa</td>
                        <td>74</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td class="row-actions">
                            <button class="icon-btn" title="View">👁</button>
                            <button class="icon-btn" title="Edit">✏️</button>
                            <button class="icon-btn danger" title="Archive">🗑</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="item-main">
                                <div class="item-dot" style="background: #f43f5e;"></div>
                                <div>
                                    <p class="item-title">Data Ethics</p>
                                    <span class="item-sub">Governance & privacy</span>
                                </div>
                            </div>
                        </td>
                        <td>DS260</td>
                        <td>Computer Science</td>
                        <td>Helena Duarte</td>
                        <td>63</td>
                        <td><span class="badge badge-archived">Archived</span></td>
                        <td class="row-actions">
                            <button class="icon-btn" title="View">👁</button>
                            <button class="icon-btn" title="Edit">✏️</button>
                            <button class="icon-btn danger" title="Archive">🗑</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-grid info-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Upcoming Milestones</h3>
                <span class="chip">January 2026</span>
            </div>
            <div class="timeline">
                <div class="timeline-row">
                    <div class="timeline-dot" style="border-color: #22c55e;"></div>
                    <div>
                        <p class="timeline-title">Publish Spring catalog</p>
                        <span class="timeline-meta">Jan 12 • All departments</span>
                    </div>
                </div>
                <div class="timeline-row">
                    <div class="timeline-dot" style="border-color: #0ea5e9;"></div>
                    <div>
                        <p class="timeline-title">Room allocation freeze</p>
                        <span class="timeline-meta">Jan 18 • Facilities</span>
                    </div>
                </div>
                <div class="timeline-row">
                    <div class="timeline-dot" style="border-color: #f59e0b;"></div>
                    <div>
                        <p class="timeline-title">Enrollment opens</p>
                        <span class="timeline-meta">Jan 22 • Portal announcement</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Top Instructors</h3>
                <span class="chip">by satisfaction</span>
            </div>
            <div class="instructors-list">
                <div class="instructor-item">
                    <div class="avatar">LM</div>
                    <div>
                        <p class="item-title">Laura Mendes</p>
                        <span class="item-sub">Web Development • 4.8/5</span>
                    </div>
                    <span class="badge badge-success">132 students</span>
                </div>
                <div class="instructor-item">
                    <div class="avatar">RC</div>
                    <div>
                        <p class="item-title">Rui Costa</p>
                        <span class="item-sub">Algorithms • 4.7/5</span>
                    </div>
                    <span class="badge badge-success">118 students</span>
                </div>
                <div class="instructor-item">
                    <div class="avatar">IS</div>
                    <div>
                        <p class="item-title">Ines Silva</p>
                        <span class="item-sub">Finance • 4.6/5</span>
                    </div>
                    <span class="badge badge-warning">Draft</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.courses-page {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}

.stat-card {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
    border: 1px solid var(--border-dark);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-label {
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text-dark);
}

.stat-meta {
    color: var(--text-dark-secondary);
    font-size: 0.85rem;
}

.filters-card {
    display: flex;
    gap: var(--spacing-lg);
    align-items: flex-end;
    justify-content: space-between;
}

.filters-left {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-md);
    flex: 1;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 6px;
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}

.field input,
.field select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
}

.filters-actions {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.table-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.chip {
    padding: 6px 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-dark);
    border-radius: 999px;
    color: var(--text-dark-secondary);
    font-size: 0.85rem;
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: var(--spacing-md);
    text-align: left;
    border-bottom: 1px solid var(--border-dark);
}

.data-table th {
    color: var(--text-dark);
    font-weight: 600;
    background: rgba(255, 255, 255, 0.03);
}

.item-main {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.item-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.item-title {
    color: var(--text-dark);
    font-weight: 600;
}

.item-sub {
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 700;
}

.badge-success { background: rgba(16, 185, 129, 0.12); color: #10b981; }
.badge-warning { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
.badge-archived { background: rgba(239, 68, 68, 0.12); color: #ef4444; }

.row-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.icon-btn {
    width: 34px;
    height: 34px;
    border: 1px solid var(--border-dark);
    background: rgba(255, 255, 255, 0.04);
    border-radius: var(--radius-md);
    cursor: pointer;
}

.icon-btn:hover { background: var(--primary); color: white; }
.icon-btn.danger:hover { background: var(--danger); }

.info-grid {
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.timeline-row {
    display: flex;
    gap: var(--spacing-md);
    align-items: center;
}

.timeline-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid var(--border-dark);
}

.timeline-title {
    color: var(--text-dark);
    font-weight: 600;
}

.timeline-meta {
    color: var(--text-dark-secondary);
    font-size: 0.9rem;
}

.instructors-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.instructor-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius-md);
}

.avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
}

@media (max-width: 900px) {
    .filters-card {
        flex-direction: column;
        align-items: stretch;
    }

    .filters-actions {
        justify-content: flex-end;
    }
}
</style>
@endpush
@endsection
