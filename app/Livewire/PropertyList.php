<?php

namespace App\Livewire;

use App\Models\Property;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PropertyList extends Component
{
    public $properties;
    public $perPage = 6; // প্রতিবার কতগুলো প্রপার্টি লোড হবে
    public $page = 1;
    public $hasMorePages;

    // Filter properties
    public string $search = '';
    public string $purpose = '';
    public string $rent_type = '';
    public string $is_negotiable = '';
    public string $bedrooms = '';
    public string $bathrooms = '';
    public string $min_sqft = '';

    // প্রোপার্টি (Sorting, View Toggle, Counter)
    public int $totalPropertiesCount = 0;
    public string $sortBy = 'default'; // 'default', 'title_asc'
    public string $sortPrice = 'default'; // 'default', 'lth' (low to high), 'htl' (high to low)
    public string $viewMode = 'grid'; // 'grid' or 'list'

    /**
     * যখন sortBy বা sortPrice প্রোপার্টির মান পরিবর্তন হবে,
     * তখন এই মেথডটি স্বয়ংক্রিয়ভাবে কল হবে।
     */
    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['sortBy', 'sortPrice'])) {
            $this->page = 1; // সর্টিং পরিবর্তন হলে পেজিনেশন রিসেট হবে
            $this->loadProperties();
        }
    }

    /**
     * ভিউ মোড পরিবর্তন করার জন্য
     */
    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
    }

    /**
     * কম্পোনেন্ট যখন প্রথমবার লোড হয়
     */
    public function mount(): void
    {
        $this->loadProperties();
    }

    /**
     * "Apply Filter" বাটনে ক্লিক করলে এই মেথডটি কাজ করবে
     */
    public function applyFilter(): void
    {
        $this->page = 1; // ফিল্টার পরিবর্তন হলে পেজিনেশন প্রথম থেকে শুরু হবে
        $this->loadProperties();
    }

    /**
     * ফিল্টার রিসেট করার জন্য
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'purpose', 'rent_type', 'is_negotiable', 'bedrooms', 'bathrooms', 'min_sqft', 'sortBy', 'sortPrice']);
        $this->applyFilter();
    }

    /**
     * প্রপার্টি লোড করার মূল ফাংশন (এখন ফিল্টার সহ)
     */
    public function loadProperties(): void
    {
        $query = Property::with(['user', 'propertyType', 'media'])->active();

        // Conditional Filters using when() for cleaner code
        $query->when($this->search, function ($q) {
            $q->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orWhere('address_area', 'like', '%' . $this->search . '%');
        });

        $query->when($this->purpose, fn($q) => $q->where('purpose', $this->purpose));
        $query->when($this->rent_type, fn($q) => $q->where('rent_type', $this->rent_type));
        $query->when($this->is_negotiable, fn($q) => $q->where('is_negotiable', $this->is_negotiable));
        $query->when($this->bedrooms, fn($q) => $q->where('bedrooms', $this->bedrooms));
        $query->when($this->bathrooms, fn($q) => $q->where('bathrooms', $this->bathrooms));
        $query->when($this->min_sqft, fn($q) => $q->where('size_sqft', '>=', $this->min_sqft));

        // মোট ফলাফলের সংখ্যা গণনা করা (পেজিনেশনের আগে)
        $this->totalPropertiesCount = $query->count();

        // সর্টিং লজিক প্রয়োগ করা
        if ($this->sortBy === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($this->sortPrice === 'lth') {
            $query->orderBy('rent_price', 'asc');
        } elseif ($this->sortPrice === 'htl') {
            $query->orderBy('rent_price', 'desc');
        } else {
            $query->latest(); // Default sorting
        }

        $paginator = $query->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        if ($this->page > 1) {
            $this->properties = $this->properties->merge($paginator->getCollection());
        } else {
            $this->properties = $paginator->getCollection();
        }

        $this->hasMorePages = $paginator->hasMorePages();
    }

    /**
     * "Load More" বাটনে ক্লিক করলে এই ফাংশনটি কাজ করবে
     */
    public function loadMore(): void
    {
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadProperties();
        }
    }

    public function render(): View|Factory
    {
        return view('livewire.property-list');
    }
}
