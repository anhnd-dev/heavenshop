<?php

namespace Database\Seeders;

use Database\Seeders\ACL\AdminRoleSeeder;
use Database\Seeders\ACL\AdminSeeder;
use Database\Seeders\ACL\PermissionSeeder;
use Database\Seeders\ACL\RolePermissionSeeder;
use Database\Seeders\ACL\RoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            /*
            |--------------------------------------------------------------------------
            | ADDRESS (province, district, ward)
            |--------------------------------------------------------------------------
            */

            AddressSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | ACL
            |--------------------------------------------------------------------------
            */

            // Roles
            RoleSeeder::class,

            // Permissions
            PermissionSeeder::class,

            // Role ↔ Permission
            RolePermissionSeeder::class,

            // Admins
            AdminSeeder::class,

            // Admin ↔ Role
            AdminRoleSeeder::class,

        ]);
    }
}
