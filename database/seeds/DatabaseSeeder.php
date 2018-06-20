<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('node')->insert([
            [
                'name' => 'Jean-Luc Picard',
                'level' => 0,
                'lft' => 1,
                'rgt' => 10,
            ],
            [
                'name' => 'Deanna Troi',
                'level' => 1,
                'lft' => 2,
                'rgt' => 3,
            ],
            [
                'name' => 'William Riker',
                'level' => 1,
                'lft' => 4,
                'rgt' => 9,
            ],
            [
                'name' => 'Data',
                'level' => 2,
                'lft' => 5,
                'rgt' => 6,
            ],
            [
                'name' => 'Geordi La Forge',
                'level' => 2,
                'lft' => 7,
                'rgt' => 8,
            ],
        ]);
    }
}
