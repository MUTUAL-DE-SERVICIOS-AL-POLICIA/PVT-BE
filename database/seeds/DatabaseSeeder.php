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
        $this->call(AportesDocumentsSeeder::class);
        $this->call(AportesRequirementsSeeder::class);
        
        $this->command->info('Todo ok finalizado! DAVID y NADIA'); 
    }
}
