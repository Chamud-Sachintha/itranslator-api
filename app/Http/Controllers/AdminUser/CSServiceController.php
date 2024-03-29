<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminOrderAssign;
use App\Models\AdminUser;
use App\Models\CSService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CSServiceController extends Controller
{
    private $AppHelper;
    private $CSOrder;
    private $Client;
    private $OrderAssign;
    private $AdminUser;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->CSOrder = new CSService();
        $this->OrderAssign = new AdminOrderAssign();
        $this->AdminUser = new AdminUser();
    }

    public function getCSServiceOrderList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {
            try {
                $orderList = $this->CSOrder->find_all_pending();

                $dataList = array();
                
                if (count($orderList) == 0) {
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }

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

    public function getCSTaskList(Request $request) {

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
                    $resp = $this->CSOrder->get_taken_or_processing_list();

                    $dataList = array();
                    foreach ($resp as $key => $value) {
                        $orderAssign = $this->CSOrder->get_by_invoice_id($value['invoice_no']);

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

    public function getOrderInfoByInvoice(Request $request) {

        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;

        if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice No is requiored.");
        } else {

            try {
                $resp = $this->CSOrder->get_by_invoice_id($invoiceNo);

                if ($resp) {
                    $dataList = array();
                    $dataList['serviceId'] = $resp['service_index'];
                    $dataList['jsonValue'] = json_decode($resp['json_value']);

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Invoice No");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
