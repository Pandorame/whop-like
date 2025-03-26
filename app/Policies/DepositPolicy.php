<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Deposit;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepositPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view_any_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can view the model.
     */
    public function view(Admin $admin, Deposit $deposit): bool
    {
        return $admin->can('view_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->can('create_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can update the model.
     */
    public function update(Admin $admin, Deposit $deposit): bool
    {
        return $admin->can('update_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can delete the model.
     */
    public function delete(Admin $admin, Deposit $deposit): bool
    {
        return $admin->can('delete_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can bulk delete.
     */
    public function deleteAny(Admin $admin): bool
    {
        return $admin->can('delete_any_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can permanently delete.
     */
    public function forceDelete(Admin $admin, Deposit $deposit): bool
    {
        return $admin->can('force_delete_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can permanently bulk delete.
     */
    public function forceDeleteAny(Admin $admin): bool
    {
        return $admin->can('force_delete_any_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can restore.
     */
    public function restore(Admin $admin, Deposit $deposit): bool
    {
        return $admin->can('restore_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can bulk restore.
     */
    public function restoreAny(Admin $admin): bool
    {
        return $admin->can('restore_any_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can replicate.
     */
    public function replicate(Admin $admin, Deposit $deposit): bool
    {
        return $admin->can('replicate_sub::agent::deposit');
    }

    /**
     * Determine whether the admin can reorder.
     */
    public function reorder(Admin $admin): bool
    {
        return $admin->can('reorder_sub::agent::deposit');
    }
}
