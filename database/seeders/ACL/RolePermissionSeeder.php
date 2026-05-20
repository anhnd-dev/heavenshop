<?php

namespace Database\Seeders\ACL;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get Roles
        |--------------------------------------------------------------------------
        */

        $superAdminRole = Role::where('slug', 'super-admin')->first();

        $managerRole = Role::where('slug', 'manager')->first();

        $staffRole = Role::where('slug', 'staff')->first();

        /*
        |--------------------------------------------------------------------------
        | Super Admin Permissions
        |--------------------------------------------------------------------------
        */

        if ($superAdminRole) {

            $superAdminRole->permissions()->sync(
                Permission::pluck('id')->toArray()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Manager Permissions
        |--------------------------------------------------------------------------
        */

        if ($managerRole) {

            $managerPermissions = Permission::whereIn('name', [

                'dashboard-view',

                'product-view',
                'product-create',
                'product-edit',

                'category-view',
                'category-create',
                'category-edit',

                'order-view',
                'order-update',

                'user-view',

            ])->pluck('id')->toArray();

            $managerRole->permissions()->sync($managerPermissions);
        }

        /*
        |--------------------------------------------------------------------------
        | Staff Permissions
        |--------------------------------------------------------------------------
        */

        if ($staffRole) {

            $staffPermissions = Permission::whereIn('name', [

                'dashboard-view',

                'product-view',

                'category-view',

                'order-view',

            ])->pluck('id')->toArray();

            $staffRole->permissions()->sync($staffPermissions);
        }
    }
}
