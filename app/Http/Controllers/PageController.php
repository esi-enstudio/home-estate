<?php

namespace App\Http\Controllers;

use App\Models\Page;
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
}
