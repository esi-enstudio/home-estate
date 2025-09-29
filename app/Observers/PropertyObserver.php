<?php

namespace App\Observers;

use App\Models\Property;
use App\Models\PropertyType; // <-- PropertyType মডেলটি ইম্পোর্ট করুন

class PropertyObserver
{
    /**
     * Handle the Property "created" event.
     * Fires after a property is created.
     */
    public function created(Property $property): void
    {
        // নতুন প্রপার্টি তৈরি হলে, সংশ্লিষ্ট PropertyType এর কাউন্টার ১ বাড়াও।
        if ($property->propertyType) {
            $property->propertyType->increment('properties_count');
        }

        // স্কোর গণনা করার জন্য saved মেথডটিকে কল করুন (ঐচ্ছিক, যদি আপনি created এবং updated আলাদা রাখেন)
        $this->calculateAndSetScore($property);
    }

    /**
     * Handle the Property "updated" event.
     * Fires after a property is updated.
     */
    public function updated(Property $property): void
    {
        if ($property->wasChanged('property_type_id')) {
            $originalTypeId = $property->getOriginal('property_type_id');
            if ($originalTypeId) {
                // === START: নিরাপদ Decrement ===
                $oldType = PropertyType::find($originalTypeId);
                // শুধুমাত্র যদি কাউন্টার শূন্যের চেয়ে বেশি হয়, তবেই কমাও
                if ($oldType && $oldType->properties_count > 0) {
                    $oldType->decrement('properties_count');
                }
                // === END: নিরাপদ Decrement ===
            }

            $property->propertyType?->increment('properties_count');
        }

        $this->calculateAndSetScore($property);
    }

    /**
     * Handle the Property "deleted" event.
     * Fires after a property is soft deleted or force deleted.
     */
    public function deleted(Property $property): void
    {
        // === START: নিরাপদ Decrement ===
        $propertyType = $property->propertyType;
        // শুধুমাত্র যদি কাউন্টার শূন্যের চেয়ে বেশি হয়, তবেই কমাও
        if ($propertyType && $propertyType->properties_count > 0) {
            $propertyType->decrement('properties_count');
        }
        // === END: নিরাপদ Decrement ===
    }

    /**
     * Handle the Property "restored" event.
     * Fires after a soft-deleted property is restored.
     */
    public function restored(Property $property): void
    {
        // প্রপার্টি পুনরুদ্ধার হলে, সংশ্লিষ্ট PropertyType এর কাউন্টার ১ বাড়াও।
        if ($property->propertyType) {
            $property->propertyType->increment('properties_count');
        }
    }


    /**
     * Calculates the score of a property based on various criteria and updates it.
     * (আপনার স্কোর গণনার মেথডটি অপরিবর্তিত)
     *
     * @param Property $property The property instance.
     * @return void
     */
    private function calculateAndSetScore(Property $property): void
    {
        $score = 0;
        // ... আপনার স্কোর গণনার সমস্ত লজিক এখানে থাকবে ...
        if ($property->is_verified) $score += 50;
        // ... etc ...

        // Update the score only if it has changed
        if ($property->score !== $score) {
            $property->score = $score;
            $property->saveQuietly();
        }
    }
}
