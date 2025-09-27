<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\Review;
use App\Models\ReviewReaction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PropertyReviews extends Component
{
    public Property $property;

    // Review list and pagination
    public $reviews;
    public $perPage = 3;
    public $page = 1;
    public $hasMorePages;

    // Rating statistics
    public float $averageRating = 0.0;
    public int $totalReviewsCount = 0;
    public array $ratingStats = [];

    // Form fields for new review
    public int $rating = 0;
    public string $title = '';
    public string $body = '';

    public bool $showSuccessMessage = false;

    // Validation rules
    protected function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:20',
        ];
    }

    // Live validation
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function mount(Property $property): void
    {
        $this->property = $property;
        $this->calculateRatingStats();
        $this->loadReviews();
    }

    public function calculateRatingStats(): void
    {
        // শুধুমাত্র 'approved' রিভিউগুলোকেই গণনার মধ্যে আনা হচ্ছে
        $approvedReviewsQuery = $this->property->reviews()->where('status', 'approved');

        $this->averageRating = $this->property->reviews()->avg('rating') ?? 0.0;
        $this->totalReviewsCount = $this->property->reviews()->count();

        // প্রতি স্টার রেটিং এর গণনাও শুধুমাত্র 'approved' রিভিউ থেকে হবে
        for ($i = 5; $i >= 1; $i--) {
            $count = $this->property->reviews()->where('status', 'approved')->where('rating', $i)->count();
            $percentage = $this->totalReviewsCount > 0 ? ($count / $this->totalReviewsCount) * 100 : 0;
            $this->ratingStats[$i] = ['count' => $count, 'percentage' => $percentage];
        }
    }

    public function loadReviews(): void
    {
        $query = $this->property->reviews()
            ->where('status', 'approved')
            ->whereNull('parent_id')
            ->with('user');

        // যদি ইউজার লগইন করা থাকে, তাহলে তার প্রতিক্রিয়াও লোড করা হবে
        if (auth()->check()) {
            $query->with('authUserReaction');
        }

        $paginator = $query->latest()->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        if ($this->page > 1) {
            $this->reviews = $this->reviews->merge($paginator->getCollection());
        } else {
            $this->reviews = $paginator->getCollection();
        }

        $this->hasMorePages = $paginator->hasMorePages();
    }

    public function toggleReaction(int $reviewId, string $type): void
    {
        if (!auth()->check()) {
            // আপনি চাইলে লগইন করার জন্য একটি পপআপ দেখাতে পারেন
            return;
        }

        $review = Review::find($reviewId);
        if (!$review) return;

        $reaction = ReviewReaction::where('user_id', auth()->id())
            ->where('review_id', $reviewId)
            ->first();

        if ($reaction) {
            if ($reaction->type === $type) {
                // একই বাটনে আবার ক্লিক করলে প্রতিক্রিয়া ডিলিট হয়ে যাবে (আন-লাইক)
                $reaction->delete();
            } else {
                // অন্য বাটনে ক্লিক করলে প্রতিক্রিয়া পরিবর্তন হয়ে যাবে (লাইক থেকে ডিসলাইক)
                $reaction->type = $type;
                $reaction->save();
            }
        } else {
            // নতুন প্রতিক্রিয়া তৈরি করা হবে
            ReviewReaction::create([
                'user_id' => auth()->id(),
                'review_id' => $reviewId,
                'type' => $type,
            ]);
        }

        // এখন রিভিউ টেবিলের কাউন্টগুলো আপডেট করতে হবে (Renormalization for performance)
        $review->likes_count = ReviewReaction::where('review_id', $reviewId)->where('type', 'like')->count();
        $review->dislikes_count = ReviewReaction::where('review_id', $reviewId)->where('type', 'dislike')->count();
        $review->favorites_count = ReviewReaction::where('review_id', $reviewId)->where('type', 'favorite')->count();
        $review->save();

        // সবশেষে, রিভিউ তালিকাটি রিফ্রেশ করা হচ্ছে
        $this->loadReviews();
    }

    public function loadMore(): void
    {
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadReviews();
        }
    }

    public function submitReview(): void
    {
        if (!Auth::check()) {
            $this->dispatch('show-login-alert'); // এটি একটি ইভেন্ট যা আপনি JS দিয়ে শুনতে পারেন
            return;
        }

        // ========== START: নতুন চেক যোগ করা হলো ==========
        $existingReview = Review::where('user_id', Auth::id())
            ->where('property_id', $this->property->id)
            ->whereNull('parent_id') // শুধুমাত্র টপ-লেভেল রিভিউ চেক করা হচ্ছে
            ->exists();

        if ($existingReview) {
            // একটি ফ্ল্যাশ মেসেজ সেট করা হচ্ছে যা ভিউতে দেখানো যাবে
            session()->flash('error', 'You have already submitted a review for this property.');
            // ভিউতে থাকা মডালটি বন্ধ করার জন্য একটি ইভেন্ট পাঠানো যেতে পারে (ঐচ্ছিক)
            // $this->dispatch('close-review-modal');
            return;
        }
        // ========== END: নতুন চেক যোগ করা হলো ==========

        $this->validate();

        Review::create([
            'property_id' => $this->property->id,
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'title' => $this->title,
            'body' => $this->body,
            'status' => 'pending',
        ]);

        $this->reset(['rating', 'title', 'body']);
        $this->showSuccessMessage = true;

        $this->page = 1;
        $this->calculateRatingStats();
        $this->loadReviews();
    }

    public function render(): View|Factory
    {
        return view('livewire.property-reviews');
    }
}
