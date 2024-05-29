<?php

namespace App\Policies;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_ban');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ban $ban): bool
    {
        return $user->can('view_ban');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_ban');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ban $ban): bool
    {
        return $user->can('update_ban');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ban $ban): bool
    {
        return $user->can('delete_ban');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_ban');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Ban $ban): bool
    {
        return $user->can('force_delete_ban');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_ban');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Ban $ban): bool
    {
        return $user->can('restore_ban');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_ban');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Ban $ban): bool
    {
        return $user->can('replicate_ban');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_ban');
    }
}
