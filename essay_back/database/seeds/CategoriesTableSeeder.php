<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $categories = [ 'Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5' ];
    foreach ( $categories as $category ) {
      DB::table( 'categories' )->insert( [
        'name'       => trim( strtolower( $category ) ),
        'is_active'     => 1,
        
      ] );    
}
}
}