<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Lokalkoder\Entree\Services\Concerns\NeedRolesAndPermissions;
use Lokalkoder\Entree\Services\NavigationServices;
use Yadahan\AuthenticationLog\AuthenticationLogable;

class EntreeRequests extends Middleware
{
    use NeedRolesAndPermissions;

    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'menu' => function () {
                return (new NavigationServices())->menu();
            },
            'auth.permissions' => function () use ($request) {
                return $this->needUserPermissionsName($request);
            },
            'auth.roles' => function () use ($request) {
                return $this->needUserRoles($request);
            },
            'auth.login' => function () use ($request) {
                $user = $request->user();

                if ($user && in_array(AuthenticationLogable::class, class_uses(get_class($user)), true)) {
                    return Carbon::parse($user->lastLoginAt())->diffForHumans();
                }

                return now();
            },
            'messages' => function () {
                return [
                    'success' => Session::get('success'),
                    'warning' => Session::get('warning'),
                    'error' => Session::get('error'),
                ];
            },
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
            'info' => function () {
                return [
                    'name' => config('app.name'),
                    'release' => now()->year,
                ];
            },
            'inspiration' => function () {
                return Inspiring::quote();
            },
        ]);
    }
}
