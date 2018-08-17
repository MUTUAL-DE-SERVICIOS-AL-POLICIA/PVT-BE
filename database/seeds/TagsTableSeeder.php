<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Tag;
use Muserpol\Models\Workflow\WorkflowState;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'pre-calificado', 'shortened'=>'precal', 'slug'=> 'pre-calificado' ],
            ['name' => 'calificado', 'shortened'=>'cal', 'slug'=> 'calificado'],
        ];
        foreach ($statuses as $status) {
            Tag::create($status);
        }
        WorkflowState::find(23)->tags()->attach([1,2]);
    }
}
