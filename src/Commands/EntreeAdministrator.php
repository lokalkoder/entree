<?php

namespace Lokalkoder\Entree\Commands;

use App\Models\Settings\Role;
use App\Models\Settings\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EntreeAdministrator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entree:administrator {email} {--role= : Role to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set User Role Base One Email';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $roles = $this->option('role');
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if ($user === null) {
            $this->info('User not exist');

            if ($this->confirm('Do you wish to create new?')) {
                $name = $this->ask('What is your name?');
                $password = $this->secret('What is the password?');

                $user = User::updateOrCreate(
                    [
                        'email' => $email,
                    ],
                    [
                        'name' => Str::title($name),
                        'password' => Hash::make($password),
                    ]
                );
            }
        }

        $userRoles = [];

        if ($user) {
            $this->info('User exist');

            if (empty($roles)) {
                $user->is_admin = true;
                $user->save();

                $userRoles[] = Role::isAdmin()->first()->id;
            } else {
                foreach (explode(',', $roles) as $role) {
                    $role = Role::updateOrCreate(
                        ['name' => $role],
                        ['description' => Str::title($role)]
                    );

                    $userRoles[] = $role->id;
                }
            }

            $this->info('Attach user roles');
            $user->roles()->syncWithoutDetaching($userRoles);
        }
    }
}
