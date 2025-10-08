<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * এই মেথডটি অন্য যেকোনো মেথড কল হওয়ার আগে কাজ করে।
     */
    public function before(User $user, string $ability): bool|null
    {
        // যদি ব্যবহারকারীর 'super_admin' রোল থাকে, তাহলে তাকে সকল অনুমতি দেওয়া হবে।
        // এটি আমাদের অন্য মেথডগুলোকে অনেক পরিষ্কার রাখে।
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null; // যদি সুপার-অ্যাডমিন না হয়, তাহলে সাধারণ নিয়ম প্রযোজ্য হবে।
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_review');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Review $review): bool
    {
        return $user->can('view_review');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_review');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->can('update_review') || $user->id === $review->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->can('delete_review') || $user->id === $review->user_id;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_review');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Review $review): bool
    {
        return $user->can('force_delete_review');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_review');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Review $review): bool
    {
        return $user->can('restore_review');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_review');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Review $review): bool
    {
        return $user->can('replicate_review');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_review');
    }
}
