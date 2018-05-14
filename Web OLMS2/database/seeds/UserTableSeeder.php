<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Activity;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();
        $user = User::create([
                'name'          => 'Admin',
                'email'         => 'admin@olms2.com',
                'username'      => 'admin',
                'password'      => bcrypt('123456'),
        ]);
        $user->assignRole('admin');

        $user = User::create([
                'name'          => 'eiei',
                'email'         => 'eiei@olms2.com',
                'username'      => 'eiei',
                'password'      => bcrypt('123456'),
        ]);
        $user->assignRole('teacher');

        $faker = Faker\Factory::create();
        foreach(range(1, 99) as $index) {
            $user = User::create([
                'name'          => $faker->name,
                'email'         => $faker->email,
                'username'      => $faker->username,
                'password'      => bcrypt($faker->password),
            ]);
            $user->assignRole('student');
            $activity = Activity::create([
                'status'        => $faker->boolean,
                'formats'       => $faker->jobTitle,
                'description'   => $faker->paragraph,
                'user_id'       => $user->id,
                'subject_id'    => rand(1, 10)
            ]);
        }

        foreach(range(100, 110) as $index) {
            $user = User::create([
                'name'          => $faker->name,
                'email'         => $faker->email,
                'username'      => $faker->username,
                'password'      => bcrypt($faker->password),
            ]);
            $user->assignRole('teacher');
        }
    }
}
