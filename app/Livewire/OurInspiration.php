<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class OurInspiration extends Component
{
    public Collection $members;
    public int $page = 1;
    public int $perPage = 8; // প্রতিবার ৮ জন মেম্বার লোড হবে
    public bool $hasMorePages;

    public function mount(): void
    {
        $this->members = new Collection();
        $this->loadMembers();
    }

    public function loadMembers(): void
    {
        $query = User::where('show_on_our_inspiration_page', true)->orderBy('name');
        $paginator = $query->simplePaginate($this->perPage, ['*'], 'page', $this->page);
        $this->members->push(...$paginator->items());
        $this->hasMorePages = $paginator->hasMorePages();
    }

    public function loadMore(): void
    {
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadMembers();
        }
    }
    public function render()
    {
        return view('livewire.our-inspiration');
    }
}
