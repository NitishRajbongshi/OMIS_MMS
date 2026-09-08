<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserMenuDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_menu_details')->insert([
            [
                'userid' => '1',
                'menuid' => '1',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'userid' => '1',
                'menuid' => '2',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'userid' => '1',
                'menuid' => '3',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'userid' => '1',
                'menuid' => '4',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'userid' => '1',
                'menuid' => '5',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'userid' => '1',
                'menuid' => '6',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'userid' => '1',
                'menuid' => '7',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'userid' => '1',
                'menuid' => '8',
                'active' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
