<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Client;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderRequests extends Controller
{
    private $AppHelper;
    private $Order;
    private $Client;
    private $NotaryOrder;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Order = new Order();
        $this->Client = new Client();
        $this->NotaryOrder = new NotaryServiceOrder();
    }

    public function getOrderRequestList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $pendingTranslateOrders = $this->Order->find_all_pending();

                $pendingOrderList = array();
                foreach ($pendingTranslateOrders as $key => $value) {

                    $clientInfo = $this->Client->get_by_id($value['client_id']);

                    $pendingOrderList[$key]['invoiceNo'] = $value['invoice_no'];
                    $pendingOrderList[$key]['fullName'] = $clientInfo['full_name'];

                    if ($value['payment_type'] == 1) {
                        $pendingOrderList[$key]['paymentMethod'] = "Bank Deposit";
                    } else {
                        $pendingOrderList[$key]['paymentMethod'] = "Online Payment";
                    }

                    $pendingOrderList[$key]['totalAmount'] = $value['total_amount'];
                    $pendingOrderList[$key]['createTime'] = $value['create_time'];
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $pendingOrderList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function updateOrderPaymentStatus(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag =(is_null($request->flag) || empty($request->flag)) ? "" : $request->token;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $paymentStatus = (is_null($request->paymentStatus) || empty($request->paymentStatus)) ? "" : $request->paymentStatus;
        
        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice No is required.");
        } else if ($paymentStatus == "") {
            return $this->AppHelper->responseMessageHandle(0, "Payment Status is required.");
        } else {

            try {
                $ext = explode("-", $invoiceNo);
                $resp = null;

                if ($ext[0] == "TR") {
                    $resp = $this->Order->update_payment_status($invoiceNo);
                } else if ($ext[0] == "NS") {
                    $resp = $this->NotaryOrder->update_payment_status($invoiceNo);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Invoice");
                }

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
}
