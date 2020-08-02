<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
                'user_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'title' => 'Adding multiple data in seeder',
                'body' => 'Laravel',
                'user_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'title' => 'Migration and Seeder',
                'body' => 'Laravel',
                'user_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'title' => 'Adding multiple data in seeder',
                'body' => 'Laravel',
                'user_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
        ));
    }
}
