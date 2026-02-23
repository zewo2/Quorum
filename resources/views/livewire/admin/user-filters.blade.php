<div>
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters and Actions -->
    <div class="users-header">
        <div class="users-filters">
            <div class="filter-group">
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Search users..."
                    class="filter-input"
                >
            </div>

            <div class="filter-group">
                <select wire:model.live="role" class="filter-select">
                    <option value="">All Roles</option>
                    <option value="student">Students</option>
                    <option value="teacher">Teachers</option>
                    <option value="admin">Admins</option>
                </select>
            </div>

            <button type="button" class="btn btn-secondary" wire:click="resetFilters">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                Clear
            </button>
        </div>

        <a href="{{ route('dashboard.admin.users.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add User
        </a>
    </div>

    <!-- Bulk Actions Bar -->
    <div class="bulk-actions-bar" id="bulkActionsBar" style="display: none;">
        <div class="bulk-actions-content">
            <span class="bulk-selected-count">0 users selected</span>
            <form method="POST" action="{{ route('dashboard.admin.users.bulk-action') }}" id="bulkActionForm" class="bulk-actions-form">
                @csrf
                <input type="hidden" name="action" id="bulkAction" value="">
                <input type="hidden" name="role" id="bulkRole" value="">
                <div id="selectedUsersContainer"></div>

                <select class="bulk-select" id="bulkActionSelect">
                    <option value="">Select Action</option>
                    <option value="change_role">Change Role</option>
                    <option value="delete">Delete Selected</option>
                </select>

                <select class="bulk-select" id="roleSelect" style="display: none;">
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>

                <button type="button" class="btn btn-primary btn-sm" onclick="executeBulkAction()">Apply</button>
                <button type="button" class="btn btn-ghost btn-sm" onclick="clearSelection()">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="users-table-container">
        <table class="users-table">
            <thead>
                <tr>
                    <th style="width: 40px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                    </th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" onchange="updateBulkActions()">
                        </td>
                        <td>
                            <div class="user-cell">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="user-avatar-img">
                                @else
                                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                @endif
                                <span class="user-name">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('dashboard.admin.users.show', $user) }}" class="action-btn" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <a href="{{ route('dashboard.admin.users.edit', $user) }}" class="action-btn" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('dashboard.admin.users.destroy', $user) }}" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-danger" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <p>No users found</p>
                                <a href="{{ route('dashboard.admin.users.create') }}" class="btn btn-primary">Add First User</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="pagination-container">
            {{ $users->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.users-management {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.alert {
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.users-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: var(--spacing-md);
    flex-wrap: wrap;
    margin-bottom: 0.5cm;
}

.users-filters {
    display: flex;
    gap: var(--spacing-md);
    flex: 1;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-family: inherit;
    font-size: 0.9375rem;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: var(--primary);
}

.filter-select option {
    background: var(--bg-dark);
    color: var(--text-dark);
    padding: 8px;
}

.filter-select option:checked {
    background: var(--primary);
    color: white;
}

.users-table-container {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-lg);
    overflow: hidden;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table thead {
    background: rgba(255, 255, 255, 0.03);
}

.users-table th {
    padding: var(--spacing-md) var(--spacing-lg);
    text-align: left;
    font-weight: 600;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-dark);
}

.users-table td {
    padding: var(--spacing-md) var(--spacing-lg);
    color: var(--text-dark-secondary);
    border-bottom: 1px solid var(--border-dark);
}

.users-table tbody tr:last-child td {
    border-bottom: none;
}

.users-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.02);
}

.user-cell {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
    font-size: 0.875rem;
}

.user-avatar-img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.user-name {
    color: var(--text-dark);
    font-weight: 500;
}

.role-badge {
    padding: var(--spacing-xs) var(--spacing-md);
    border-radius: var(--radius-md);
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.role-student {
    background: rgba(14, 165, 233, 0.1);
    color: #0ea5e9;
}

.role-teacher {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.role-admin {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.action-buttons {
    display: flex;
    gap: var(--spacing-sm);
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.05);
    border: none;
    border-radius: var(--radius-md);
    color: var(--text-dark-secondary);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.action-btn:hover {
    background: var(--primary);
    color: white;
}

.action-btn-danger {
    color: var(--danger);
}

.action-btn-danger:hover {
    background: var(--danger);
    color: white;
}

.inline-form {
    display: inline;
}

.bulk-actions-bar {
    background: var(--bg-dark-secondary);
    border: 1px solid var(--primary);
    border-radius: var(--radius-md);
    padding: var(--spacing-md) var(--spacing-lg);
    margin-bottom: var(--spacing-md);
}

.bulk-actions-content {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

.bulk-selected-count {
    font-weight: 600;
    color: var(--primary);
}

.bulk-actions-form {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    flex: 1;
}

.bulk-select {
    padding: var(--spacing-xs) var(--spacing-sm);
    background: var(--bg-dark);
    border: 1px solid var(--border-dark);
    border-radius: var(--radius-md);
    color: var(--text-dark);
    font-size: 0.875rem;
}

.empty-state {
    text-align: center;
    padding: var(--spacing-2xl) !important;
}

.empty-state-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--spacing-md);
    color: var(--text-dark-secondary);
}

.empty-state-content svg {
    opacity: 0.5;
}

.pagination-container {
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .users-header {
        flex-direction: column;
    }

    .users-table-container {
        overflow-x: auto;
    }

    .users-table {
        min-width: 600px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkBar = document.getElementById('bulkActionsBar');
    const count = checkboxes.length;

    if (count > 0) {
        bulkBar.style.display = 'block';
        document.querySelector('.bulk-selected-count').textContent = `${count} user${count > 1 ? 's' : ''} selected`;
    } else {
        bulkBar.style.display = 'none';
        document.getElementById('selectAll').checked = false;
    }
}

document.getElementById('bulkActionSelect').addEventListener('change', function() {
    const roleSelect = document.getElementById('roleSelect');
    if (this.value === 'change_role') {
        roleSelect.style.display = 'block';
    } else {
        roleSelect.style.display = 'none';
    }
});

function executeBulkAction() {
    const action = document.getElementById('bulkActionSelect').value;
    if (!action) {
        alert('Please select an action');
        return;
    }

    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Please select at least one user');
        return;
    }

    if (action === 'change_role') {
        const role = document.getElementById('roleSelect').value;
        if (!role) {
            alert('Please select a role');
            return;
        }

        if (!confirm(`Are you sure you want to change the role of ${checkboxes.length} user(s) to ${role}?`)) {
            return;
        }

        document.getElementById('bulkRole').value = role;
    } else if (action === 'delete') {
        if (!confirm(`Are you sure you want to delete ${checkboxes.length} user(s)? This action cannot be undone.`)) {
            return;
        }
    }

    document.getElementById('bulkAction').value = action;

    // Clear existing hidden inputs
    const container = document.getElementById('selectedUsersContainer');
    container.innerHTML = '';

    // Add selected user IDs
    checkboxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'user_ids[]';
        input.value = checkbox.value;
        container.appendChild(input);
    });

    document.getElementById('bulkActionForm').submit();
}

function clearSelection() {
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}
</script>
@endpush
