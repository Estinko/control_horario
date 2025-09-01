<?php

namespace App\Policies;

use App\Models\TimeRecord;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TimeRecord $timeRecord): bool
    {
        if ($user->is_admin) {
            return true;
        } else {
            return $timeRecord->user_id == $user->id;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !($user->is_admin);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TimeRecord $timeRecord): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeRecord $timeRecord): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TimeRecord $timeRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TimeRecord $timeRecord): bool
    {
        return false;
    }
}
