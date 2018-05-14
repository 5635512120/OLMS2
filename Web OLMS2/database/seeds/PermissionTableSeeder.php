<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('permissions')->truncate();
        $permissions = [
            [
                'slug'          => 'view-backend',
                'name'          => 'View-Backend',
                'description'   => '',
            ],
            [
                'slug'          => 'new-activity',
                'name'          => 'New Activity',
                'description'   => '',
            ],
        ];
        DB::table('permissions')->insert($permissions);
    }
}
