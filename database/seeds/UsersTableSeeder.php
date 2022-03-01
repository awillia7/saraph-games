<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Has;

class UsersTableSeeder extends seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run()
    {
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@example.com',
            'password'  => Hash::make('P@ssw0rd')
        ]);
    }
}