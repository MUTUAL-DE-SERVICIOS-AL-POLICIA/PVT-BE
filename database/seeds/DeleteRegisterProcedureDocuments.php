<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;

class DeleteRegisterProcedureDocuments extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedure_documents = ProcedureDocument::find(78);
        $procedure_documents->delete();

        $procedure_documents = ProcedureDocument::find(14);
        $procedure_documents->delete();
    }
}
