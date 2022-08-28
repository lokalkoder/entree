<?php

namespace Lokalkoder\Entree\Services\Concerns;

use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

trait UsingSpatieRole
{
    use HasRoles;

    /**
     * Scope the model query to certain roles only.
     *
     * @param  Builder  $query
     * @param  string|array|Role|Collection  $roles
     * @param  string|null  $guard
     * @return Builder
     */
    public function scopeNotRole(Builder $query, $roles, string $guard = null): Builder
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        if (! is_array($roles)) {
            $roles = [$roles];
        }

        $roles = array_map(function ($role) use ($guard) {
            if ($role instanceof Role) {
                return $role;
            }

            $method = is_numeric($role) ? 'findById' : 'findByName';
            $guard = $guard ?: $this->getDefaultGuardName();

            return $this->getRoleClass()->{$method}($role, $guard);
        }, $roles);

        return $query->whereHas('roles', function (Builder $subQuery) use ($roles) {
            $subQuery->whereNotIn(config('permission.table_names.roles').'.id', \array_column($roles, 'id'));
        });
    }
}
