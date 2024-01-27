<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\CsPaymentModel;
use App\Models\CSService;
use Illuminate\Http\Request;

class CsPaymentModelController extends Controller
{
    private $AppHelper;
    private $CsOrder;
    private $CsPaymentLog;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->CsOrder = new CSService();
        $this->CsPaymentLog = new CsPaymentModel();
    }

    public function addPaymentLog(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $companyNameApproval = (is_null($request->companyNameApproval) || empty($request->companyNameApproval)) ? "" : $request->companyNameApproval;
        $form1 = (is_null($request->form1) || empty($request->form1)) ? "" : $request->form1;
        $form10 = (is_null($request->form10) || empty($request->form10)) ? "" : $request->form10;
        $form13 = (is_null($request->form13) || empty($request->form13)) ? "" : $request->form13;
        $form15 = (is_null($request->form15) || empty($request->form15)) ? "" : $request->form15;
        $form18 = (is_null($request->form18) || empty($request->form18)) ? "" : $request->form18;
        $form19 = (is_null($request->form19) || empty($request->form19)) ? "" : $request->form19;
        $form20 = (is_null($request->form20) || empty($request->form20)) ? "" : $request->form20;
        $copyCharges = (is_null($request->copyCharges) || empty($request->copyCharges)) ? "" : $request->copyCharges;
        $articleFees = (is_null($request->articleFees) || empty($request->articleFees)) ? "" : $request->articleFees;
        $amendmendFees = (is_null($request->amendmendFees) || empty($request->amendmendFees)) ? "" : $request->amendmendFees;
        $annualFees = (is_null($request->annualFees) || empty($request->annualFees)) ? "" : $request->annualFees;
        $panalties = (is_null($request->panalties) || empty($request->panalties)) ? "" : $request->panalties;
        $other = (is_null($request->other) || empty($request->other)) ? "" : $request->other;
        $govStampDuty = (is_null($request->govStampDuty) || empty($request->govStampDuty)) ? "" : $request->govStampDuty;
        $transportFees = (is_null($request->transportFees) || empty($request->transportFees)) ? "" : $request->transportFees;
        $companySecFees = (is_null($request->companySecFees) || empty($request->companySecFees)) ? "" : $request->companySecFees;
        $expServiceCharges = (is_null($request->expServiceCharges) || empty($request->expServiceCharges)) ? "" : $request->expServiceCharges;
        
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

            $paymentInfo["invoiceNo"] = $invoiceNo;
            $paymentInfo["companyNameApproval"] = $companyNameApproval;
            $paymentInfo["form1"] = $form1;
            $paymentInfo["form10"] = $form10;
            $paymentInfo["form13"] = $form13;
            $paymentInfo["form15"] = $form15;
            $paymentInfo["form18"] = $form18;
            $paymentInfo["form19"] = $form19;
            $paymentInfo["form20"] = $form20;
            $paymentInfo["copyCharges"] = $copyCharges;
            $paymentInfo["articleFees"] = $articleFees;
            $paymentInfo["amendmendFees"] = $amendmendFees;
            $paymentInfo["annualFees"] = $annualFees;
            $paymentInfo["panalties"] = $panalties;
            $paymentInfo["other"] = $other;
            $paymentInfo["govStampDuty"] = $govStampDuty;
            $paymentInfo["companySecFees"] = $companySecFees;
            $paymentInfo["expServiceCharges"] = $expServiceCharges;
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

            $validate_log = $this->CsPaymentLog->get_log_by_invoiceNo($invoiceNo);

            $resp = null;
            if (empty($validate_log)) {
                $resp = $this->CsPaymentLog->add_log($paymentInfo);
            } else {
                $paymentInfo['id'] = $validate_log['id'];
                $model = $this->CsPaymentLog->find($validate_log['id']);

                $resp = $model->update($paymentInfo);
            }

            if ($resp) {
                $this->CsOrder->set_total_amount_of_order($invoiceNo, $totalAmount);
                return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
            }
        }
    }

    public function getCSOrderPaymentInfo(Request $request) {

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
                $resp = $this->CsPaymentLog->get_log_by_invoiceNo($invoiceNo);

                $dataList = array();
                $dataList['isOrderPaymentSet'] = false;

                if ($resp) {
                    
                    $dataList = $resp;
                    $dataList['isOrderPaymentSet'] = true;

                    if ($resp['totalAmount'] != "0.00" || $resp['totalAmount'] != "0") {
                        $amountInArreas = $this->AppHelper->convertToNumber($resp['totalAmount']) - 
                                        ($this->AppHelper->convertToNumber($resp['firstAdvance']) + $this->AppHelper->convertToNumber($resp['secondAdvance']) + 
                                        $this->AppHelper->convertToNumber($resp['thirdAdvance']) + $this->AppHelper->convertToNumber($resp['forthAdvance']) + $this->AppHelper->convertToNumber($resp['fifthAdvance']) + 
                                        $this->AppHelper->convertToNumber($resp['finalPayment']));
                    } else {
                        $amountInArreas = 0;
                    }

                    $dataList['amountInArreas'] = $amountInArreas;

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                } else {
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
