<?php
namespace App\Http\View\Composers;

use App\Settings\SiteSettings;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MetaComposer
{
    public function __construct(public SiteSettings $settings) {}

    public function compose(View $view): void
    {
        // View থেকে আসা নির্দিষ্ট ডেটা (যদি থাকে)
        $pageData = $view->getData();

        // SEO ডেটার জন্য ডিফল্ট মানগুলো সাইট সেটিংস থেকে নেওয়া হচ্ছে
        $title = $pageData['title'] ?? $this->settings->meta_title ?? config('app.name');
        $description = $pageData['description'] ?? $this->settings->meta_description ?? '';
        $keywords = $pageData['keywords'] ?? $this->settings->meta_keywords ?? [];
        $ogImage = $pageData['ogImage'] ?? ($this->settings->og_image ? Storage::url($this->settings->og_image) : asset('assets/img/default-og.jpg'));

        // keywords যদি অ্যারে হয়, তাহলে স্ট্রিং-এ রূপান্তর করা হচ্ছে
        if (is_array($keywords)) {
            $keywords = implode(', ', $keywords);
        }

        $view->with(compact('title', 'description', 'keywords', 'ogImage'));
    }
}
