<?php

namespace Database\Seeders\ACL;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AdminRoleSeeder extends Seeder
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
        | Get Admins
        |--------------------------------------------------------------------------
        */

        $superAdmin = Admin::where('email', 'superadmin@heavenshop.com')->first();

        $manager = Admin::where('email', 'manager@heavenshop.com')->first();

        $staff = Admin::where('email', 'staff@heavenshop.com')->first();

        /*
        |--------------------------------------------------------------------------
        | Assign Roles
        |--------------------------------------------------------------------------
        */

        if ($superAdmin && $superAdminRole) {

            $superAdmin->roles()->syncWithoutDetaching([
                $superAdminRole->id,
            ]);
        }

        if ($manager && $managerRole) {

            $manager->roles()->syncWithoutDetaching([
                $managerRole->id,
            ]);
        }

        if ($staff && $staffRole) {

            $staff->roles()->syncWithoutDetaching([
                $staffRole->id,
            ]);
        }
    }
}
