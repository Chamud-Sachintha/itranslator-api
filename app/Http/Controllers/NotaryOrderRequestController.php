<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Client;
use App\Models\NotaryServiceOrder;
use Illuminate\Http\Request;

class NotaryOrderRequestController extends Controller
{
    private $AppHelper;
    private $NotaryServiceOrders;
    private $Client;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->NotaryServiceOrders = new NotaryServiceOrder();
        $this->Client = new Client();
    }

    public function getAllPendingNotaryOrderList(Request $request) {
        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $pendingTranslateOrders = $this->NotaryServiceOrders->find_all_payment_pending();

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

                    $pendingOrderList[$key]['totalAmount'] = $value['total_amt'];
                    $pendingOrderList[$key]['createTime'] = $value['create_time'];
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $pendingOrderList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
