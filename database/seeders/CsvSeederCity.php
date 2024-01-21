<?php

namespace Database\Seeders;
use App\Models\City;
use Illuminate\Database\Seeder;

class CsvSeederCity extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeds/city.csv'), 'r');

        while ($row = fgetcsv($csv)) {
            City::create([
                'id' => $row[0],
                'province_id' => $row[1],
                'name' => $row[4]
            ]);
        }

        fclose($csv);
    }
}
