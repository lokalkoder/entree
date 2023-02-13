<?php

namespace Lokalkoder\Entree\Commands;

use App\Providers\RouteServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Joesama\Pintu\Services\ComponentServices;
use Lokalkoder\Entree\Commands\Concerns\UseEntreeModel;
use Spatie\Permission\PermissionRegistrar;

class EntreeGivePermission extends Command
{
    use UseEntreeModel;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entree:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed permission for role';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $components = (new ComponentServices(RouteServiceProvider::class))->toArray();

        $permissions = config('setup.roles.permit');

        if (app()->environment('local')) {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \DB::table('permissions')->truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $access = collect([]);

        // update permissions
        collect($components)->each(function ($component, $key) use ($permissions, $access) {
            collect($component)->each(function ($subcomponent, $subkey) use ($key, $permissions, $access) {
                if ($key === 'dashboard') {
                    $permittedAction = ['access'];
                } else {
                    $permittedAction = $permissions;
                }

                collect($permittedAction)->each(function ($permission) use ($key, $subkey, $access) {
                    $name = $permission.'.'.$key.'.'.$subkey;

                    $permit = $this->permissionModel()->updateOrCreate(
                        ['name' => $name, 'guard_name' => 'web'],
                        ['description' => str_replace('.', ' ', $name)]
                    );

                    if ($permission === 'access') {
                        $access->push($permit);
                    }
                });
            });
        });

        $adminRole = $this->roleModel()->updateOrCreate(
            ['name' => config('setup.roles.admin')],
            ['description' => config('setup.roles.admin')]
        );

        if (! Str::isUuid($adminRole->uuid)) {
            $adminRole->uuid = Str::uuid();
            $adminRole->save();
        }

        $adminRole->givePermissionTo($this->permissionModel()->all());

        foreach (config('setup.roles.default') as $role => $access) {
            $role = $this->roleModel()->updateOrCreate(
                ['name' => $role],
                ['description' => $role]
            );

            if (! Str::isUuid($role->uuid)) {
                $role->uuid = Str::uuid();
                $role->save();
            }

            foreach ($access as $permit) {
                $role->givePermissionTo(
                    $this->permissionModel()->where('name', 'not like', '%setting%')
                        ->where('name', 'like', '%' . $permit . '%')
                        ->get()
                );
            }
        }
    }
}
