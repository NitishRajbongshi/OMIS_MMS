<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RoleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_details')->insert(
            [
                'rolename' => 'superadmin',
                'roletype' => 'SA',
                'inserted' => '1',
                'viewed' => '1',
                'updated' => '1',
                'deleted' => '1',
                'role_created_by' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'rolename' => 'admin',
                'roletype' => 'A',
                'inserted' => '1',
                'viewed' => '1',
                'updated' => '1',
                'deleted' => '1',
                'role_created_by' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        DB::table('user_role_details')->insert([
            'user_id' => '1',
            'role_id' => '1',
            'inserted_by' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
