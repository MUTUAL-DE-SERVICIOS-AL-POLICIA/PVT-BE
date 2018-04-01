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
        $this->call(ProcedureTablesSeeder::class);
        $this->call(KinshipSeeder::class);
        $this->call(OperationSeeder::class);
        $this->call(ActionSeed::class);
        // $this->call(SequenceSeeder::class);
        // $this->call(QuotaSeeder::class);
        
        $this->command->info('Todo ok finalizado! DAVID y NADIA'); 
    }
}
