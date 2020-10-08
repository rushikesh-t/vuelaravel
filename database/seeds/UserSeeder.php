<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'username' => 'admin',
            'name' => 'admin admin',
            'age' => 00,
            'gender' => 'Male',
            'email' => 'admin@domain.com',
            'password' => bcrypt('password'),
            'type' => 2,
        ]);
        
        DB::table('users')->insert([
            'username' => 'employee',
            'name' => 'employee employee',
            'age' => 00,
            'gender' => 'Male',
            'email' => 'employee@domain.com',
            'password' => bcrypt('password'),
            'type' => 1,
        ]);
    }
}
