<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait MarksAsRead
{
    /**
     * Mark the given record as read if it hasn't been already.
     */
    protected static function markAsRead(Model $record): void
    {
        // নিশ্চিত করুন যে মডেলে 'is_read' নামে একটি কলাম আছে
        if (isset($record->is_read) && !$record->is_read) {
            // saveQuietly() ব্যবহার করলে কোনো updated_at ইভেন্ট ফায়ার হবে না
            $record->forceFill(['is_read' => 1, 'read_at' => now()])->saveQuietly();
        }
    }
}
