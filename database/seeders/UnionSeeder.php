<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UnionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // JSON ফাইল থেকে কনটেন্ট পড়া
        $json = File::get('database/bangladesh-geo-local/unions.json');
        $data = json_decode($json);

        // type: "table" খুঁজে বের করা
        $tableData = collect($data)->firstWhere('type', 'table');

        // যদি ডেটা পাওয়া যায়, তাহলে ইনসার্ট করো
        if ($tableData && isset($tableData->data)) {
            foreach ($tableData->data as $data) {
                DB::table('unions')->insert([
                    'upazila_id' => $data->upazila_id,
                    'name' => $data->name,
                    'bn_name' => $data->bn_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
