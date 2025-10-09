<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class TestimonialsPage extends Component
{
    public Collection $testimonials; // এটি এখন একটি Collection হবে
    public int $page = 1;
    public int $perPage = 12; // প্রতিবার কতগুলো আইটেম লোড হবে
    public bool $hasMorePages;

    /**
     * কম্পোনেন্ট প্রথমবার লোড হওয়ার সময়।
     */
    public function mount(): void
    {
        $this->testimonials = new Collection(); // প্রথমে একটি খালি কালেকশন তৈরি করা হলো
        $this->loadTestimonials();
    }

    /**
     * টেস্টমোনিয়াল লোড করার মূল ফাংশন।
     */
    public function loadTestimonials(): void
    {
        $query = Review::with('user')
            ->where('status', 'approved')
            ->where('is_testimonial', true)
            ->latest();

        // 'simplePaginate' ব্যবহার করা হচ্ছে যা হালকা এবং 'hasMorePages' মেথড দেয়
        $paginator = $query->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        // নতুন লোড হওয়া আইটেমগুলোকে আগেরগুলোর সাথে যুক্ত করা হচ্ছে
        $this->testimonials->push(...$paginator->items());

        // "Load More" বাটন দেখানো হবে কিনা তা নির্ধারণ করুন
        $this->hasMorePages = $paginator->hasMorePages();
    }

    /**
     * "Load More" বাটনে ক্লিক করলে এই মেথডটি কাজ করবে।
     */
    public function loadMore(): void
    {
        if ($this->hasMorePages) {
            $this->page++;
            $this->loadTestimonials();
        }
    }

    public function render()
    {
        return view('livewire.testimonials-page');
    }
}
