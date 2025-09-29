<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class CustomerTestimonials extends Component
{
    public $testimonials = [];
    public $totalReviews = 0;
    public $overallRating = 0.0;

    public function mount(): void
    {
        // ৬ ঘণ্টার জন্য এই পরিসংখ্যানগুলো ক্যাশ করা হবে
        $stats = Cache::remember('customer_testimonials_stats', now()->addHours(6), function () {
            /// এখন শুধুমাত্র সেই রিভিউগুলো আনা হচ্ছে যেগুলোকে আপনি Testimonial হিসেবে মার্ক করেছেন
            $testimonials = Review::with('user')
                ->where('status', 'approved')
                ->where('is_testimonial', true) // <-- মূল পরিবর্তন এখানে
                ->latest()
                ->take(5)
                ->get();

            // ওয়েবসাইটের মোট রিভিউ এবং গড় রেটিং গণনা করা হচ্ছে
            $allApprovedReviews = Review::where('status', 'approved');
            $totalCount = $allApprovedReviews->count();
            $avgRating = $allApprovedReviews->avg('rating') ?? 0.0;

            return [
                'testimonials' => $testimonials,
                'totalReviews' => $totalCount,
                'overallRating' => $avgRating,
            ];
        });

        $this->testimonials = $stats['testimonials'];
        $this->totalReviews = $stats['totalReviews'];
        $this->overallRating = $stats['overallRating'];
    }

    public function render()
    {
        return view('livewire.customer-testimonials');
    }
}
