<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        // ⚡ Tắt FK để truncate nhanh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ⚡ Truncate theo thứ tự (con → cha)
        $this->truncateTables();

        // ⚡ Load data local
        $provinces = $this->loadJson('provinces.json');
        $districts = $this->loadJson('districts.json');
        $wards = $this->loadJson('wards.json');

        $now = now();

        // ======================
        // Provinces
        // ======================
        $provinceData = [];
        foreach ($provinces as $item) {
            $provinceData[] = [
                'name' => $item['name'],
                'code' => $item['code'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('provinces')->insert($provinceData);

        // Map code → id
        $provinceMap = DB::table('provinces')->pluck('id', 'code');

        // ======================
        // Districts
        // ======================
        $districtData = [];
        foreach ($districts as $item) {
            $districtData[] = [
                'name' => $item['name'],
                'code' => $item['code'],
                'province_id' => $provinceMap[$item['parent_code']] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('districts')->insert($districtData);

        // Map code → id
        $districtMap = DB::table('districts')->pluck('id', 'code');

        // ======================
        // Wards (chunk insert)
        // ======================
        $wardData = [];
        foreach ($wards as $item) {
            $wardData[] = [
                'name' => $item['name'],
                'code' => $item['code'],
                'district_id' => $districtMap[$item['parent_code']] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($wardData, 1000) as $chunk) {
            DB::table('wards')->insert($chunk);
        }

        // ⚡ Bật lại FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Truncate tables safely
     */
    private function truncateTables(): void
    {
        $tables = ['wards', 'districts', 'provinces'];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
    }

    /**
     * Load JSON file from database/data
     */
    private function loadJson(string $file): array
    {
        $path = database_path("data/{$file}");

        if (!file_exists($path)) {
            throw new \Exception("File not found: {$file}");
        }

        return json_decode(file_get_contents($path), true);
    }
}