<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//create admin user
    	$user = new Author();
    	$user->name = 'admin';
    	$user->username = 'admin';
    	$user->password = Hash::make('admin');
    	$user->admin = true;

    	$user->save();
    }
}
