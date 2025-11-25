<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(Admin $admin)
    {
        return true;
    }

    public function view(Admin $admin, Product $product)
    {
        return true;
    }

    public function create(Admin $admin)
    {
        \Log::info('Checking create permission', ['admin_id' => $admin->id]);
        return true;
    }

    public function update(Admin $admin, Product $product): bool
    {
        return $admin !== null;
    }

    public function delete(Admin $admin, Product $product)
    {
        return true;
    }
}