<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User :: create([
            'name' => 'SuperVisors',
            'email' => 'Admin@gmail.com',
            'password' => bcrypt('12345678'),
            'is_admin' => 1
        ]);
    }
}
