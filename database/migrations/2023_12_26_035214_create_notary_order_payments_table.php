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
            $table->string("extSearch")->nullable()->default(0);
            $table->string("secondCopyCheck")->nullable()->default(0);
            $table->string("obtainExt")->nullable()->default(0);
            $table->string("obtainSecondCpyTaking")->nullable()->default(0);
            $table->string("prepOfTitle")->nullable()->default(0);
            $table->string("photographyFees")->nullable()->default(0);
            $table->string("govStampDuty")->nullable()->default(0);
            $table->string("regFees")->nullable()->default(0);
            $table->string("transpotationFees")->nullable()->default(0);
            $table->string("notaryFees")->nullable()->default(0);
            $table->string("expServiceCharge")->nullable()->default(0);
            $table->string("refCommision")->nullable()->default(0);
            $table->string("postageCharge")->nullable()->default(0);
            $table->string("fullChargeOfServiceProvision")->nullable()->default(0);
            $table->string("firstAdvance")->nullable()->default(0);
            $table->string("secondAdvance")->nullable()->default(0);
            $table->string("thirdAdvance")->nullable()->default(0);
            $table->string("forthAdvance")->nullable()->default(0);
            $table->string("fifthAdvance")->nullable()->default(0);
            $table->string("finalPayment")->nullable()->default(0);
            $table->string("amountInArreas")->nullable()->default(0);
            $table->string("descriptionOfService")->nullable();
            $table->string("pickUpDate");
            $table->string("dateOfSubmission");
            $table->string("dateOfMailing");
            $table->string("dateOfRegistration");
            $table->string("stampDuty")->nullable()->default(0);
            $table->string('totalAmount')->nullable()->default(0);
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
