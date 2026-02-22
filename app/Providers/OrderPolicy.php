<?php
// app/Policies/OrderPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view orders');
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        // User can view their branch orders
        if ($user->hasPermissionTo('view orders')) {
            // Super Admin can view all
            if ($user->hasBranchRole('Super Admin', null)) {
                return true;
            }

            // Check if user has access to this branch
            return $user->branchRoles()
                ->where('branch_id', $order->branch_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create orders');
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        // Only allow editing pending/processing orders
        if (!in_array($order->status, ['pending', 'processing'])) {
            return false;
        }

        return $this->view($user, $order) && $user->hasPermissionTo('edit orders');
    }

    /**
     * Determine whether the user can process the order.
     */
    public function process(User $user, Order $order): bool
    {
        return $this->view($user, $order) && $user->hasPermissionTo('process orders');
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        // Can't delete orders with payments
        if ($order->payments()->exists()) {
            return false;
        }

        return $this->view($user, $order) && $user->hasPermissionTo('delete orders');
    }
}
