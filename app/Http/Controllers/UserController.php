<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return view('dashboards.admin.users.index');
    }

    public function create()
    {
        return view('dashboards.admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user = User::create($validated);

        $this->logActivity($user, 'created', 'User account created');

        return redirect()
            ->route('dashboard.admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['activities' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('dashboards.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('dashboards.admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $oldValues = $user->only(['name', 'email', 'role', 'phone', 'address', 'date_of_birth', 'nif']);

        //this make it so the password is updated only if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $user->update($validated);

        $changes = [];
        foreach ($oldValues as $key => $oldValue) {
            if (isset($validated[$key]) && $validated[$key] != $oldValue) {
                $changes[$key] = ['old' => $oldValue, 'new' => $validated[$key]];
            }
        }

        $this->logActivity($user, 'updated', 'User profile updated', $changes);

        return redirect()
            ->route('dashboard.admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        //do not allow self deletion
        if ($user->id === Auth::id()) {
            return redirect()
                ->route('dashboard.admin.users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        // Delete profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Log activity before deletion
        $this->logActivity($user, 'deleted', 'User account deleted');

        $user->delete();

        return redirect()
            ->route('dashboard.admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,change_role',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'role' => 'required_if:action,change_role|in:student,teacher,admin',
        ]);

        $userIds = $request->user_ids;

        // Prevent self-deletion/modification
        if (in_array(Auth::id(), $userIds)) {
            return back()->with('error', 'You cannot perform bulk actions on your own account!');
        }

        switch ($request->action) {
            case 'delete':
                $users = User::whereIn('id', $userIds)->get();
                foreach ($users as $user) {
                    if ($user->profile_picture) {
                        Storage::disk('public')->delete($user->profile_picture);
                    }
                    $this->logActivity($user, 'deleted', 'User deleted via bulk action');
                }
                User::whereIn('id', $userIds)->delete();
                return back()->with('success', count($userIds) . ' users deleted successfully!');

            case 'change_role':
                User::whereIn('id', $userIds)->update(['role' => $request->role]);

                $users = User::whereIn('id', $userIds)->get();
                foreach ($users as $user) {
                    $this->logActivity(
                        $user,
                        'updated',
                        'Role changed to ' . $request->role . ' via bulk action',
                        ['role' => ['old' => $user->getOriginal('role'), 'new' => $request->role]]
                    );
                }

                return back()->with('success', count($userIds) . ' users updated successfully!');
        }

        return back();
    }

    /**
     * Log user activity
     */
    private function logActivity(User $user, string $action, string $description, array $changes = [])
    {
        UserActivity::create([
            'user_id' => $user->id,
            'performed_by' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
