<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // পলিসি চেক: ইউজার কি কোনো প্রোপার্টি দেখতে পারবে?
//        $this->authorize('viewAny', Property::class);

        $properties = Auth::user()->properties()
            ->with('propertyType') // রিলেশনশিপ লোড করা
            ->latest()
            ->paginate(5);

        return view('listings.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // পলিসি চেক: ইউজার কি নতুন প্রোপার্টি তৈরি করতে পারবে?
//        $this->authorize('create', Property::class);
        return view('listings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $listing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $listing)
    {
        // পলিসি চেক: এই নির্দিষ্ট প্রোপার্টিটি কি ইউজার এডিট করতে পারবে?
//        $this->authorize('update', $listing);
        return view('listings.edit', ['listing' => $listing]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $listing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $listing)
    {
        // পলিসি চেক: এই নির্দিষ্ট প্রোপার্টিটি কি ইউজার ডিলিট করতে পারবে?
//        $this->authorize('delete', $listing);

        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success', 'আপনার লিস্টিং সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

// দ্রষ্টব্য: store এবং update মেথডের কাজ Livewire কম্পোনেন্ট করবে,
// তাই এই কন্ট্রোলারে সেগুলোর কোড লেখার প্রয়োজন নেই।
