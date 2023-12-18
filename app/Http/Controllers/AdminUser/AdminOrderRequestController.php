<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminOrderAssign;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderRequestController extends Controller
{
    private $AppHelper;
    private $TranslationOrder;
    private $NotaryServiceOrder;
    private $OrderAssign;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->TranslationOrder = new Order();
        $this->NotaryServiceOrder = new NotaryServiceOrder();
        $this->OrderAssign = new AdminOrderAssign();
    }


    public function getAllTranslationOrderList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $orderList = $this->TranslationOrder->find_all_pending();

                $dataList = array();
                foreach ($orderList as $key => $value) {
                    $orderAssign = $this->OrderAssign->get_by_invoice_id($value['invoice_no']);
 
                    if ($orderAssign == null) {
                        $dataList[$key]['invoiceNo'] = $value['invoice_no'];
                        $dataList[$key]['totalAmount'] = $value['total_amount'];
                        $dataList[$key]['paymentStatus'] = $value['payment_status'];
                        $dataList[$key]['orderStatus'] = $value['order_status'];
                        $dataList[$key]['createTime'] = $value['create_time'];
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getNotaruyServiceOrderList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {
            try {
                $orderList = $this->TranslationOrder->find_all();

                $dataList = array();
                foreach ($orderList as $key => $value) {
                    $orderAssign = $this->OrderAssign->get_by_invoice_id($value['invoice_no']);

                    if (empty($orderAssign)) {
                        $dataList[$key]['invoiceNo'] = $value['invoice_no'];
                        $dataList[$key]['totalAmount'] = $value['total_amount'];
                        $dataList[$key]['paymentStatus'] = $value['payment_status'];
                        $dataList[$key]['orderStatus'] = $value['order_status'];
                        $dataList[$key]['createTime'] = $value['create_time'];
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
