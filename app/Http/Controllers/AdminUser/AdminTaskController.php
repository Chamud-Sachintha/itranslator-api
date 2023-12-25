<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminOrderAssign;
use App\Models\AdminUser;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    private $AppHelper;
    private $TranslateOrder;
    private $NotaryServiceOrder;
    private $AdminUser;
    private $OrderAssign;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->TranslateOrder = new Order();
        $this->NotaryServiceOrder = new NotaryServiceOrder();
        $this->AdminUser = new AdminUser();
        $this->OrderAssign = new AdminOrderAssign();
    }

    public function getAllTranslateTaskList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $adminInfo = $this->AdminUser->find_by_token($request_token);

                if ($adminInfo) {
                    $resp = $this->TranslateOrder->get_taken_or_processing_list();

                    $dataList = array();
                    foreach ($resp as $key => $value) {
                        $orderAssign = $this->OrderAssign->get_by_invoice_id($value['invoice_no']);

                        $dataList[$key]['invoiceNo'] = $value['invoice_no'];
                        $dataList[$key]['totalAmount'] = $value['total_amount'];
                        $dataList[$key]['paymentStatus'] = $value['payment_status'];
                        $dataList[$key]['orderStatus'] = $value['order_status'];
                        $dataList[$key]['createTime'] = $value['create_time'];
                        $dataList[$key]['assignedTime'] = $orderAssign['create_time'];
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getNotaryTaskList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token isrequired.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $adminInfo = $this->AdminUser->find_by_token($request_token);

                if ($adminInfo) {
                    $resp = $this->NotaryServiceOrder->get_taken_or_processing_list();

                    $dataList = array();
                    foreach ($resp as $key => $value) {
                        $orderAssign = $this->NotaryServiceOrder->get_by_invoice_id($value['invoice_no']);

                        $dataList[$key]['invoiceNo'] = $value['invoice_no'];
                        $dataList[$key]['totalAmount'] = $value['total_amount'];
                        $dataList[$key]['paymentStatus'] = $value['payment_status'];
                        $dataList[$key]['orderStatus'] = $value['order_status'];
                        $dataList[$key]['createTime'] = $value['create_time'];
                        $dataList[$key]['assignedTime'] = $orderAssign['create_time'];
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
