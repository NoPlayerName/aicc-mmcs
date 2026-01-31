<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Permission\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{


    public static function login($usr, $password,)
    {

        $user = User::where('usr', $usr)->first();
        // $routeName = $request->route()->getName();
        if ($user && $user->pswd === md5($password)) {
            if (PermissionService::can($user, '/', 'can_access')) {
                Auth::login($user);
                return true;
            }
            return false;
        }
        return false;
    }

    public static function logout()
    {
        Auth::logout();
        session()->invalidate();
        redirect()->route('login');
    }
}
