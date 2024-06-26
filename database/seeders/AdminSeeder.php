<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Mr. Mritunjay",
            "email" => "admin@aaftonline.com",
            "password" => bcrypt('secretaaft'),
        ]);
        User::create([
            "name" => "Mr. Archit",
            "email" => "archit@aaftonline.com",
            "password" => bcrypt('secretaaft'),
        ]);
        User::create([
            "name" => "Mr. Uday",
            "email" => "uday@aaftonline.com",
            "password" => bcrypt('secretaaft'),
        ]);User::create([
            "name" => "Mr. Akshay",
            "email" => "akshay@aaftonline.com",
            "password" => bcrypt('secretaaft'),
        ]);
    }
}
