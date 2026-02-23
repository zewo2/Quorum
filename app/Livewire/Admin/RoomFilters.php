<?php

namespace App\Livewire\Admin;

use App\Models\Room;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class RoomFilters extends Component
{
    use WithPagination;

    #[Url]
    public string $q = '';

    #[Url]
    public string $building = '';

    #[Url]
    public string $capacity_min = '';

    #[Url]
    public string $capacity_max = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('q', 'building', 'capacity_min', 'capacity_max');
        $this->resetPage();
    }

    public function render()
    {
        $query = Room::query();

        if (filled($this->q)) {
            $query->where('code', 'like', '%' . $this->q . '%');
        }

        if (filled($this->building)) {
            $query->where('building', $this->building);
        }

        if (filled($this->capacity_min)) {
            $query->where('capacity', '>=', (int) $this->capacity_min);
        }

        if (filled($this->capacity_max)) {
            $query->where('capacity', '<=', (int) $this->capacity_max);
        }

        $rooms = $query->orderBy('code')
            ->paginate(20);

        $buildings = Room::whereNotNull('building')
            ->where('building', '!=', '')
            ->distinct()
            ->orderBy('building')
            ->pluck('building');

        return view('livewire.admin.room-filters', [
            'rooms' => $rooms,
            'buildings' => $buildings,
        ]);
    }
}
