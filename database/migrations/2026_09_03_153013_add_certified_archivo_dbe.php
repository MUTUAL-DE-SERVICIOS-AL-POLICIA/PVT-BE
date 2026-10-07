<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifiedArchivoDbe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        insert into roles (module_id, display_name, action, created_at, updated_at, name)
        values (2, 'Área de Archivo DBE', 'Aprobado', NOW(), NOW(), 'CE-area-de-archivo-dbe');
        ");

        DB::statement("
        insert into wf_states (module_id, role_id, name, first_shortened, sequence_number)
        values (2, (select id from roles where display_name = 'Área de Archivo DBE'), 'Área de Archivo DBE Complemento Económico', 'Archivo', 13);
        ");

        DB::statement("
        INSERT INTO public.wf_sequences
        (workflow_id, wf_state_current_id, wf_state_next_id, action, created_at, updated_at)
        VALUES(1, 8, (select id from wf_states where name = 'Área de Archivo DBE Complemento Económico'), 'Aprobar', now(),now());");

        ///acceso de jefatura a archivo ce

        DB::statement("
        INSERT INTO public.wf_sequences
        (workflow_id, wf_state_current_id, wf_state_next_id, action, created_at, updated_at)
        VALUES(1, 4, (select id from wf_states where name = 'Área de Archivo Complemento Económico'), 'Aprobar', now(),now());");

        //
        Schema::table('eco_com_submitted_documents', function (Blueprint $table) {
            $table->boolean('is_archive_review')
                ->default(false)
                ->after('is_uploaded');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('eco_com_submitted_documents', function (Blueprint $table) {
            $table->dropColumn('is_archive_review');
        });
    }
}
