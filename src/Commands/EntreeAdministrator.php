<?php

namespace Lokalkoder\Entree\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lokalkoder\Entree\Commands\Concerns\UseEntreeModel;

class EntreeAdministrator extends Command
{
    use UseEntreeModel;

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

        $user = $this->userModel()->where('email', $email)->first();

        if ($user === null) {
            $this->info('User not exist');

            if ($this->confirm('Do you wish to create new?')) {
                $name = $this->ask('What is your name?');
                $password = $this->secret('What is the password?');

                $user = $this->userModel()->updateOrCreate(
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

                $userRoles[] = $this->roleModel()->isAdmin()->first()->id;
            } else {
                foreach (explode(',', $roles) as $role) {
                    $role = $this->roleModel()->updateOrCreate(
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
