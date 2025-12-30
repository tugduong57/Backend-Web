<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ThesesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('theses')->insert([
                'title' => $faker->sentence(6),
                'student_id' => $faker->numberBetween(1, 10),
                'program' => $faker->randomElement(['Information Systems', 'Software Engineering', 'Data Science']),
                'supervisor' => $faker->name,
                'abstract' => $faker->paragraph(5),
                'submission_date' => $faker->date(),
                'defense_date' => $faker->date(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
