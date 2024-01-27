<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsPaymentModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cs_payment_models', function (Blueprint $table) {
            $table->id();
            $table->string("invoiceNo");
            $table->string("companyNameApproval")->nullable()->default(0);
            $table->string("form1")->nullable()->default(0);
            $table->string("form10")->nullable()->default(0);
            $table->string("form13")->nullable()->default(0);
            $table->string("form15")->nullable()->default(0);
            $table->string("form18")->nullable()->default(0);
            $table->string("form20")->nullable()->default(0);
            $table->string("copyCharges")->nullable()->default(0);
            $table->string("articleFees")->nullable()->default(0);
            $table->string("amendmendFees")->nullable()->default(0);
            $table->string("annualFees")->nullable()->default(0);
            $table->string("panalties")->nullable()->default(0);
            $table->string("other")->nullable()->default(0);
            $table->string("govStampDuty")->nullable()->default(0);
            $table->string("companySecFees")->nullable()->default(0);
            $table->string("expServiceCharges")->nullable()->default(0);
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
            $table->string("descriptionOfService");
            $table->string("pickUpDate");
            $table->string("dateOfSubmission");
            $table->string("dateOfMailing");
            $table->string("dateOfRegistration");
            $table->string("stampDuty");
            $table->string("totalAmount");
            $table->integer("createTime");
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
        Schema::dropIfExists('cs_payment_models');
    }
}
