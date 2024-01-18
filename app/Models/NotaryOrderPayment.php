<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaryOrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        "invoiceNo",
        "extSearch",
        "secondCopyCheck",
        "obtainExt",
        "obtainSecondCpyTaking",
        "prepOfTitle",
        "photographyFees",
        "govStampDuty",
        "regFees",
        "transpotationFees",
        "notaryFees",
        "expServiceCharge",
        "refCommision",
        "postageCharge",
        "fullChargeOfServiceProvision",
        "firstAdvance",
        "secondAdvance",
        "thirdAdvance",
        "forthAdvance",
        "fifthAdvance",
        "finalPayment",
        "amountInArreas",
        "descriptionOfService",
        "pickUpDate",
        "dateOfSubmission",
        "dateOfMailing",
        "dateOfRegistration",
        "stampDuty",
        "totalAmount",
        "createTime"
    ];

    public function add_log($paymentInfo) {
        return $this->create($paymentInfo);
    }
}
