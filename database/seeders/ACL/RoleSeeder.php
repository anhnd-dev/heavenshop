<?php

namespace Database\Seeders\ACL;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [

            [
                'name'        => 'Super Admin',
                'slug'        => 'super-admin',
                'description' => 'Full system access',
                'publish'     => true,
            ],

            [
                'name'        => 'Manager',
                'slug'        => 'manager',
                'description' => 'Shop manager',
                'publish'     => true,
            ],

            [
                'name'        => 'Staff',
                'slug'        => 'staff',
                'description' => 'Shop staff',
                'publish'     => true,
            ],

        ];

        foreach ($roles as $role) {

            Role::firstOrCreate(
                [
                    'slug' => $role['slug'],
                ],
                $role
            );
        }
    }
}
