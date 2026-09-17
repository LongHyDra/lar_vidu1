<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasRoles
{
    /**
     * Kiểm tra người dùng có role cụ thể không
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return Auth::check() && Auth::user()->role === $role;
    }

    /**
     * Kiểm tra người dùng có một trong các role này không
     *
     * @param  array  $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        return Auth::check() && in_array(Auth::user()->role, $roles);
    }

    /**
     * Kiểm tra người dùng có phải admin không
     *
     * @return bool
     */
    public function isAdmin()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Kiểm tra người dùng có phải editor không
     *
     * @return bool
     */
    public function isEditor()
    {
        return Auth::check() && Auth::user()->role === 'editor';
    }

    /**
     * Kiểm tra người dùng có phải manager không
     *
     * @return bool
     */
    public function isManager()
    {
        return Auth::check() && Auth::user()->role === 'manager';
    }

    /**
     * Kiểm tra người dùng có phải customer không
     *
     * @return bool
     */
    public function isCustomer()
    {
        return Auth::check() && Auth::user()->role === 'customer';
    }
}
