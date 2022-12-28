<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

	    \App\Models\App_Env\App_Env_Model::create([
             'key' => 'App_Version_IOS',
             'data' => '0.0.11',
         ]);

	    \App\Models\App_Env\App_Env_Model::create([
		    'key' => 'App_Version_Android',
		    'data' => '0.0.11',
	    ]);
    }
}
