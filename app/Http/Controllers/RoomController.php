<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        return view('admin.rooms.index');
    }

    public function create(): View
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:rooms,code',
            'building' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1|max:1000',
        ]);

        Room::create($validated);

        return redirect()->route('dashboard.admin.rooms.index')
            ->with('success', 'Room created successfully!');
    }

    public function edit(Room $room): View
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:rooms,code,' . $room->id,
            'building' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:1|max:1000',
        ]);

        $room->update($validated);

        return redirect()->route('dashboard.admin.rooms.index')
            ->with('success', 'Room updated successfully!');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->route('dashboard.admin.rooms.index')
            ->with('success', 'Room deleted successfully!');
    }
}
