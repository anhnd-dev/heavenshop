<?php

namespace Database\Seeders\ACL;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'dashboard-view',
                'module' => 'dashboard',
                'action' => 'view',
            ],

            /*
            |--------------------------------------------------------------------------
            | Product
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'product-view',
                'module' => 'product',
                'action' => 'view',
            ],

            [
                'name'   => 'product-create',
                'module' => 'product',
                'action' => 'create',
            ],

            [
                'name'   => 'product-edit',
                'module' => 'product',
                'action' => 'edit',
            ],

            [
                'name'   => 'product-delete',
                'module' => 'product',
                'action' => 'delete',
            ],

            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'category-view',
                'module' => 'category',
                'action' => 'view',
            ],

            [
                'name'   => 'category-create',
                'module' => 'category',
                'action' => 'create',
            ],

            [
                'name'   => 'category-edit',
                'module' => 'category',
                'action' => 'edit',
            ],

            [
                'name'   => 'category-delete',
                'module' => 'category',
                'action' => 'delete',
            ],

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'order-view',
                'module' => 'order',
                'action' => 'view',
            ],

            [
                'name'   => 'order-update',
                'module' => 'order',
                'action' => 'update',
            ],

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'user-view',
                'module' => 'user',
                'action' => 'view',
            ],

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'role-view',
                'module' => 'role',
                'action' => 'view',
            ],

            [
                'name'   => 'role-create',
                'module' => 'role',
                'action' => 'create',
            ],

            [
                'name'   => 'role-edit',
                'module' => 'role',
                'action' => 'edit',
            ],

            [
                'name'   => 'role-delete',
                'module' => 'role',
                'action' => 'delete',
            ],

            /*
            |--------------------------------------------------------------------------
            | Permission
            |--------------------------------------------------------------------------
            */

            [
                'name'   => 'permission-view',
                'module' => 'permission',
                'action' => 'view',
            ],

            [
                'name'   => 'permission-create',
                'module' => 'permission',
                'action' => 'create',
            ],

            [
                'name'   => 'permission-edit',
                'module' => 'permission',
                'action' => 'edit',
            ],

            [
                'name'   => 'permission-delete',
                'module' => 'permission',
                'action' => 'delete',
            ],

        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate(
                [
                    'name' => $permission['name'],
                ],
                $permission
            );
        }
    }
}
