<?php

namespace Lokalkoder\Entree\Services;

use Illuminate\Support\Collection;
use Joesama\Pintu\Services\ComponentServices;
use Lokalkoder\Entree\Services\Concerns\NeedRolesAndPermissions;

class NavigationServices
{
    use NeedRolesAndPermissions;

    public Collection $menu;

    public function __construct()
    {
        $permission = self::getAuthorizeGroupAccess();

        $this->menu = ($this->getComponentCollection())->transform(function ($submodule, $module) use ($permission) {
            if (! $permission->has($module)) {
                return null;
            }

            $format = self::format($module, [], $module);

            $format['sub_menus'] = collect($submodule)->transform(function ($sub, $dir) use ($module, $permission) {
                return in_array($dir, $permission->get($module)) ? self::format($module.'.'.$dir, $sub) : null;
            })->filter(function ($menu) {
                return $menu !== null;
            });

            return $format;
        })->filter(function ($menu) {
            return $menu !== null;
        });
    }

    /**
     * @return Collection
     */
    public static function flat(): Collection
    {
        return collect(self::component())->flatMap(function ($menu) {
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

        $isTitle = ($isModule) ? true : false;

        return [
            'icon' => $icon,
            'route' => $route,
            'url' => ($isModule) ? '/' : route($route),
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
}
