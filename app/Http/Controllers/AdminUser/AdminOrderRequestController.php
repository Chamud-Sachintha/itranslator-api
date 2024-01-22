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

    public function getOrderDetailsByInvoice(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $type = (is_null($request->type) || empty($request->type)) ? "" : $request->type;

        if ($request_token == "") { 
            return $this->AppHelper->responseMessageHandle(0, "Toekn is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Innvoice Number is required.");
        } else {

            try {
                
                $dataList = null;

                if ($type == "TR") {
                    $dataList = $this->getTranslateItemList($invoiceNo);
                } else if ($type == "NS") {
                    
                } else {
                        
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getOrderInfo(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $type = (is_null($request->type) || empty($request->type)) ? "" : $request->type;

        if ($request_token == "") { 
            return $this->AppHelper->responseMessageHandle(0, "Toekn is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Innvoice Number is required.");
        } else {
            
            try {
                $dataList = array();

                if ($type == "TR") {
                    $resp = $this->TranslationOrder->find_by_invoice($invoiceNo);

                    $dataList['isHaveBankSlip'] = 0;

                    if ($resp['payment_type'] == 1) {
                        $dataList['paymentMethod'] = "Bank Deposit";
                        $dataList['bankSlip'] = $resp['bank_slip'];
                        $dataList['isHaveBankSlip'] = 1;
                    } else {
                        $dataList['paymentMethod'] = "Online Payment";
                    }
                } else if ($type == "NS") {
                    $resp = $this->NotaryServiceOrder->get_by_invoice_id($invoiceNo);

                    $dataList['isHaveBankSlip'] = 0;

                    if ($resp['payment_type'] == 1) {
                        $dataList['paymentMethod'] = "Bank Deposit";
                        $dataList['bankSlip'] = $resp['bank_slip'];
                        $dataList['isHaveBankSlip'] = 1;
                    } else {
                        $dataList['paymentMethod'] = "Pending";
                    }
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getTranslateOrderDocuments(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $serviceId = (is_null($request->serviceId) || empty($request->serviceId)) ? "" : $request->serviceId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice is required.");
        } else if ($serviceId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Service Id is Required.");
        } else {

            try {
                $order = $this->TranslationOrder->find_by_invoice($invoiceNo);

                if ($order) {
                    $orderItems = $this->TrOrderItems->find_by_order_and_serviceId($order->id, $serviceId);
                    $jsonArrayValues = json_decode($orderItems->json_value);
                    
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $jsonArrayValues);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function updateOrderStatusByInvoice(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag =(is_null($request->flag) || empty($request->flag)) ? "" : $request->token;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $orderStatus = (is_null($request->orderStatus) || empty($request->orderStatus)) ? "" : $request->orderStatus;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice No is required.");
        } else if ($orderStatus == "") {
            return $this->AppHelper->responseMessageHandle(0, "Payment Status is required.");
        } else {

            try {
                $ext = explode("-", $invoiceNo);

                $orderInfo = array();
                $orderInfo['invoiceNo'] = $invoiceNo;
                $orderInfo['orderStatus'] = $orderStatus;

                $resp = null;
                if ($ext[0] == "TR") {
                    $resp = $this->TranslationOrder->update_order_status_admin($orderInfo);
                }

                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Opertion Complete");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    private function getNotaryItemList($invoiceNo) {
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

            return $dataList;
        }
    }

    private function getNotaryServiceOrderInfi($invoiceNo) {
        $orderInfo = $this->NotaryServiceOrder->get_by_invoice_id($invoiceNo);

        if ($orderInfo) {
            $dataList = array();

            // $dataList['']
        }
    }

    private function getTranslateItemList($invoiceNo) {
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

                if (property_exists($jsonDecodedValue, 'pages')) {
                    $dataList[$key]['pages'] = $jsonDecodedValue->pages;
                } else {
                    $pageCount = 0;

                    if (property_exists($jsonDecodedValue, 'page1')) {
                        $pageCount += 1;
                    }

                    if (property_exists($jsonDecodedValue, 'page2')) {
                        $pageCount += 1;
                    }

                    if (property_exists($jsonDecodedValue, 'page3')) {
                        $pageCount += 1;
                    }

                    if (property_exists($jsonDecodedValue, 'page4')) {
                        $pageCount += 1;
                    }

                    if (property_exists($jsonDecodedValue, 'page5')) {
                        $pageCount += 1;
                    }

                    if (property_exists($jsonDecodedValue, 'page6')) {
                        $pageCount += 1;
                    }

                    $dataList[$key]['pages'] = $pageCount;
                }

                $dataList[$key]['createTime'] = $value['create_time'];

                if ($orderAssignInfo) {
                    $dataList[$key]['assignedTime'] = $orderAssignInfo['create_time'];
                } else {
                    $dataList[$key]['assignedTime'] = "0";
                }
            }

            return $dataList;
        }
    }
}
