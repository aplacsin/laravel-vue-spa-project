<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsUserSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'show posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);

        $role1 = Role::create(['name' => 'editor']);
        $role1->givePermissionTo('show posts');
        $role1->givePermissionTo('edit posts');

        $role2 = Role::create(['name' => 'subscriber']);
        $role2->givePermissionTo('show posts');

        $role3 = Role::create(['name' => 'super-admin']);

        $user = User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => password_hash('12345678', PASSWORD_BCRYPT),
        ]);
        $user->assignRole($role1);

        $user = User::factory()->create([
            'name' => 'Subscriber',
            'email' => 'subscriber@example.com',
            'password' => password_hash('12345678', PASSWORD_BCRYPT),
        ]);
        $user->assignRole($role2);

        $user = User::factory()->create([
            'name' => 'Super-Admin',
            'email' => 'admin@example.com',
            'password' => password_hash('12345678', PASSWORD_BCRYPT),
        ]);
        $user->assignRole($role3);
    }
}
