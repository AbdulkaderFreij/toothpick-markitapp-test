<?php

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert(array(
            array(
              'title' => 'Migration and Seeder',
              'body' => 'Laravel',
              'user_id' => '1'
            ),
            array(
              'title' => 'Adding multiple data in seeder',
              'body' => 'Laravel',
              'user_id' => '1'
            ),
            array(
                'title' => 'Migration and Seeder',
                'body' => 'Laravel',
                'user_id' => '2'
              ),
              array(
                'title' => 'Adding multiple data in seeder',
                'body' => 'Laravel',
                'user_id' => '2'
              ),
          ));
    }
}
