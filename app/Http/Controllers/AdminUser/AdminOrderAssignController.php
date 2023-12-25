<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminOrderAssign;
use App\Models\AdminUser;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderAssignController extends Controller
{
    private $AppHelper;
    private $OrderAssign;
    private $AdminUser;
    private $TranslateOrder;
    private $NotaryServiceOrder;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->OrderAssign = new AdminOrderAssign();
        $this->AdminUser = new AdminUser();
        $this->TranslateOrder = new Order();
        $this->NotaryServiceOrder = new NotaryServiceOrder();
    }

    public function assignOrder(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $type = (is_null($request->type) || empty($request->type)) ? "" : $request->type;

        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        
        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($type == "") {
            return $this->AppHelper->responseMessageHandle(0, "Type is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice No is Reuqired.");
        } else {

            try {
                $validateOrder = $this->OrderAssign->get_by_invoice_id($invoiceNo);

                if ($validateOrder) {
                    return $this->AppHelper->responseMessageHandle(0, "Already Assigns");
                }

                $admin = $this->AdminUser->find_by_token($request_token);

                $orderInfo = array();
                $orderInfo['invoiceNo'] = $invoiceNo;
                $orderInfo['adminId'] = $admin->id;
                $orderInfo['createTime'] = $this->AppHelper->get_date_and_time();

                $resp = $this->OrderAssign->add_log($orderInfo);

                $resp1 = null;

                if ($type == "TS") {
                    $resp1 = $this->TranslateOrder->update_order_status($invoiceNo);
                } else if ($type == "NS") {
                    $resp1 = $this->NotaryServiceOrder->update_order_status($invoiceNo);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Order Type.");
                }

                if ($resp && $resp1) {
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
