<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminOrderAssign;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminOrderRequestController extends Controller
{
    private $AppHelper;
    private $TranslationOrder;
    private $NotaryServiceOrder;
    private $OrderAssign;
    private $TrOrderItems;
    private $Service;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->TranslationOrder = new Order();
        $this->NotaryServiceOrder = new NotaryServiceOrder();
        $this->OrderAssign = new AdminOrderAssign();
        $this->TrOrderItems = new OrderItems();
        $this->Service = new Service();
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
                $orderList = $this->NotaryServiceOrder->find_all_pending();

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

    public function getOrderDetailsByInvoice(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;

        if ($request_token == "") { 
            return $this->AppHelper->responseMessageHandle(0, "Toekn is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Innvoice Number is required.");
        } else {

            try {
                $orderInfo = $this->TranslationOrder->find_by_invoice($invoiceNo);

                if ($orderInfo) {
                    $resp = $this->TrOrderItems->get_by_orderId_and_serviceId($orderInfo->id);

                    $dataList = array();
                    foreach ($resp as $key => $value) {

                        $serviceInfo = $this->Service->find_by_id($value['service_id']);
                        $orderAssignInfo = $this->OrderAssign->get_by_invoice_id($orderInfo->invoice_no);
                        $jsonDecodedValue = json_decode($value->json_value);

                        $dataList[$key]['serviceId'] = $value['service_id'];
                        $dataList[$key]['documentTitle'] = $serviceInfo['service_name'];
                        $dataList[$key]['pages'] = $jsonDecodedValue->pages;
                        $dataList[$key]['createTime'] = $value['create_time'];
                        $dataList[$key]['assignedTime'] = $orderAssignInfo['create_time'];
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
