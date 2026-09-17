<?php

namespace App\Traits;

/**
 * @property string $role
 */
trait HasRoles
{
    public function hasRole(string $role): bool
    {
        return ($this->role ?? null) === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role ?? null, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isEditor(): bool
    {
        return $this->hasRole('editor');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }
}
