<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'phoneno' => 'NULL',
            'address1' => 'NULL',
            'address2' => 'NULL',
            'district' => 'NULL',
            'pin' => 'NULL',
            'state' => 'NULL',
            'country' => 'NULL',
            'gender' => 'NULL',
            'user_role_id' => '1',
            'department' => 'Admin/Others',
            'office' => 'NULL',
            'designation' => 'NULL',
            'post' => 'NULL',
            'activity_status' => 'A',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
