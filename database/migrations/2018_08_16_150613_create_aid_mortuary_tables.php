<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAidMortuaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_aid_procedures', function (Blueprint $table) { // Cuantia
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('hierarchy_id')->unsigned(); //identificador jerarquia
            $table->enum('type_mortuary', ['Titular', 'Conyuge', 'Viuda'])->nullable(); //tipo fallecido
            $table->bigInteger('procedure_modality_id')->unsigned(); //identificador modalidad
            $table->decimal('amount', 13, 2); // monto
            $table->date('year'); // año
            $table->foreign('hierarchy_id')->references('id')->on('hierarchies')->onDelete('cascade');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('quota_aid_mortuaries', function (Blueprint $table) {// Trámites de Cuota y Auxilio Mortuorio
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('affiliate_id')->unsigned(); //identificador afiliado
            $table->bigInteger('quota_aid_procedure_id')->unsigned(); //identificador de cuantia
            $table->bigInteger('procedure_modality_id')->unsigned()->nullable(); //identificador de tipo de modalidad
            $table->bigInteger('city_start_id')->unsigned()->nullable(); //ciudad donde se inicia el trámite.
            $table->bigInteger('city_end_id')->unsigned()->nullable(); //ciudad donde se entrega el pago.
            $table->string('code')->unique(); //codigo
            $table->date('reception_date')->nullable(); //fecha de nacimento
            $table->decimal('subtotal', 13, 2); // sub total
            $table->decimal('total', 13, 2); // total
            $table->bigInteger('workflow_id')->unsigned(); // identificador de flujo
            $table->bigInteger('wf_state_current_id')->unsigned(); //identificador de flujo de estado
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('quota_aid_procedure_id')->references('id')->on('quota_aid_procedures');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->foreign('city_start_id')->references('id')->on('cities');
            $table->foreign('city_end_id')->references('id')->on('cities');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->foreign('wf_state_current_id')->references('id')->on('wf_states');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quota_aid_submitted_documents', function (Blueprint $table) {
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('quota_aid_mortuary_id')->unsigned();// identificador del trámite
            $table->bigInteger('procedure_requirement_id')->unsigned();// identificador de los requisitos
            $table->date('reception_date'); // fecha de recepcion
            $table->text('comment')->nullable(); // observacion
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries')->onDelete('cascade');
            $table->foreign('procedure_requirement_id')->references('id')->on('procedure_requirements');
            $table->unique(['quota_aid_mortuary_id', 'procedure_requirement_id']);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quota_aid_observations', function (Blueprint $table) {   //observaciones de Quota y Auxilio
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('quota_aid_mortuary_id')->unsigned();
            $table->bigInteger('observation_type_id')->unsigned();
            $table->date('date');
            $table->longText('message');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries')->onDelete('cascade');
            $table->foreign('observation_type_id')->references('id')->on('observation_types');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quota_aid_beneficiaries', function (Blueprint $table) {
            $table->bigIncrements('id'); // identifiador
            $table->bigInteger('quota_aid_mortuary_id')->unsigned(); //identificador de cuota y auxilio mortuoria
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable(); //identificación del ci
            $table->bigInteger('kinship_id')->unsigned()->nullable();
            $table->enum('type', ['S', 'N']); // tipo de beneficiario (Solicitante o Normal)
            $table->string('identity_card'); //ci
            $table->string('last_name')->nullable(); //apellido paterno
            $table->string('mothers_last_name')->nullable(); // apellido materno
            $table->string('first_name')->nullable(); // primer nombre
            $table->string('second_name')->nullable(); // segundo nombre
            $table->string('surname_husband')->nullable(); //apellido casada
            $table->date('birth_date')->nullable(); //fecha de nacimento
            $table->enum('gender', ['M', 'F']); // genero
            $table->enum('civil_status', ['C', 'S', 'V', 'D'])->nullable(); //estado civil
            $table->decimal('percentage')->nullable(); //Porcentaje de monto
            $table->decimal('paid_amount')->nullable(); //monto a pagar
            $table->string('phone_number')->nullable(); // nomero de telefono
            $table->string('cell_phone_number')->nullable(); // numero de celular
            $table->foreign('quota_aid_mortuary_id')->references('id')->on('quota_aid_mortuaries')->onDelete('cascade'); // identificador de fondo de retiro
            $table->foreign('city_identity_card_id')->references('id')->on('cities'); //identificación del ci
            $table->foreign('kinship_id')->references('id')->on('kinships');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('address_quota_aid_beneficiary', function (Blueprint $table) {//direccion de los beneficiarios
            $table->bigIncrements('id');
            $table->bigInteger('quota_aid_beneficiary_id')->unsigned();
            $table->bigInteger('address_id')->unsigned();
            $table->foreign('quota_aid_beneficiary_id')->references('id')->on('quota_aid_beneficiaries');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->timestamps();
        });


        Schema::create('quota_aid_advisors', function (Blueprint $table) { // tabla tutor
            $table->bigIncrements('id');
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable(); //identificación del ci
            $table->bigInteger('kinship_id')->unsigned()->nullable();
            $table->string('identity_card'); //ci
            $table->string('last_name')->nullable(); //apellido paterno
            $table->string('mothers_last_name')->nullable(); // apellido materno
            $table->string('first_name')->nullable(); // primer nombre
            $table->string('second_name')->nullable(); // segundo nombre
            $table->string('surname_husband')->nullable(); //apellido casada
            $table->date('birth_date')->nullable(); //fecha de nacimento
            $table->enum('gender', ['M', 'F']); // genero
            $table->enum('type', ['Natural', 'Legal']);
            //datos de tutor legal
            $table->string('name_court')->nullable(); //legal
            $table->string('resolution_number')->nullable(); //legal
            $table->date('resolution_date')->nullable(); //legal
            // fin datos de tutor legal
            $table->string('phone_number')->nullable(); //numero de telefono
            $table->string('cell_phone_number')->nullable(); //numero de cel
            $table->foreign('city_identity_card_id')->references('id')->on('cities');
            $table->foreign('kinship_id')->references('id')->on('kinships');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quota_aid_advisor_beneficiary', function (Blueprint $table) {//tabla tutor beneficiario
            $table->bigIncrements('id');
            $table->bigInteger('quota_aid_beneficiary_id')->unsigned();
            $table->bigInteger('quota_aid_advisor_id')->unsigned();
            $table->foreign('quota_aid_beneficiary_id')->references('id')->on('quota_aid_beneficiaries');
            $table->foreign('quota_aid_advisor_id')->references('id')->on('quota_aid_advisors');
        });

        Schema::create('quota_aid_legal_guardians', function (Blueprint $table) { // apoderado
            $table->bigIncrements('id');
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable(); // Ciudad de expedicion
            $table->string('identity_card')->nullable(); // numero de CI
            $table->string('last_name')->nullable(); //apellido
            $table->string('mothers_last_name')->nullable(); //apellido materno
            $table->string('first_name')->nullable(); //nombre
            $table->string('second_name')->nullable(); //segundo nombre
            $table->string('surname_husband')->nullable(); //apellido de casada
            $table->string('phone_number')->nullable();// numero de telefono fijo
            $table->string('cell_phone_number')->nullable(); //numero de celuluar
            $table->string('number_authority')->nullable(); //numero de poder
            $table->string('notary_of_ public_faith')->nullable(); //notaria de fe publica Nro...
            $table->string('notary')->nullable(); //notario
            $table->foreign('city_identity_card_id')->references('id')->on('cities');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quota_aid_beneficiary_legal_guardian', function (Blueprint $table) { //Beneficiario-apoderado
            $table->bigIncrements('id');
            $table->bigInteger('quota_aid_beneficiary_id')->unsigned(); //identificador del beneficiario
            $table->bigInteger('quota_aid_legal_guardian_id')->unsigned(); //identificador del tutor
            $table->foreign('quota_aid_beneficiary_id')->references('id')->on('quota_aid_beneficiaries');
            $table->foreign('quota_aid_legal_guardian_id')->references('id')->on('quota_aid_legal_guardians');
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('quota_aid_beneficiary_legal_guardian');
        Schema::drop('quota_aid_legal_guardians');
        Schema::drop('quota_aid_advisor_beneficiary');
        Schema::drop('quota_aid_advisors');
        Schema::drop('address_quota_aid_beneficiary');
        Schema::drop('quota_aid_observations');
        Schema::drop('quota_aid_beneficiaries');
        Schema::drop('quota_aid_submitted_documents');
        Schema::drop('quota_aid_mortuaries');
        Schema::drop('quota_aid_procedures');
    }
}
