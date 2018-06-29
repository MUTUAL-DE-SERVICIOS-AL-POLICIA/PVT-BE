<?php

use Illuminate\Database\Seeder;

use Muserpol\Models\TemplateType;

class TemplateTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            ['name' => 'Dictamen Legal', 'module_id' => 3],
            ['name' => 'Resolución', 'module_id' => 3],
        ];
        foreach ($values as $status) {
            TemplateType::create($status);
        }
    }
}
