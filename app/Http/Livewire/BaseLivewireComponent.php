<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\Permission\PermissionService;
use Illuminate\Support\Facades\Auth;

abstract class BaseLivewireComponent extends Component
{
    /**
     * Route atau identifier menu untuk permission.
     * Bisa di-override manual di child component.
     */
    public string $route = '';

    /**
     * Mount base permission.
     */
    public function mountBase()
    {
        // Ambil route name dari current route kalau tidak di-set manual
        if (!$this->route) {
            $this->route = $this->getRouteKeyFromRequest();
        }

        // Batasi akses view halaman
        return $this->authorizeMethod('can_access');
        // return $this->route;
    }

    /**
     * Ambil route name dari request.
     */
    protected function getRouteKeyFromRequest(): string
    {
        // $route = request()->route();
        // return $route ? $route->getName() : '';
        return '/' . ltrim(request()->path(), '/');
    }

    /**
     * Cek permission
     */
    public function can(string $access = 'can_access'): bool
    {

        $user = Auth::user();
        if (!$user || !$this->route) return false;

        return PermissionService::can($user, $this->route, $access);
    }
    public function canView(string $route,  string $access = 'can_access'): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        return PermissionService::can($user, $route, $access);
    }

    /**
     * Batasi eksekusi method
     */
    protected function authorizeMethod(string $access = 'can_access')
    {
        return $this->can($access);
        if (! $this->can($access)) {
            abort(403, 'Unauthorized action.');
        }
    }
}
