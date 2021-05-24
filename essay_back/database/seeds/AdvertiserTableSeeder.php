<?php

use Illuminate\Database\Seeder;

class AdvertiserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<100 ; $i++)
    		{
       DB::table('advertisers')->insert([
            'name' => Str::random(10),
            'description' => Str::random(100),
            
        ]);
       }
    }
    
}
