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

    public function get_log_by_invoiceNo($invoiceNo) {
        $map['invoiceNo'] = $invoiceNo;

        return $this->where($map)->first();
    }

    public function update_log($paymentInfo) {
        dd($paymentInfo);
        return $this->update($paymentInfo);
    }
}
