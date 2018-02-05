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
        // $this->call(UsersTableSeeder::class);
        $this->call(ProcedureTablesSeeder::class);
        $this->call(QuotaSeeder::class);
        $this->call(WorkFlowAndStates::class);
        $this->call(SequenceSeeder::class);
        $this->command->info('Todo ok finalizado!'); 
    }
}
