<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetirementFundTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ret_fun_modality_types', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('ret_fun_modalities', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ret_fun_modality_type_id')->unsigned()->nullable();
            $table->foreign('ret_fun_modality_type_id')->references('id')->on('ret_fun_modality_types');
        });
        // Schema::create('ret_fund_modality_types', function(Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('name');
        //     $table->timestamps();
        // });
        // Schema::create('ret_fund_modalities', function(Blueprint $table) {
        //     $table->bigInteger('ret_fun_modality_type_id')->unsigned()->nullable();
        //     $table->foreign('ret_fun_modality_type_id')->references('id')->on('ret_fun_modality_types');
        // });


        Schema::create('retirement_funds', function (Blueprint $table) {
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('affiliate_id')->unsigned(); //identificador afiliado
            $table->bigInteger('ret_fun_modality_id')->unsigned()->nullable(); //identificador de tipo de modalidad
            $table->bigInteger('city_id')->unsigned()->nullable(); //identificador de ciudad
            $table->string('code')->unique(); //codigo

            $table->string('resolution_code')->nullable(); //codigo de resolucion
            $table->date('resolution_date')->nullable(); // fecha de resolucion
            $table->string('legal_assessment_code')->nullable(); //codigo de evaluación legal
            $table->date('legal_assessment_date')->nullable();// fecha de evaluación legal

            $table->enum('type', ['Pago', 'Anticipo']); //tipo

            $table->string('accounting_code')->nullable(); //código de cuenta
            $table->date('accounting_response_date')->nullable(); // fecha de respuesta de cuenta
            $table->string('loan_code')->nullable(); // codigo de prestamo
            $table->date('loan_response_date')->nullable(); // fecha de prestamo de respuesta

            $table->date('reception_date')->nullable(); //fecha de recepcion
            $table->date('qualification_date')->nullable(); //fecha de calificacion
            $table->date('check_file_date')->nullable(); // fecha archivo

            $table->decimal('average_quotable', 13, 2); //promedio cotizable
            $table->integer('quotations');  //cotizaciones
            /**/
            $table->decimal('total_quotable', 13, 2); //total cotizaciones

            $table->decimal('total_additional_quotable', 13, 2); //total de cotizacion adicional
            $table->decimal('subtotal', 13, 2); // sud total
            $table->decimal('performance', 13, 2); // rendimiento
            $table->decimal('total', 13, 2); // total
            $table->string('comment'); //
            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade'); //identificador de afiliado
            $table->foreign('ret_fun_modality_id')->references('id')->on('ret_fun_modalities'); // identificador de tipo de modalidad
            $table->foreign('city_id')->references('id')->on('cities'); // identificador de cuidad
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_requirement_types', function (Blueprint $table) {
            $table->bigIncrements('id'); // identificador
            $table->string('name'); // nombre
            $table->timestamps();
        });
        Schema::create('ret_fun_requirements', function (Blueprint $table) {
            $table->bigIncrements('id'); // identificador
            $table->bigInteger('ret_fun_modality_id')->unsigned(); //identificador de tipo de modalidad
            $table->bigInteger('ret_fun_requirement_type_id')->unsigned()->nullable(); //tipo de requerimiento renta
            $table->string('name'); //nombre
            $table->string('shortened'); // abreviado
            $table->foreign('ret_fun_modality_id')->references('id')->on('ret_fun_modalities'); //
            $table->foreign('ret_fun_requirement_type_id')->references('id')->on('ret_fun_requirement_types'); // identificador de tipo de requisito
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_submitted_documents', function (Blueprint $table) {
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('retirement_fund_id')->unsigned(); // identificador de fondo de retiro
            $table->bigInteger('ret_fun_requirement_id')->unsigned();
            $table->date('reception_date'); // fecha de recepcion
            $table->boolean('status')->default(0); //estado
            $table->string('comment')->nullable(); // observacion
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade'); // identificador de fondo de jubilacion
            $table->foreign('ret_fun_requirement_id')->references('id')->on('ret_fun_requirements'); //Ret. requisito de diversión id
            $table->unique(['retirement_fund_id', 'ret_fun_requirement_id']); // identificador de fondo de retiro
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_antecedent_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); //nombre
            $table->string('shortened'); //abrebiacion
            $table->timestamps();
        });

        Schema::create('ret_fun_antecedents', function (Blueprint $table) {
            $table->bigIncrements('id'); //id
            $table->bigInteger('retirement_fund_id')->unsigned(); // identificador de fondo de retiro
            $table->bigInteger('ret_fun_antecedent_file_id')->unsigned(); // identificador del archivo de antecedente
            $table->boolean('status')->default(0); //estado
            $table->date('reception_date')->nullable(); //fecha de recepcion
            $table->string('code')->nullable(); // codigo
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade'); //identificador de fondo de jubilación
            $table->foreign('ret_fun_antecedent_file_id')->references('id')->on('ret_fun_antecedent_files'); //identificador del archivo de antecedente
            $table->unique(['retirement_fund_id', 'ret_fun_antecedent_file_id']); // identificador del archivo de antecedente
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ret_fun_applicants', function (Blueprint $table) {
            $table->bigIncrements('id'); // identifiador
            $table->bigInteger('retirement_fund_id')->unsigned(); //identificador de fondo de retiro
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable(); //identificación del ci
            $table->string('identity_card'); //ci
            $table->string('last_name')->nullable(); //apellido paterno
            $table->string('mothers_last_name')->nullable(); // apellido materno
            $table->string('first_name')->nullable(); // primer nombre
            $table->string('second_name')->nullable(); // segundo nombre
            $table->string('surname_husband')->nullable(); //apellido casada
            $table->string('kinship')->nullable(); // parentesco
            $table->date('birth_date')->nullable(); //fecha de nacimento
            $table->enum('gender', ['M', 'F']); // genero
            $table->enum('civil_status', ['C', 'S', 'V', 'D'])->nullable(); //estado civil
            $table->string('phone_number')->nullable(); // nomero de telefono
            $table->string('cell_phone_number')->nullable(); // numero de celular
            $table->string('home_address')->nullable(); // direccion
            $table->string('work_address')->nullable(); // direccion trabajo
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds')->onDelete('cascade'); // identificador de fondo de retiro
            $table->foreign('city_identity_card_id')->references('id')->on('cities'); //identificación del ci
            $table->timestamps();
            $table->softDeletes();
        });
      
        Schema::create('ret_fun_legal_guardians', function (Blueprint $table) {
            $table->bigIncrements('id'); // identificador
            $table->bigInteger('retirement_fund_id')->unsigned(); //identificador fondo de retiro
            $table->bigInteger('city_identity_card_id')->unsigned()->nullable(); //identificación del ci
            $table->string('identity_card')->nullable(); //ci
            $table->string('last_name')->nullable(); // apellido paterno
            $table->string('mothers_last_name')->nullable(); // apellido materno
            $table->string('first_name')->nullable(); // primer nombre
            $table->string('second_name')->nullable(); //segundo nombre
            $table->string('surname_husband')->nullable(); //apellido casada
            $table->string('phone_number')->nullable(); //numero de telefono
            $table->string('cell_phone_number')->nullable(); //numero de cel
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds'); // identificador de fondo de retiro
            $table->foreign('city_identity_card_id')->references('id')->on('cities'); //identificación del ci
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('address', function(Blueprint $table) {
            $table->bigIncrements('id'); //identificador
            $table->bigInteger('affiliate_id')->unsigned(); //identificador de afiliado
            $table->bigInteger('city_address_id')->unsigned()->nullable(); // identificador de la dirección y ciudad
            $table->string('zone')->nullable(); // zona
            $table->string('street')->nullable(); // calle
            $table->string('number_address')->nullable(); //numero de domicilio
            $table->foreign('affiliate_id')->references('id')->on('affiliates'); //identificador de afiliado
            $table->foreign('city_address_id')->references('id')->on('cities'); //identificación del ci
        });
      
        Schema::create('interval_type', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
      
        Schema::create('interval_type_ret_fun', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->bigInteger('interval_type_id')->unsigned();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->foreign('interval_type_id')->references('id')->on('interval_type');
            $table->timestamps();
            $table->softDeletes();
        });
      
        Schema::create('ret_fun_advanced_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('retirement_fund_id')->unsigned();
            $table->date('date')->nullable();
            $table->decimal('total', 13, 2)->nullable();
            $table->foreign('retirement_fund_id')->references('id')->on('retirement_funds');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ret_fun_advanced_payment');
        Schema::drop('interval_type_ret_fun');
        Schema::drop('interval_type');
        Schema::drop('address');
        Schema::drop('ret_fun_legal_guardians');
        Schema::drop('ret_fun_applicants');
        Schema::drop('ret_fun_antecedents');
        Schema::drop('ret_fun_antecedent_files');
        Schema::drop('ret_fun_submitted_documents');
        Schema::drop('ret_fun_requirements');
        Schema::drop('ret_fun_requirement_types');
        Schema::drop('retirement_funds');
        Schema::drop('ret_fun_modalities');
        Schema::drop('ret_fun_modality_types');
    }
}
