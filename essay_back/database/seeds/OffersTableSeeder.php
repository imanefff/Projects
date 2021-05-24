<?php

use Illuminate\Database\Seeder;

class OffersTableSeeder extends Seeder
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
       DB::table('offers')->insert([
            'sid' => Str::random(6),
            'name' => Str::random(10),
            'offer_url' => 'http://www.'. Str::random(7).'.com',
            'landing_page_url' => 'http://www.'. Str::random(7).'.com/'.Str::random(20),
            'description' => Str::random(50),
            'start_date' => Carbon\Carbon::now(),
            'end_date' => Carbon\Carbon::now(),
            'amount' => rand(5,20),
            'is_mobile' => 0,
            'is_active' => 1,

            

            
        ]);
       
       } 
          }
}
