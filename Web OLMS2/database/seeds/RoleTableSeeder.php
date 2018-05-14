<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->truncate();
        $roles = [
            [
                'slug'          => 'student',
                'name'          => 'Student',
                'description'   => '',
            ],
            [
                'slug'          => 'teacher',
                'name'          => 'Teacher',
                'description'   => '',
            ],
            [
                'slug'          => 'admin',
                'name'          => 'Administrator',
                'description'   => '',
            ],
        ];
        DB::table('roles')->insert($roles);
    }
}
