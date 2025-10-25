<?php

namespace App\Models;

use App\Traits\HasCustomSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\HtmlString;

/**
 * @method static withCount(string $string)
 * @method static create(array $array)
 * @method static truncate()
 */
class Amenity extends Model
{
    use HasCustomSlug;

    protected $fillable = ['slug','name_en','name_bn','icon_class','type','show_on_homepage','properties_count'];


    public function getSluggableField(): string
    {
        return 'name_en';
    }

    public function getRouteKeyName(): string
    {
        return 'slug'; // Use slug instead of id in routes
    }

    /**
     * The properties that have this amenity.
     */
    public function properties(): BelongsToMany
    {
        // একটি সুবিধার অনেকগুলো প্রপার্টি থাকতে পারে
        return $this->belongsToMany(Property::class)
            ->withTimestamps();
    }

    /**
     * Get the full HTML for the amenity's icon.
     * This is an Eloquent Accessor, accessed via the `$amenity->icon_html` property.
     *
     * আপনি Filament থেকে যে আইকন ক্লাসই দিন না কেন (Material Icons বা FontAwesome), ফ্রন্টএন্ডে সেটি স্বয়ংক্রিয়ভাবে এবং সঠিকভাবে প্রদর্শিত হবে।
     * @return HtmlString
     */
    public function getIconHtmlAttribute(): HtmlString
    {
        $iconClass = $this->icon_class;
        $defaultIcon = 'interests'; // যদি কোনো আইকন সেট করা না থাকে

        // যদি icon_class খালি থাকে, তাহলে একটি ডিফল্ট আইকন দেখাও
        if (empty($iconClass)) {
            return new HtmlString('<i class="material-icons-outlined">' . $defaultIcon . '</i>');
        }

        // আপনার ফর্মের helper text অনুযায়ী, আমরা চেক করব যে ক্লাসের নামে স্পেস আছে কিনা
        // স্পেস থাকলে (e.g., "fa-solid fa-wifi"), ধরে নেব এটি FontAwesome
        // স্পেস না থাকলে (e.g., "wifi"), ধরে নেব এটি Material Icons
        if (str_contains($iconClass, ' ')) {
            // FontAwesome বা অন্য কোনো ফুল ক্লাস-ভিত্তিক আইকনের জন্য
            return new HtmlString('<i class="' . e($iconClass) . '"></i>');
        } else {
            // Material Icons এর জন্য
            return new HtmlString('<i class="material-icons-outlined">' . e($iconClass) . '</i>');
        }
    }
}
