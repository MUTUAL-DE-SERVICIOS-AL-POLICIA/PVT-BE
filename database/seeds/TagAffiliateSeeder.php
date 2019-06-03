<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\Tag;
use Muserpol\Models\Module;
use Carbon\Carbon;

class TagAffiliateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $t = new Tag();
        $t->name = 'Documentos Preverificados I/2019';
        $t->shortened = 'Doc Prev';
        $t->slug = str_slug('Documentos Preverificados', '-');
        $t->save();
        $module = Module::find(2);
        $module->tags()->save($t, ['date' => Carbon::now(), 'user_id' => 1]);
        $t = new Tag();
        $t->name = 'Documentos Preverificados II/2019';
        $t->shortened = 'Doc Prev';
        $t->slug = str_slug('Documentos Preverificados', '-');
        $t->save();
        $module = Module::find(2);
        $module->tags()->save($t, ['date' => Carbon::now(), 'user_id' => 1]);
    }
}
