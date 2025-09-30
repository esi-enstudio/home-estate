<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BlogIndex extends Component
{
    // Load More এর জন্য প্রোপার্টি
    public $posts;
    public int $page = 1;
    public int $perPage = 3;
    public bool $hasMorePages;

    // ফিল্টার প্রোপার্টি
    public string $search = '';
    public ?int $selectedCategory = null;

    // URL-এ state রাখার জন্য (SEO এবং শেয়ারিং এর জন্য ভালো)
    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null, 'as' => 'category'],
    ];

    // --- Lifecycle Hooks and Actions ---

    /**
     * কম্পোনেন্ট প্রথমবার লোড হওয়ার সময়।
     */
    public function mount(): void
    {
        $this->loadPosts();
    }

    /**
     * যখনই search বা selectedCategory প্রোপার্টির মান পরিবর্তন হবে,
     * এই মেথডটি স্বয়ংক্রিয়ভাবে কল হবে।
     */
    public function updated($propertyName): void
    {
        // শুধুমাত্র এই দুটি প্রোপার্টি পরিবর্তন হলেই কোডটি চলবে
        if (in_array($propertyName, ['search', 'selectedCategory'])) {
            $this->page = 1;      // পেজিনেশন প্রথম পাতায় রিসেট করুন
            $this->loadPosts();   // নতুন ফিল্টারসহ পোস্টগুলো আবার লোড করুন
        }
    }

    /**
     * ক্যাটাগরি লিঙ্কে ক্লিক করলে এই মেথডটি কল হবে।
     * এর একমাত্র কাজ হলো state পরিবর্তন করা। বাকি কাজ updated() hook করে দেবে।
     */
    public function filterByCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
    }

    /**
     * পোস্ট লোড করার মূল ফাংশন।
     */
    public function loadPosts(): void
    {
        $query = Post::where('status', 'published')
            ->with('user', 'media', 'category');

        // সার্চ কোয়েরি
        $query->when($this->search, function ($q) {
            $q->where('title', 'like', '%' . $this->search . '%');
        });

        // === START: নতুন এবং গুরুত্বপূর্ণ পরিবর্তন ===
        // ক্যাটাগরি ফিল্টার কোয়েরি
        $query->when($this->selectedCategory, function ($q) {
            $q->where('category_id', $this->selectedCategory);
        });
        // === END: নতুন এবং গুরুত্বপূর্ণ পরিবর্তন ===

        $paginator = $query->latest('published_at')->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        if ($this->page > 1) {
            $this->posts = $this->posts->merge($paginator->getCollection());
        } else {
            $this->posts = $paginator->getCollection();
        }

        $this->hasMorePages = $paginator->hasMorePages();
    }

    /**
     * "Load More" বাটনে ক্লিক করলে এই মেথডটি কাজ করবে।
     */
    public function loadMore(): void
    {
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadPosts();
        }
    }

    public function render(): Factory|View
    {
        // সাইডবারের ডেটা এখানে লোড করা হচ্ছে
        $categories = Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])->get();
        $topArticles = Post::where('status', 'published')->latest('published_at')->take(4)->get();

        return view('livewire.blog-index', [
            'categories' => $categories,
            'topArticles' => $topArticles,
        ]);
    }
}
