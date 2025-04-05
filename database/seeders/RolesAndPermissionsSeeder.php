<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        // User permissions
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'place orders']);

        // Company permissions
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);
        Permission::create(['name' => 'view orders']);

        // Admin permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage companies']);
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'view reports']);

        // Create roles and assign permissions
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo(['view products', 'place orders']);

        $role = Role::create(['name' => 'company']);
        $role->givePermissionTo([
            'view products',
            'place orders',
            'create products',
            'edit products',
            'delete products',
            'view orders'
        ]);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());
    }
}