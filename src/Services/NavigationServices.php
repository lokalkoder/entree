<?php

namespace Lokalkoder\Entree\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Joesama\Pintu\Services\ComponentServices;
use Lokalkoder\Entree\Services\Concerns\NeedRolesAndPermissions;

class NavigationServices
{
    use NeedRolesAndPermissions;

    protected Collection $permission;

    public function __construct()
    {
        $this->permission = self::getAuthorizeGroupAccess();
    }

    /**
     * @return Collection
     */
    public function menu(): Collection
    {
        return $this->getMenuComponent($this->permission);
    }

    /**
     * @return Collection
     */
    public function flat(): Collection
    {
        return $this->menu()->flatMap(function ($menu) {
            return $menu['sub_menus'];
        });
    }

    /**
     * @param  string  $item
     * @param  array  $param
     * @param  string|null  $icon
     * @return array
     */
    private static function format(string $item, array $param = [], string $icon = null): array
    {
        $isModule = empty($param);

        $route = ($isModule) ? '#' : Arr::get($param, 'named.get');

        $label = ($isModule) ? __('menu.'.$item.'.title') : __('menu.'.$item);

        $class = ($isModule) ? null : 'nav-link-text';

        $isTitle = (bool) $isModule;

        return [
            'icon' => $icon,
            'route' => $route,
            'url' => ($isModule) ? '/' : (Route::has($route) ? route($route) : null),
            'label' => $label,
            'tags' => str_replace('.', ',', $item),
            'class' => $class,
            'is_title' => false,
            'sub_menus' => null,
        ];
    }

    /**
     * @return Collection
     */
    protected function getComponentCollection(): Collection
    {
        return (new ComponentServices('App\Providers\RouteServiceProvider'))->toCollection();
    }

    /**
     * @param $permission
     * @return Collection
     */
    protected function getMenuComponent($permission): Collection
    {
        return $this->getComponentCollection()->transform(function ($submodule, $module) use ($permission) {
            if (config('entree.enable_permission') && ! $permission->has($module)) {
                return null;
            }

            $format = self::format($module, [], $module);

            $submodule = collect($submodule);

            if (($index = $submodule->get('index')) && $submodule->count() == 1) {
                $format = self::format($module.'.index', $index);
            } else {
                $format['sub_menus'] = $submodule->transform(function ($sub, $dir) use ($module, $permission) {
                    if (config('entree.enable_permission')) {
                        return in_array($dir, $permission->get($module)) ? self::format($module.'.'.$dir, $sub) : null;
                    }

                    return self::format($module.'.'.$dir, $sub);
                })->filter(function ($menu) {
                    return $menu !== null;
                });
            }

            return $format;
        })->filter(function ($menu) {
            return $menu !== null;
        });
    }
}
