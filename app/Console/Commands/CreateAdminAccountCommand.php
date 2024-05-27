<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminAccountCommand extends Command
{
    protected $signature = 'create:admin-account';

    protected $description = 'Command description';

    public function handle(): void
    {
        $user = User::where('name', 'Admin')->first();
        if (! $user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $this->info('Admin user created successfully');

        $role = Role::where('name', 'admin')->first();
        if (! $role) {
            $role = Role::create(['name' => 'admin']);
        }

        $this->info('Admin role created successfully');

        $permission = Permission::where('name', 'access admin panel')->first();
        if (! $permission) {
            // If no permission exists, create a new one
            $permission = Permission::create(['name' => 'access admin panel']);
        }

        $role->givePermissionTo($permission);

        $user->assignRole($role);

        if (! $user->privacySettings) {
            $user->privacySettings()->create();
        }

        $this->info('Admin account was created successfully');
    }
}
