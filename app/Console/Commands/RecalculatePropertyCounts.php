<?php

namespace App\Console\Commands;

use App\Models\PropertyType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculatePropertyCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-property-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting recalculation...');

        PropertyType::all()->each(function ($type) {
            // প্রতিটি টাইপের জন্য সরাসরি ডাটাবেজ থেকে গণনা করা হচ্ছে
            $actualCount = DB::table('properties')->where('property_type_id', $type->id)->count();

            // যদি গণনা করা মান এবং সেভ করা মান ভিন্ন হয়, তাহলে আপডেট করা হচ্ছে
            if ($type->properties_count != $actualCount) {
                $this->line("Updating count for '{$type->name_en}': {$type->properties_count} -> {$actualCount}");
                $type->properties_count = $actualCount;
                $type->saveQuietly();
            }
        });

        $this->info('Recalculation complete!');
        return 0;
    }
}
