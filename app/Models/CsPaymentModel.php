<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsPaymentModel extends Model
{
    use HasFactory;

    protected $fillable = [
        "invoiceNo",
        "companyNameApproval",
        "form1",
        "form10",
        "form13",
        "form15",
        "form18",
        "form20",
        "copyCharges",
        "articleFees",
        "amendmendFees",
        "annualFees",
        "panalties",
        "other",
        "govStampDuty",
        "companySecFees",
        "expServiceCharges",
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

    public function get_log_by_invoiceNo($invoiceNo) {
        $map['invoiceNo'] = $invoiceNo;

        return $this->where($map)->first();
    }

    public function update_log($paymentInfo) {
        return $this->update($paymentInfo);
    }

    public function add_log($info) {
        return $this->create($info);
    }
}
