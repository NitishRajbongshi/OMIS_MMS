<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        DB::table('menu_details')->insert([
            [
                'menu_name' => 'Manage Department',
                'url' => 'add-department',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'menu_name' => 'Manage Users',
                'url' => 'manage-user',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'menu_name' => 'Manage Office',
                'url' => 'manage-office',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'menu_name' => 'Manage Designations',
                'url' => 'manage-designation',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'menu_name' => 'Manage Posts',
                'url' => 'manage-post',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'menu_name' => 'Manage Roles',
                'url' => 'add-role',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'menu_name' => 'Manage Roads',
                'url' => 'manage-road',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'menu_name' => 'Modify Request',
                'url' => 'modify-road',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
