<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isShopOwner() && $user->hasShop();
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$product->shop_id) {
            return false;
        }

        return $user->isShopOwner() && $product->shop->user_id === $user->id;
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$product->shop_id) {
            return false;
        }

        return $user->isShopOwner() && $product->shop->user_id === $user->id;
    }
}