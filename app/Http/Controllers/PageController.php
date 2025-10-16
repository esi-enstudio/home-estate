<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Settings\MaintenanceSettings;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about'); // আমরা এই ভিউ ফাইলটি একটু পরেই তৈরি করব
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function show(Page $page) // Route Model Binding
    {
        // শুধুমাত্র প্রকাশিত পেজই দেখা যাবে
        if (!$page->is_published) {
            abort(404);
        }
        return view('pages.show', compact('page'));
    }

    public function faq()
    {
        // ডাটাবেজ থেকে সকল সক্রিয় FAQ লোড করা হচ্ছে এবং sort_order অনুযায়ী সাজানো হচ্ছে
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.faq', compact('faqs'));
    }

    public function testimonials()
    {
        // এই ভিউ ফাইলটি Livewire কম্পোনেন্টটি লোড করবে
        return view('pages.testimonials');
    }

    public function comingSoon(MaintenanceSettings $settings)
    {
        // Dependency Injection ব্যবহার করে সেটিংস লোড করা হচ্ছে
        return view('pages.coming-soon', ['settings' => $settings]);
    }

    public function ourInspiration()
    {
        return view('pages.our-inspiration'); // এই ভিউটি Livewire কম্পোনেন্ট লোড করবে
    }

    public function mapView()
    {
        // আমরা এই ভিউ ফাইলটি একটু পরেই তৈরি করব
        return view('pages.map-view');
    }
}
