<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotaryOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notary_order_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoiceNo');
            $table->string("extSearch");
            $table->string("secondCopyCheck");
            $table->string("obtainExt");
            $table->string("obtainSecondCpyTaking");
            $table->string("prepOfTitle");
            $table->string("photographyFees");
            $table->string("govStampDuty");
            $table->string("regFees");
            $table->string("transpotationFees");
            $table->string("notaryFees");
            $table->string("expServiceCharge");
            $table->string("refCommision");
            $table->string("postageCharge");
            $table->string("fullChargeOfServiceProvision");
            $table->string("firstAdvance");
            $table->string("secondAdvance");
            $table->string("thirdAdvance");
            $table->string("forthAdvance");
            $table->string("fifthAdvance");
            $table->string("finalPayment");
            $table->string("amountInArreas");
            $table->string("descriptionOfService");
            $table->string("pickUpDate");
            $table->string("dateOfSubmission");
            $table->string("dateOfMailing");
            $table->string("dateOfRegistration");
            $table->string("stampDuty");
            $table->string('totalAmount');
            $table->integer('createTime');
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
        Schema::dropIfExists('notary_order_payments');
    }
}
