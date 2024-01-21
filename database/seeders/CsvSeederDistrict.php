<?php

namespace Database\Seeders;
use App\Models\District;
use Illuminate\Database\Seeder;

class CsvSeederDistrict extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = fopen(base_path('database/seeds/subdistrict.csv'), 'r');

        while ($row = fgetcsv($csv)) {
            District::create([
                'id' => $row[0],
                'province_id' => $row[1],
                'city_id' => $row[3],
                'name' => $row[6]
            ]);
        }

        fclose($csv);
    }
}
