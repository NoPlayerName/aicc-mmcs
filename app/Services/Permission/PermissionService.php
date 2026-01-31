<?php

namespace App\Services\Permission;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PermissionService
{
    public static function can(User $user, string $route, string $access = "can_access")
    {

        $query = $user->userPermission()
            ->whereHas('menu', function ($q) use ($route) {
                $q->where('apps_group', 'MMCS')
                    ->where('url', $route);
            })
            ->where($access, 1);
        return $query->exists();
    }
}
