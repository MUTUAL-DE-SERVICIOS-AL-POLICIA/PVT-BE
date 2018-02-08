<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetirementFundTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {    

        //Fondo de retiro
        Schema::create('procedure_types', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('module_id')->unsigned()->nullable();
            $table->string('name');
           $table->foreign('module_id')->references('id')->on('modules');
            $table->timestamps();
        });

        Schema::create('procedure_modalities', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('procedure_type_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('shortened')->nullable();
            $table->boolean('is_valid')->default(true); //esta vigente la modalidad hdp 
            $table->foreign('procedure_type_id')->references('id')->on('procedure_types');
        });

        Schema::create('procedure_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('procedure_requirements', function (Blueprint $table) { ///requisito entregado 
            $table->bigIncrements('id'); // identificador
            $table->bigInteger('procedure_modality_id')->unsigned(); //identificador de tipo de modalidad
            $table->bigInteger('procedure_document_id')->unsigned()->nullable(); //tipo de requerimiento renta
            $table->integer('number');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities'); //
            $table->foreign('procedure_document_id')->references('id')->on('procedure_documents'); //
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_procedures', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('annual_yield', 13, 2); //rendimiento anual porcentaje
            $table->decimal('administrative_expenses', 13, 2); //descuento gastos administrativos
            $table->integer('contributions_number'); //numero de contribuciones
            $table->boolean('is_enabled')->default(true); //estado activo 
            $table->timestamps();
        });

        Schema::create('retirement_funds', function (Blueprint $table) {
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('affiliate_id')->unsigned(); //identificador afiliado
            $table->bigInteger('procedure_modality_id')->unsigned()->nullable(); //identificador de tipo de modalidad
            $table->bigInteger('ret_fun_procedure_id')->unsigned()->nullable(); //identificador de tipo de modalidad
            $table->bigInteger('city_start_id')->unsigned()->nullable(); //ciudad donde se inicia el tramite.
            $table->bigInteger('city_end_id')->unsigned()->nullable(); //ciudad donde se entrega el pago.
            $table->bigInteger('workflow_id')->unsigned(); // identificador de flujo
            $table->bigInteger('wf_state_current_id')->unsigned(); //identificador de flujo de estado         
            $table->string('code')->unique(); //codigo
            $table->date('reception_date')->nullable(); //fecha de recepcion
            $table->enum('type', ['Pago', 'Anticipo'])->default('Pago'); //tipo
            $table->decimal('subtotal', 13, 2); // sub total
            $table->decimal('total', 13, 2); // total
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->foreign('ret_fun_procedure_id')->references('id')->on('ret_fun_procedures');
            $table->foreign('city_start_id')->references('id')->on('cities');
            $table->foreign('city_end_id')->references('id')->on('cities');
            $table->foreign('workflow_id')->references('id')->on('workflows');
            $table->foreign('wf_state_current_id')->references('id')->on('wf_states');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_submitted_documents', function (Blueprint $table) {
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('retirement_fund_id')->unsigned(); // identificador de fondo de retiro
            $table->bigInteger('procedure_requirement_id')->unsigned();
            $table->date('reception_date'); // fecha de recepcion
            $table->text('comment')->nullable(); // observacion
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade'); // identificador de fondo de jubilacion
            $table->foreign('procedure_requirement_id')->references('id')->on('procedure_requirements'); //Ret. requisito de diversión id
            $table->unique(['retirement_fund_id', 'procedure_requirement_id']); // identificador de fondo de retiro
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kinships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
      
        Schema::create('ret_fun_beneficiaries', function (Blueprint $table) {
            $table->bigIncrements('id'); // identifiador
            $table->bigInteger('retirement_fund_id')->unsigned(); //identificador de fondo de retiro
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable(); //identificación del ci
            $table->bigInteger('kinship_id')->unsigned()->nullable();
            $table->string('identity_card')->nullable(); //ci
            $table->string('last_name')->nullable(); //apellido paterno
            $table->string('mothers_last_name')->nullable(); // apellido materno
            $table->string('first_name')->nullable(); // primer nombre
            $table->string('second_name')->nullable(); // segundo nombre
            $table->string('surname_husband')->nullable(); //apellido casada
            $table->date('birth_date')->nullable(); //fecha de nacimento
            $table->enum('gender', ['M', 'F']); // genero
            $table->enum('type', ['S', 'N']); // tipo de beneficiario (Solicitante o Normal)
            $table->enum('civil_status', ['C', 'S', 'V', 'D'])->nullable(); //estado civil
            $table->string('phone_number')->nullable(); // nomero de telefono
            $table->string('cell_phone_number')->nullable(); // numero de celular
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade'); // identificador de fondo de retiro
            $table->foreign('city_identity_card_id')->references('id')->on('cities'); //identificación del ci
            $table->foreign('kinship_id')->references('id')->on('kinships');
            $table->timestamps();
            $table->softDeletes();
        });
      
      	Schema::create('addresses', function(Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('city_address_id')->unsigned()->nullable(); // identificador de la dirección y ciudad
            $table->string('zone')->nullable(); // zona
            $table->string('street')->nullable(); // calle
            $table->string('number_address')->nullable(); //numero de domicilio
            $table->foreign('city_address_id')->references('id')->on('cities'); //identificación del ci
          	$table->timestamps();
        });
      
        Schema::create('address_ret_fun_beneficiary', function(Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('ret_fun_beneficiary_id')->unsigned();
            $table->bigInteger('address_id')->unsigned();
            $table->foreign('ret_fun_beneficiary_id')->references('id')->on('ret_fun_beneficiaries'); 
            $table->foreign('address_id')->references('id')->on('addresses'); 
          	$table->timestamps();
        });

        Schema::create('ret_fun_advisors', function (Blueprint $table) { // tabla tutor
            $table->bigIncrements('id');
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable(); //identificación del ci
            $table->bigInteger('kinship_id')->unsigned()->nullable();
            $table->string('identity_card'); //ci
            $table->string('last_name')->nullable(); //apellido paterno
            $table->string('mothers_last_name')->nullable(); // apellido materno
            $table->string('first_name')->nullable(); // primer nombre
            $table->string('second_name')->nullable(); // segundo nombre
            $table->string('surname_husband')->nullable(); //apellido ca  sada
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

        Schema::create('ret_fun_advisor_beneficiary', function (Blueprint $table) { //Tabla tutor-beneficiario
            $table->bigIncrements('id');
            $table->bigInteger('ret_fun_beneficiary_id')->unsigned();
            $table->bigInteger('ret_fun_advisor_id')->unsigned();
            $table->foreign('ret_fun_beneficiary_id')->references('id')->on('ret_fun_beneficiaries');
            $table->foreign('ret_fun_advisor_id')->references('id')->on('ret_fun_advisors');
            $table->timestamps();
        });
      
      	Schema::create('ret_fun_legal_guardians', function (Blueprint $table) { // apoderado
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned()->index(); //
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
            $table->string('notary_of_public_faith')->nullable(); //notaria de fe publica Nro....CECHUS ANITA
            $table->string('notary')->nullable(); //notario
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade');
            $table->foreign('city_identity_card_id')->references('id')->on('cities');
            $table->timestamps();
            $table->softDeletes();
        });
      
      	Schema::create('ret_fun_beneficiary_legal_guardian', function (Blueprint $table) { //Beneficiario-apoderado
            $table->bigIncrements('id');
            $table->bigInteger('ret_fun_beneficiary_id')->unsigned(); //identificador del beneficiario
            $table->bigInteger('ret_fun_legal_guardian_id')->unsigned(); //identificador del tutor
            $table->foreign('ret_fun_beneficiary_id')->references('id')->on('ret_fun_beneficiaries');
            $table->foreign('ret_fun_legal_guardian_id')->references('id')->on('ret_fun_legal_guardians');
            $table->timestamps();
        });
        
        Schema::create('procedure_interval_types', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('procedure_modality_id')->unsigned(); //identificador de tipo de modalidad
            $table->string('name')->nullable();
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_intervals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('procedure_interval_type_id')->unsigned();
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreign('procedure_interval_type_id')->references('id')->on('procedure_interval_types');
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->timestamps();
            $table->softDeletes();
        });

        //documentos de afiliados afiliados
        Schema::create('affiliate_folders', function (Blueprint $table) {  //Folder o carpeta de afiliado
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_id')->unsigned();
            $table->bigInteger('procedure_modality_id')->unsigned();
            $table->string('code_file')->nullable();
            $table->integer('folder_number')->nullable();
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->foreign('procedure_modality_id')->references('id')->on('procedure_modalities');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('scanned_documents', function (Blueprint $table) { //Escaneo de documentos de afiliado
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_folder_id')->unsigned();
            $table->bigInteger('procedure_document_id')->unsigned();
            $table->string('name');
            $table->text('url_file');
            $table->date('due_date')->nullable(); //fecha de vencimiento
            $table->text('comment')->nullable();
            $table->foreign('affiliate_folder_id')->references('id')->on('affiliate_folders');
            $table->foreign('procedure_document_id')->references('id')->on('procedure_documents'); //
            $table->timestamps();
            $table->softDeletes();
        });
        //Disponibilidad
        Schema::create('contribution_types', function (Blueprint $table) { //Tipos de Aportes
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('shortened');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('contributions', function (Blueprint $table) { //Escaneo de documentos de afiliado
            $table->bigInteger('contribution_type_id')->unsigned()->nullable();
            $table->foreign('contribution_type_id')->references('id')->on('contribution_types');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('contribution_type_id');
        });
        Schema::drop('contribution_types');
        Schema::drop('scanned_documents');
        Schema::drop('affiliate_folders');     
        Schema::drop('interval_type_ranges');
        Schema::drop('interval_types');
        Schema::drop('ret_fun_beneficiary_legal_guardian');
        Schema::drop('ret_fun_legal_guardians');
        Schema::drop('ret_fun_advisor_beneficiary');       
        Schema::drop('ret_fun_advisors');
        //Schema::drop('address_ret_fun_beneficiary');
        Schema::drop('ret_fun_beneficiaries');
        Schema::drop('ret_fun_submitted_documents');
        Schema::drop('kinships');
        Schema::drop('retirement_funds');
        Schema::drop('ret_fun_procedures');
        Schema::drop('procedure_requirements');
        Schema::drop('procedure_documents');
        Schema::drop('procedure_modalities');
        Schema::drop('procedure_types'); 
      //  Schema::drop('ret_fun_address_applicants');
        
        
        

        
        
    }

}
