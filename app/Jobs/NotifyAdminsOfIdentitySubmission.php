<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Filament\Facades\Filament;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifyAdminsOfIdentitySubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // === START: মূল এবং চূড়ান্ত সমাধান এখানে ===
    /**
     * Create a new job instance.
     * আমরা এখন সম্পূর্ণ User মডেলের পরিবর্তে শুধুমাত্র user ID গ্রহণ করছি।
     */
    public function __construct(public int $submitterId)
    {
        //
    }
    // === END ===

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // === START: ID ব্যবহার করে মডেলটিকে নতুন করে লোড করা ===
            $submitter = User::find($this->submitterId);

            if (!$submitter) {
                Log::error("User with ID {$this->submitterId} not found in NotifyAdminsOfIdentitySubmission job.");
                return;
            }
            // === END ===

            Log::info("Job started for user: {$submitter->name}");

            $admins = User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->get();

            if ($admins->isEmpty()) {
                Log::warning("No super admins found to notify.");
                return;
            }

            // $this->submitter এর পরিবর্তে এখন $submitter ব্যবহার করা হচ্ছে
            $url = Filament::getPanel('superadmin')
                ->getResourceUrl(\App\Filament\Resources\UserResource::class, 'edit', ['record' => $submitter]);

            Notification::make()
                ->title('নতুন পরিচয়পত্র ভেরিফিকেশনের জন্য আবেদন')
                ->body("ব্যবহারকারী {$submitter->name} তার পরিচয়পত্র জমা দিয়েছেন।")
                ->actions([
                    Action::make('view')->label('পর্যালোচনা করুন')->url($url),
                ])
                ->sendToDatabase($admins);

            Log::info("Notification job completed successfully for user ID: {$submitter->id}");

        } catch (\Exception $e) {
            Log::error("Error in NotifyAdminsOfIdentitySubmission job: " . $e->getMessage());
            report($e);
        }
    }
}
