<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class OptimizationController extends Controller
{
    public function clearCache() // :GET
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        Artisan::call('key:generate');

        return "Cleared";
    }

    public function migrate() // :GET
    {
        Artisan::call('migrate');

        return "Migrated";
    }

    public function migrateFresh() // :GET
    {
        Artisan::call('migrate:fresh');

        return "Migrate Freshed";
    }
}
