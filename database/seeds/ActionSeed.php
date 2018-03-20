<?php

use Illuminate\Database\Seeder;

class ActionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('actions')->insert([
            ['name' => 'create', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['name' => 'read', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['name' => 'update', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['name' => 'delete', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
            ['name' => 'print', 'created_at' => '2018/03/08', 'updated_at' =>'2018/03/08'],
         
        ]);
    }
}
