<?php

namespace Database\Seeders\ACL;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        Admin::firstOrCreate(
            [
                'email' => 'superadmin@heavenshop.com',
            ],
            [
                'full_name' => 'Super Admin',
                'user_name' => 'superadmin',

                'password' => Hash::make('Sin2000s'),

                'phone'  => '0900000000',
                'gender' => 1,
                'status' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        */

        Admin::firstOrCreate(
            [
                'email' => 'manager@heavenshop.com',
            ],
            [
                'full_name' => 'Manager',
                'user_name' => 'manager',

                'password' => Hash::make('Sin2000s'),

                'phone'  => '0900000001',
                'gender' => 1,
                'status' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Staff
        |--------------------------------------------------------------------------
        */

        Admin::firstOrCreate(
            [
                'email' => 'staff@heavenshop.com',
            ],
            [
                'full_name' => 'Staff',
                'user_name' => 'staff',

                'password' => Hash::make('Sin2000s'),

                'phone'  => '0900000002',
                'gender' => 2,
                'status' => true,
            ]
        );
    }
}
