<?php

namespace App\Observers;

use App\Filament\Resources\PropertyResource;
use App\Mail\NewPropertyPendingMail;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// <-- PropertyType মডেলটি ইম্পোর্ট করুন

class PropertyObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the Property "created" event.
     * Fires after a property is created.
     * @throws Exception
     */
    public function created(Property $property): void
    {
        // ডাটাবেজ থেকে সর্বশেষ ডেটা (ডিফল্ট 'status' সহ) নিয়ে মডেলটিকে রিফ্রেশ করা হচ্ছে।
        $property->refresh();

        $owner = $property->user;

        // আপনার রোলের নাম 'super_admin' (ছোট হাতের) অনুযায়ী আপডেট করা হয়েছে
        if ($owner && !$owner->hasRole('super_admin')) {
            Log::info("Condition met: Owner is not a Super Admin. Owner: {$owner->name}");

            $admins = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->get();
            $adminEmail = config('mail.admin_address', 'admin@example.com');

            if ($admins->isNotEmpty()) {
                Log::info("Sending notification to admins...");

                // === START: মূল পরিবর্তন - Superadmin প্যানেলের জন্য URL তৈরি ===
                $url = Filament::getPanel('superadmin')
                    ->getResourceUrl(Property::class, 'edit', ['record' => $property]);
                // === END ===

                Notification::make()
                    ->title('নতুন প্রপার্টি পর্যালোচনার জন্য জমা হয়েছে')
                    ->icon('heroicon-o-home-modern')
                    ->body("{$owner->name} একটি নতুন প্রপার্টি '{$property->title}' জমা দিয়েছেন।")
                    ->actions([
                        Action::make('view')
                            ->label('প্রপার্টিটি দেখুন')
                            ->url($url), // <-- নতুন URL ভ্যারিয়েবলটি এখানে ব্যবহার করা হচ্ছে
                    ])
                    ->sendToDatabase($admins);

                Log::info("Notification sent with URL: " . $url);
            }

            if ($adminEmail) {
                Mail::to($adminEmail)->send(new NewPropertyPendingMail($property));
            }
        }

        // --- পুরোনো লজিক অপরিবর্তিত ---
        if ($property->propertyType) {
            $property->propertyType->increment('properties_count');
        }
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

        Cache::forget('homepage_stats');
    }
}
