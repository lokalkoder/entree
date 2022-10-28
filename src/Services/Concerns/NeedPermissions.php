<?php

namespace Lokalkoder\Entree\Services\Concerns;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

trait NeedPermissions
{
    /**
     * @return mixed
     */
    public function registeredPermissions(): mixed
    {
        $permissions = collect([]);

        (Permission::pluck('description', 'name'))->each(function ($permission, $key) use ($permissions) {
            $type = explode('.', $key);

            $group = $type[1].'.'.$type[2];

            $set = $permissions->get($group) ?? collect([
                'title' => __('menu.'.$group),
                'item' => collect([]),
            ]);

            if ($set !== null) {
                $set['item']->push([
                    'id' => $key,
                    'desc' => Str::title($type[0]).' '.__('menu.'.$group),
                ]);

                $permissions->put($group, $set);
            }
        });

        return $permissions;
    }
}
