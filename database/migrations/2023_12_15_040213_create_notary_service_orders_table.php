<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotaryServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notary_service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('main_category');
            $table->string('sub_category');
            $table->string('description_of_service');
            $table->string('doc_1');
            $table->string('doc_2');
            $table->string('doc_3');
            $table->integer('date_of_signing');
            $table->integer('start_date');
            $table->integer('end_date');
            $table->string('value');
            $table->string('monthly_rent');
            $table->string('advance_amount');
            $table->string('village_officer_number');
            $table->string('devisional_sec');
            $table->string('local_gov');
            $table->string('district');
            $table->string('land_reg_office');
            $table->string('notary_person_json');
            $table->string('payment_status');
            $table->string('order_status');
            $table->integer('create_time');
            $table->integer('modified_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notary_service_orders');
    }
}
