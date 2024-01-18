<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotaryOrderPayment;
use App\Models\NotaryServiceOrder;

class NotaryOrderPaymentController extends Controller
{
    private $AppHelper;
    private $NotaryOrder;
    private $NotaryPaymentLog;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->NotaryOrder = new NotaryServiceOrder();
        $this->NotaryPaymentLog = new NotaryOrderPayment();
    }

    public function getOrderInfoByInvoice(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice No is required.");
        } else {

            try {
                $resp = $this->NotaryOrder->get_by_invoice_id($invoiceNo);

                if ($resp) {
                    $dataList['firstDocType'] = json_decode($resp->doc_1);
                    $dataList['secondDocType'] = json_decode($resp->doc_2);
                    $dataList['thirdDocType'] = json_decode($resp->doc_3);

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Invoice No");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function setPaymentInforByInvoice(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $extSearch = (is_null($request->extSearch) || empty($request->extSearch)) ? "" : $request->extSearch;
        $secondCpCheck = (is_null($request->secondCopyCheck) || empty($request->secondCopyCheck)) ? "" : $request->secondCopyCheck;
        $obtainExt = (is_null($request->obtainExt) || empty($request->obtainExt)) ? "" : $request->obtainExt;
        $obtainSecondCpTaking = (is_null($request->obtainSecondCpyTaking) || empty($request->obtainSecondCpyTaking)) ? "" : $request->obtainSecondCpyTaking;
        $prepOfTitle = (is_null($request->prepOfTitle) || empty($request->prepOfTitle)) ? "" : $request->prepOfTitle;
        $photographyFees = (is_null($request->photographyFees) || empty($request->photographyFees)) ? "" : $request->photographyFees;
        $photoCpyFees = (is_null($request->photoCpyFees) || empty($request->photoCpyFees)) ? "" : $request->photoCpyFees;
        $govStampDuty = (is_null($request->govStampDuty) || empty($request->govStampDuty)) ? "" : $request->govStampDuty;
        $regFees = (is_null($request->regFees) || empty($request->regFees)) ? "" : $request->regFees;
        $transportFees = (is_null($request->transpotationFees) || empty($request->transpotationFees)) ? "" : $request->transpotationFees;
        $notaryFees = (is_null($request->notaryFees) || empty($request->notaryFees)) ? "" : $request->notaryFees;
        $exServiceFees = (is_null($request->expServiceCharge) || empty($request->expServiceCharge)) ? "" : $request->expServiceCharge;
        $refCommision = (is_null($request->refCommision) || empty($request->refCommision)) ? "" : $request->refCommision;
        $postageFees = (is_null($request->postageCharge) || empty($request->postageCharge)) ? "" : $request->postageCharge;
        $serviceProvitionFee = (is_null($request->fullChargeOfServiceProvision) || empty($request->fullChargeOfServiceProvision)) ? "" : $request->fullChargeOfServiceProvision;
        $firstAdvance = (is_null($request->firstAdvance) || empty($request->firstAdvance)) ? "" : $request->firstAdvance;
        $secondAdvance = (is_null($request->secondAdvance) || empty($request->secondAdvance)) ? "" : $request->secondAdvance;
        $thirdAdvance = (is_null($request->thirdAdvance) || empty($request->thirdAdvance)) ? "" : $request->thirdAdvance;
        $fourthAdvance = (is_null($request->forthAdvance) || empty($request->forthAdvance)) ? "" : $request->forthAdvance;
        $fifthdAdvance = (is_null($request->fifthAdvance) || empty($request->fifthAdvance)) ? "" : $request->fifthAdvance;
        $finalPayment = (is_null($request->finalPayment) || empty($request->finalPayment)) ? "" : $request->finalPayment;
        $arreasAmount = (is_null($request->amountInArreas) || empty($request->amountInArreas)) ? "" : $request->amountInArreas;
        $workDescription = (is_null($request->descriptionOfService) || empty($request->descriptionOfService)) ? "" : $request->descriptionOfService;
        $pickupDate = (is_null($request->pickUpDate) || empty($request->pickUpDate)) ? "" : $request->pickUpDate;
        $dateOfSubmission = (is_null($request->dateOfSubmission) || empty($request->dateOfSubmission)) ? "" : $request->dateOfSubmission;
        $dateOfMailing = (is_null($request->dateOfMailing) || empty($request->dateOfMailing)) ? "" : $request->dateOfMailing;
        $dateOfRegistration = (is_null($request->dateOfRegistration) || empty($request->dateOfRegistration)) ? "" : $request->dateOfRegistration;
        $stampDuty = (is_null($request->stampDuty) || empty($request->stampDuty)) ? "" : $request->stampDuty;

        $totalAmount = (is_null($request->totalAmount) || empty($request->totalAmount)) ? "" : $request->totalAmount;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $paymentInfo["invoiceNo"] = $invoiceNo;
                $paymentInfo["extSearch"] = $extSearch;
                $paymentInfo["secondCopyCheck"] = $secondCpCheck;
                $paymentInfo["obtainExt"] = $obtainExt;
                $paymentInfo["obtainSecondCpyTaking"] = $obtainSecondCpTaking;
                $paymentInfo["prepOfTitle"] = $prepOfTitle;
                $paymentInfo["photographyFees"] = $photographyFees;
                $paymentInfo["govStampDuty"] = $govStampDuty;
                $paymentInfo["regFees"] = $regFees;
                $paymentInfo["transpotationFees"] = $transportFees;
                $paymentInfo["notaryFees"] = $notaryFees;
                $paymentInfo["expServiceCharge"] = $exServiceFees;
                $paymentInfo["refCommision"] = $refCommision;
                $paymentInfo["postageCharge"] = $postageFees;
                $paymentInfo["fullChargeOfServiceProvision"] = $serviceProvitionFee;
                $paymentInfo["firstAdvance"] = $firstAdvance;
                $paymentInfo["secondAdvance"] = $secondAdvance;
                $paymentInfo["thirdAdvance"] = $thirdAdvance;
                $paymentInfo["forthAdvance"] = $fourthAdvance;
                $paymentInfo["fifthAdvance"] = $fifthdAdvance;
                $paymentInfo["finalPayment"] = $finalPayment;
                $paymentInfo["amountInArreas"] = $arreasAmount;
                $paymentInfo["descriptionOfService"] = $workDescription;
                $paymentInfo["pickUpDate"] = $pickupDate;
                $paymentInfo["dateOfSubmission"] = $dateOfSubmission;
                $paymentInfo["dateOfMailing"] = $dateOfMailing;
                $paymentInfo["dateOfRegistration"] = $dateOfRegistration;
                $paymentInfo["stampDuty"] = $stampDuty;
                $paymentInfo["totalAmount"] = $totalAmount;
                $paymentInfo['createTime'] = $this->AppHelper->get_date_and_time();

                $resp = $this->NotaryPaymentLog->add_log($paymentInfo);

                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getNotaryOrderPaymentInfo(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice No is required.");
        } else {

            try {
                $resp = $this->NotaryOrder->get_by_invoice_id($invoiceNo);

                if ($resp) {
                    $dataList = array();
                    
                    if ($resp['total_amount'] > 0) {
                        $dataList['amountSet'] = 1;
                    } else {
                        $dataList['amountSet'] = 0;
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Invoice No.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
