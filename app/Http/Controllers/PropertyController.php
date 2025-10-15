<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     * এই পেজটি Livewire কম্পোনেন্টকে রেন্ডার করবে।
     */
    public function index(): Factory|\Illuminate\Contracts\View\View
    {
        // এখন আর ডেটা লোড করার কোনো প্রয়োজন নেই।
        // শুধুমাত্র মূল ভিউ ফাইলটি রিটার্ন করা হচ্ছে।
        return view('properties.index');
    }

    /**
     * একটি নির্দিষ্ট প্রপার্টির বিস্তারিত পেজ দেখায়।
     *
     * @param Property $property (Route Model Binding এর মাধ্যমে স্বয়ংক্রিয়ভাবে আসবে)
     * @return View
     */
    public function show(Property $property)
    {
        // ধাপ ১: নিরাপত্তা চেক - শুধুমাত্র 'active' প্রপার্টিই পাবলিকলি দেখা যাবে।
        // আপনি চাইলে Policy ব্যবহার করতে পারেন: $this->authorize('view', $property);
        if ($property->status !== 'active' && !(auth()->check() && auth()->user()->can('view_any_property'))) {
            abort(404);
        }

        // ধাপ ২: শুধুমাত্র প্রয়োজনীয় এবং অনুমোদিত ডেটা Eager Load করা
        $property->load([
            'user',
            'propertyType',
            'media',
            'amenities',
            // শুধুমাত্র অনুমোদিত রিভিউ এবং তাদের ইউজার লোড করা হচ্ছে
            'reviews' => function ($query) {
                $query->where('status', 'approved')->with('user');
            },
            'division',
            'district',
            'upazila'
        ]);

        // একই এলাকার এবং একই ধরণের অন্যান্য প্রপার্টিগুলো "Similar Properties" হিসেবে দেখানোর জন্য
        $similarProperties = Property::where('status', 'active')
            ->where('id', '!=', $property->id) // বর্তমান প্রপার্টি বাদে
            ->where('property_type_id', $property->property_type_id) // একই ধরণের
            ->where('district_id', $property->district_id) // একই জেলায়
            ->with('media', 'propertyType') // এদেরও কিছু বেসিক তথ্য লোড করা হলো
            ->inRandomOrder()
            ->take(4) // ৪টি সিমিলার প্রপার্টি দেখানো হবে
            ->get();

        // প্রপার্টি থেকে SEO ডেটা নেওয়া হচ্ছে, ফলব্যাক হিসেবে সাধারণ ডেটা ব্যবহার করা হচ্ছে
        $title = $property->meta_title ?: $property->title . ' | ' . config('app.name');
        $description = $property->meta_description ?: Str::limit(strip_tags($property->description), 155);
        $keywords = $property->meta_keywords ?: [];
        $ogImage = $property->getFirstMediaUrl('featured_image') ?: null;

        // ভিউ ফাইলে $property এবং $similarProperties ভ্যারিয়েবল দুটি পাঠানো হচ্ছে
        return view('properties.show', compact('property', 'similarProperties', 'title', 'description', 'keywords', 'ogImage'));
    }

    /**
     * Increment the view count for a property.
     * Uses session to prevent multiple counts from the same user in a single session.
     *
     * @param  Property  $property
     * @return JsonResponse
     */
    public function incrementViewCount(Property $property): JsonResponse
    {
        // একটি সেশন কী (session key) তৈরি করা হচ্ছে যা এই প্রপার্টির জন্য ইউনিক
        $sessionKey = 'viewed_property_' . $property->id;

        // যদি এই সেশনে এই প্রপার্টিটি আগে ভিউ করা না হয়ে থাকে
        if (!session()->has($sessionKey)) {
            // views_count এক বৃদ্ধি করা হচ্ছে
            $property->increment('views_count');

            // সেশনে একটি ফ্ল্যাগ সেট করা হচ্ছে যাতে একই সেশনে আর কাউন্ট না বাড়ে
            session()->put($sessionKey, true);
        }

        // ফ্রন্টএন্ডকে জানানোর জন্য একটি সফল বার্তা পাঠানো হচ্ছে
        return response()->json(['status' => 'success']);
    }

    /**
     * Generate and download the property listing form as a Word (.docx) file.
     * @throws Exception
     */
    public function downloadListingFormWord()
    {
        // ১. একটি নতুন PhpWord অবজেক্ট তৈরি করুন এবং বাংলা ফন্ট সেট করুন
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Kalpurush'); // নিশ্চিত করুন এই ফন্টটি storage/fonts/ এ আছে
        $phpWord->setDefaultFontSize(11);

        // ২. স্টাইল ডিফাইন করুন
        $phpWord->addParagraphStyle('pStyle', ['spaceAfter' => 100]);
        $phpWord->addTitleStyle('mainTitle', ['bold' => true, 'size' => 20, 'color' => '333333'], ['alignment' => 'center', 'spaceAfter' => 240]);
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 14, 'color' => '1F4E78', 'spaceBefore' => 300, 'spaceAfter' => 150]);
        $labelStyle = ['bold' => true];
        $tableStyle = ['borderSize' => 6, 'borderColor' => 'DDDDDD', 'cellMargin' => 80];
        $phpWord->addTableStyle('formTable', $tableStyle);
        $cellStyle = ['valign' => 'center'];
        $placeholderCellStyle = ['borderSize' => 0, 'borderColor' => 'FFFFFF'];

        // ৩. একটি নতুন সেকশন তৈরি করুন
        $section = $phpWord->addSection(['marginLeft' => 720, 'marginRight' => 720, 'marginTop' => 720, 'marginBottom' => 720]);

        // ৪. ডকুমেন্টের কন্টেন্ট তৈরি করুন
        $section->addTitle('Home Estate', 'mainTitle');
        $section->addText('প্রপার্টি লিস্টিং ফর্ম', ['bold' => true, 'size' => 16], ['alignment' => 'center', 'spaceAfter' => 400]);
        $section->addText('ফর্ম পূরণের তারিখ: ______ / ______ / ________', null, ['spaceAfter' => 400]);

        // --- মালিকের তথ্য ---
        $section->addTitle('১. মালিকের তথ্য', 1);
        $table = $section->addTable('formTable');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('মালিকের নাম:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('ফোন নম্বর:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('ইমেইল অ্যাড্রেস:', $labelStyle);
        $table->addCell(7000, $cellStyle);

        // --- মূল তথ্য ---
        $section->addTitle('২. মূল তথ্য', 1);
        $table = $section->addTable('formTable');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('বিজ্ঞাপনের শিরোনাম:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('উদ্দেশ্য:', $labelStyle);
        $table->addCell(7000, $cellStyle)->addCheckBox('purpose_rent', 'ভাড়া');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('', null, ['spaceAfter' => 0]); // ખાલી সেল
        $table->addCell(7000, $cellStyle)->addCheckBox('purpose_sell', 'বিক্রয়');
        $table->addRow();
        $table->addCell(3000, ['valign' => 'top'])->addText('বিস্তারিত বর্ণনা:', $labelStyle);
        $table->addCell(7000)->addText(''); // বড় টেক্সট বক্সের জন্য

        // --- মূল্য নির্ধারণ ---
        $section->addTitle('৩. মূল্য নির্ধারণ', 1);
        $table = $section->addTable('formTable');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('ভাড়া / মূল্য (৳):', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('ভাড়ার ধরণ:', $labelStyle);
        $table->addCell(7000, $cellStyle)->addText(' ( ) দৈনিক   ( ) সাপ্তাহিক   ( ) মাসিক   ( ) বাৎসরিক');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('আলোচনা সাপেক্ষ?', $labelStyle);
        $table->addCell(7000, $cellStyle)->addText(' ( ) হ্যাঁ   ( ) না (একদাম)');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('সার্ভিস চার্জ (৳):', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('সিকিউরিটি ডিপোজিট (৳):', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('কবে থেকে পাওয়া যাবে:', $labelStyle);
        $table->addCell(7000, $cellStyle);

        // পৃষ্ঠা বিরতি (Page Break)
        $section->addPageBreak();

        // --- স্পেসিফিকেশন ---
        $section->addTitle('৪. প্রপার্টির স্পেসিফিকেশন', 1);
        $table = $section->addTable('formTable');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('বেডরুম সংখ্যা:', $labelStyle);
        $table->addCell(2000, $cellStyle);
        $table->addCell(3000, $cellStyle)->addText('ফ্লোর লেভেল:', $labelStyle);
        $table->addCell(2000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('বাথরুম সংখ্যা:', $labelStyle);
        $table->addCell(2000, $cellStyle);
        $table->addCell(3000, $cellStyle)->addText('মোট তলা:', $labelStyle);
        $table->addCell(2000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('বারান্দা সংখ্যা:', $labelStyle);
        $table->addCell(2000, $cellStyle);
        $table->addCell(3000, $cellStyle)->addText('কোনমুখী:', $labelStyle);
        $table->addCell(2000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('আকার (স্কয়ার ফিট):', $labelStyle);
        $table->addCell(2000, $cellStyle);
        $table->addCell(3000, $cellStyle)->addText('নির্মাণ সাল:', $labelStyle);
        $table->addCell(2000, $cellStyle);

        // --- অবস্থান ---
        $section->addTitle('৫. অবস্থান', 1);
        $table = $section->addTable('formTable');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('বিভাগ ও জেলা:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('উপজেলা ও ইউনিয়ন:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('এলাকা:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('বাসা ও রাস্তার ঠিকানা:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('পোস্ট কোড:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('গুগল ম্যাপস লিংক:', $labelStyle);
        $table->addCell(7000, $cellStyle);

        // --- অতিরিক্ত সুবিধা ও নিয়মাবলী ---
        $section->addTitle('৬. অতিরিক্ত সুবিধা ও নিয়মাবলী', 1);
        $table = $section->addTable('formTable');
        $table->addRow();
        $table->addCell(3000, ['valign' => 'top'])->addText('অতিরিক্ত সুবিধা:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(3000, ['valign' => 'top'])->addText('বাসার নিয়মাবলী:', $labelStyle);
        $table->addCell(7000, $cellStyle);

        // --- ছবি ও ভিডিও ---
        $section->addTitle('৭. ছবি ও ভিডিও', 1);
        $table = $section->addTable('formTable');
        $table->addRow();
        $table->addCell(3000, $cellStyle)->addText('ভিডিও লিংক:', $labelStyle);
        $table->addCell(7000, $cellStyle);
        $table->addRow();
        $table->addCell(10000, ['gridSpan' => 2, 'bgColor' => 'F2F2F2'])->addText(
            'ছবিগুলো অনুগ্রহ করে photos@homeestate.com ইমেইলে অথবা +8801xxxxxxxxx নম্বরে হোয়াটসঅ্যাপ করুন।',
            ['bold' => true],
            ['alignment' => 'center']
        );

        // --- স্বাক্ষর ---
        $section->addTextBreak(3);
        $section->addText('আমি এই মর্মে ঘোষণা করছি যে, উপরে প্রদত্ত সকল তথ্য আমার জ্ঞান ও বিশ্বাসমতে সম্পূর্ণ সত্য।');
        $section->addTextBreak(3);
        $section->addLine(['weight' => 1, 'width' => 300, 'height' => 0]);
        $section->addText('মালিকের স্বাক্ষর');

        // ৫. ফাইলটি সেভ করুন এবং ডাউনলোড করুন
        $filename = 'home-estate-property-listing-form.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }
}
