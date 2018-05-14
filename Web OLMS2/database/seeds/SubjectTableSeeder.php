<?php

use Illuminate\Database\Seeder;
use App\Subject;
use App\User;

class SubjectTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach(range(101, 111) as $index) {
            $subject = Subject::create([
                'name'         => $faker->jobTitle,
                'code'         => $faker->numberBetween($min = 1000, $max = 9000),
                'owner_id'      => $index,
            ]);
        }

        foreach(range(2, 100) as $index) {
            $user = User::find($index);
            $user->subject()->sync([array_random(range(2,100))]);
        }
    }
}