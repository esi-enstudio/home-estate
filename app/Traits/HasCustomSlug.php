<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Normalizer;

trait HasCustomSlug
{
    /**
     * Boot the trait to handle model events.
     */
    protected static function bootHasCustomSlug(): void
    {
        static::creating(function (Model $model) {
            // মডেলটিতে getSluggableField() মেথড আছে কি না তা চেক করুন
            if (method_exists($model, 'getSluggableField')) {
                $sourceField = $model->getSluggableField();

                // যদি slug খালি থাকে এবং সোর্স ফিল্ডে ভ্যালু থাকে
                if (empty($model->slug) && !empty($model->{$sourceField})) {
                    $model->slug = self::generateUniqueCustomSlug($model->{$sourceField});
                }
            }
        });

        static::updating(function (Model $model) {
            if (method_exists($model, 'getSluggableField')) {
                $sourceField = $model->getSluggableField();

                // যদি সোর্স ফিল্ডটি পরিবর্তন হয়ে থাকে
                if ($model->isDirty($sourceField)) {
                    $model->slug = self::generateUniqueCustomSlug($model->{$sourceField}, $model->id);
                }
            }
        });
    }

    /**
     * Generate a unique, custom slug based on the source string.
     * Handles both Bangla and English text.
     *
     * @param string $source The string to generate a slug from.
     * @param int|null $exceptId The ID of the current model to exclude.
     * @return string
     */
    private static function generateUniqueCustomSlug(string $source, int $exceptId = null): string
    {
        // --- ধাপ ১: মূল স্লাগ তৈরি করা ---
        $slug = self::createCustomSlug($source);

        // --- ধাপ ২: স্লাগটিকে ইউনিক করা ---
        $originalSlug = $slug;
        $count = 1;

        $query = static::where('slug', $slug);
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        // যদি একই স্লাগ বিদ্যমান থাকে, তাহলে শেষে একটি সংখ্যা যোগ করুন
        while ($query->clone()->exists()) { // clone() ব্যবহার করা ভালো অভ্যাস
            $slug = $originalSlug . '-' . $count++;
            $query = static::where('slug', 'like', $slug . '%');
        }

        return $slug;
    }

    /**
     * Create a custom slug from a string, with Unicode Normalization.
     *
     * @param string $text
     * @return string
     */
    private static function createCustomSlug(string $text): string
    {
        // ধাপ ১: ইউনিকোড নরমালাইজেশন (অত্যন্ত গুরুত্বপূর্ণ)
        if (class_exists('Normalizer')) {
            $text = \Normalizer::normalize($text, \Normalizer::FORM_C);
        }

        // ধাপ ২: সব কিছুকে ছোট হাতের অক্ষরে রূপান্তর করুন
        $slug = mb_strtolower($text, 'UTF-8');

        // ধাপ ৩: ভাষা সনাক্তকরণ
        $isBangla = preg_match('/[\p{Bengali}]/u', $slug);

        // ধাপ ৪: সব ধরনের স্পেস, নিউলাইন এবং ট্যাবের একাধিক উপস্থিতিকে একটি মাত্র স্পেসে পরিণত করুন
        $slug = preg_replace('/\s+/u', ' ', $slug);

        if ($isBangla) {
            // --- বাংলার জন্য নিরাপদ লজিক ---

            // ক. শুধুমাত্র বাংলা অক্ষর, ইংরেজি সংখ্যা, এবং স্পেস রাখুন।
            // বাকি সব বিশেষ চিহ্ন (!, @, #, <, >, [], ইত্যাদি) মুছে ফেলুন।
            // \x{0980}-\x{09FF} হলো বাংলা অক্ষরের ইউনিকোড রেঞ্জ।
            $slug = preg_replace('/[^\x{0980}-\x{09FF}0-9\s]/u', '', $slug);

        } else {
            // --- ইংরেজির জন্য নিরাপদ লজিক ---

            // ক. শুধুমাত্র ইংরেজি অক্ষর, সংখ্যা এবং স্পেস রাখুন।
            $slug = preg_replace('/[^a-z0-9\s]/', '', $slug);
        }

        // ধাপ ৫: শুরু বা শেষের স্পেস মুছে ফেলুন
        $slug = trim($slug);

        // ধাপ ৬: সব স্পেসকে একটি হাইফেন দিয়ে প্রতিস্থাপন করুন
        $slug = str_replace(' ', '-', $slug);

        // ধাপ ৭: যদি কোনো কারণে একাধিক হাইফেন তৈরি হয়, সেটিকে একটিতে পরিণত করুন
        $slug = preg_replace('/-+/', '-', $slug);

        // যদি কোনো কারণে স্ট্রিং খালি হয়ে যায়
        if (empty($slug)) {
            return 'n-a-' . time();
        }

        return $slug;
    }

    /**
     * Get the field name that should be used for generating the slug.
     * This method must be implemented in the model using this trait.
     */
    abstract public function getSluggableField(): string;
}
