<?php

namespace Lokalkoder\Entree\Services\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait NeedRolesAndPermissions
{
    /**
     * Need authenticate user roles.
     *
     * @param  Request|null  $request
     * @return Collection
     */
    public static function needUserRoles(Request $request = null): Collection
    {
        return ($user = self::authUser($request)) ? $user->roles : collect([]);
    }

    /**
     * Need authenticate user permissions.
     *
     * @param  Request|null  $request
     * @return Collection
     */
    public static function needUserPermissions(Request $request = null): Collection
    {
        return ($user = self::authUser($request)) ? $user->getAllPermissions() : collect([]);
    }

    /**
     * Need authenticate user permissions name.
     *
     * @param  Request|null  $request
     * @return Collection
     */
    public static function needUserPermissionsName(Request $request = null): Collection
    {
        return self::needUserPermissions($request)->transform(function ($permission) {
            return $permission->name;
        });
    }

    /**
     * Check if user authorize to access route.
     *
     * @param  Request  $request
     * @return bool
     */
    public static function canAccess(Request $request): bool
    {
        return self::needUserPermissionsName($request)
            ->has('access.'.$request->route()->getName());
    }

    /**
     * @return Collection
     */
    public static function getAuthorizeGroupAccess(): Collection
    {
        $permission = self::needUserPermissions()->filter(function ($permission) {
            return Str::contains($permission->name, 'access');
        })->mapToGroups(function ($permission) {
            $type = explode('.', $permission->name);

            return [$type[1] => $type[2]];
        })->map(function ($permission) {
            return array_unique($permission->toArray());
        });

        return $permission;
    }

    /**
     * Get requested authenticated user.
     *
     * @param  Request|null  $request
     * @return Authenticatable|mixed|null
     */
    private static function authUser(Request $request = null): mixed
    {
        return $request ? $request->user() : (Auth::check() ? Auth::user() : null);
    }
}
