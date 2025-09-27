<a href="#" wire:click.prevent="toggleFavorite">
    @if($isFavorited)
        <i class="material-icons-outlined rounded text-danger">favorite</i>
    @else
        <i class="material-icons-outlined rounded">favorite_border</i>
    @endif
</a>
