<?php

namespace App\Livewire;

use App\Mail\NewReviewMail;
use App\Models\Property;
use App\Models\Review;
use App\Models\ReviewReaction;
use App\Notifications\NewReviewNotification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    public ?string $successMessage = null;

    public string $replyBody = ''; // রিপ্লাই ফর্মের জন্য

    public ?int $editingReviewId = null;
    public string $editingReviewBody = '';

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
            ->with([
                'user',
                'replies' => function ($query) {
                    $query->where('status', 'approved');
                }
            ]);

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
        // ধাপ ১: ব্যবহারকারী লগইন করা আছে কিনা তা পরীক্ষা করা
        if (!Auth::check()) {
            // JS কে জানানোর জন্য একটি ইভেন্ট পাঠান যাতে লগইন পপআপ দেখানো যায়
            $this->dispatch('show-login-alert');
            return;
        }

        // ধাপ ২: ব্যবহারকারী এই প্রপার্টির মালিক কিনা তা পরীক্ষা করা (ঐচ্ছিক কিন্তু প্রস্তাবিত)
        if ($this->property->user_id === Auth::id()) {
            session()->flash('error', 'দুঃখিত, আপনি নিজের প্রপার্টিতে রিভিউ দিতে পারবেন না।');
            return;
        }

        // ধাপ ৩: ব্যবহারকারী ইতোমধ্যে রিভিউ দিয়েছে কিনা তা পরীক্ষা করা
        $existingReview = Review::where('user_id', Auth::id())
            ->where('property_id', $this->property->id)
            ->whereNull('parent_id')
            ->exists();

        if ($existingReview) {
            session()->flash('error', 'আপনি ইতোমধ্যে এই প্রপার্টির জন্য একটি রিভিউ জমা দিয়েছেন।');
            return;
        }

        // ধাপ ৪: ডেটা ভ্যালিডেট করা
        $validatedData = $this->validate();

        // ধাপ ৫: রিভিউ তৈরি করা
        $review = Review::create([
            'property_id' => $this->property->id,
            'user_id'     => Auth::id(),
            'rating'      => $validatedData['rating'],
            'title'       => $validatedData['title'],
            'body'        => $validatedData['body'],
            'status'      => 'pending', // মডারেশনের জন্য ডিফল্ট 'pending'
        ]);

        // ধাপ ৬: প্রপার্টি মালিককে নোটিফাই করা (ইমেইল এবং ডাটাবেস)
        try {
            $owner = $this->property->user;
            if ($owner) {
                $owner->notify(new NewReviewNotification($review));
                Mail::to($owner->email)->send(new NewReviewMail($review));
            }
        } catch (\Exception $e) {
            // যদি নোটিফিকেশন পাঠাতে কোনো সমস্যা হয়, তাহলে সেটি লগ করা হবে
            // কিন্তু ব্যবহারকারীর অভিজ্ঞতা बाधित হবে না।
            report($e);
        }

        // ধাপ ৭: ফর্ম রিসেট করা
        $this->reset(['rating', 'title', 'body']);

        // মডাল বন্ধ করা এবং টোস্ট দেখানোর জন্য একটি ইভেন্ট পাঠান
        // আমরা টোস্টের জন্য একটি মেসেজ এবং টাইপ (success) পাস করছি
        $this->successMessage = 'ধন্যবাদ! আপনার রিভিউটি সফলভাবে জমা হয়েছে এবং পর্যালোচনার অপেক্ষায় আছে।';
        $this->dispatch('close-review-modal');

        // ধাপ ৯: কম্পোনেন্টের ডেটা রিফ্রেশ করা
        $this->page = 1; // পেজিনেশন প্রথম পাতায় নিয়ে আসা
        $this->calculateRatingStats();
        $this->loadReviews();
    }

    /**
     * Handles the submission of a new reply.
     */
    public function submitReply(int $parentId): void
    {
        // ধাপ ১: ব্যবহারকারী লগইন করা আছে কিনা তা পরীক্ষা করা
        if (!Auth::check()) {
            $this->dispatch('show-login-alert');
            return;
        }

        // ধাপ ২: মূল রিভিউটি খুঁজে বের করা
        $parentReview = Review::find($parentId);
        if (!$parentReview) {
            session()->flash('reply_error_' . $parentId, 'দুঃখিত, কোনো একটি সমস্যা হয়েছে।');
            return;
        }

        // ধাপ ৩: ভ্যালিডেশন
        $this->validate(['replyBody' => 'required|string|min:5|max:1000']);

        // ধাপ ৪: রিপ্লাই তৈরি করা
        $parentReview->replies()->create([
            'property_id' => $this->property->id,
            'user_id' => Auth::id(),
            'body' => $this->replyBody,
            'rating' => 0, // রিপ্লাইয়ের কোনো রেটিং থাকে না
            // মালিক বা সুপার অ্যাডমিনের রিপ্লাই স্বয়ংক্রিয়ভাবে অনুমোদিত হবে
            'status' => 'approved',
        ]);

        // ধাপ ৫: ফর্ম রিসেট করা এবং রিভিউ তালিকা রিফ্রেশ করা
        $this->replyBody = '';
        $this->loadReviews();

        // আপনি চাইলে একটি সাকসেস মেসেজ দেখাতে পারেন
        $this->dispatch('reply-submitted-successfully');
    }

    /**
     * কোনো রিভিউ এডিট করার জন্য এই মেথডটি কল হবে।
     */
    public function edit(int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);

        // পলিসি বা গেট ব্যবহার করে অথোরাইজেশন চেক করা সেরা অনুশীলন
        if (Auth::user()->cannot('update', $review)) {
            return;
        }

        $this->editingReviewId = $review->id;
        $this->editingReviewBody = $review->body;
    }

    /**
     * এডিটিং বাতিল করার জন্য।
     */
    public function cancelEdit(): void
    {
        $this->reset('editingReviewId', 'editingReviewBody');
    }

    /**
     * এডিট করা রিভিউ/রিপ্লাই আপডেট করার জন্য।
     */
    public function update(): void
    {
        if ($this->editingReviewId === null) return;

        $review = Review::findOrFail($this->editingReviewId);

        if (Auth::user()->cannot('update', $review)) {
            return;
        }

        $this->validate(['editingReviewBody' => 'required|string|min:10']);

        $review->update(['body' => $this->editingReviewBody]);

        $this->cancelEdit(); // এডিটিং স্টেট রিসেট করুন
        $this->loadReviews(); // তালিকা রিফ্রেশ করুন
    }

    /**
     * কোনো রিভিউ বা রিপ্লাই ডিলিট করার জন্য।
     */
    public function delete(int $reviewId): void
    {
        $review = Review::findOrFail($reviewId);

        if (Auth::user()->cannot('delete', $review)) {
            return;
        }

        $review->delete();

        $this->loadReviews(); // তালিকা রিফ্রেশ করুন
        $this->calculateRatingStats(); // পরিসংখ্যান রিফ্রেশ করুন
    }

    public function render(): View|Factory
    {
        return view('livewire.property-reviews');
    }
}
