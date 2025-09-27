<?php

namespace App\Livewire;

use App\Models\Property;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteButton extends Component
{
    public Property $property;
    public bool $isFavorited;

    public function mount(Property $property): void
    {
        $this->property = $property;
        $this->updateFavoritedStatus();
    }

    public function updateFavoritedStatus(): void
    {
        if (Auth::check()) {
            $this->isFavorited = Auth::user()->favoriteProperties()->where('property_id', $this->property->id)->exists();
        } else {
            $this->isFavorited = false;
        }
    }

    public function toggleFavorite(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('filament.app.auth.login'));
            return;
        }

        Auth::user()->favoriteProperties()->toggle($this->property->id);
        $this->updateFavoritedStatus();
    }
    public function render(): Factory|View
    {
        return view('livewire.favorite-button');
    }
}
