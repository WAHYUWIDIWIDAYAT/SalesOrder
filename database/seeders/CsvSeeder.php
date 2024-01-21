<?php

namespace Database\Seeders;
use App\Models\Province;
use Illuminate\Database\Seeder;

class CsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeds/province.csv'), 'r');

        while ($row = fgetcsv($csv)) {
            Province::create([
                'id' => $row[0],
                'name' => $row[1],

            ]);
        }

        fclose($csv);
    }
}
