<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\AdminUser;
use App\Models\Client;
use App\Models\Order;
use App\Models\NotaryServiceOrder;

class AdminMessageController extends Controller
{
    private $AppHelper;
    private $Order;
    private $AdminMessage;
    private $AdminUser;
    private $ClientInfo;
    private $NotaryOrder;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Order = new Order();
        $this->NotaryOrder = new NotaryServiceOrder();
        $this->AdminMessage = new AdminMessage();
        $this->AdminUser = new AdminUser();
        $this->ClientInfo = new Client();
    }

    public function sendAdminMessageToClient(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $mesage = (is_null($request->message) || empty($request->message)) ? "" : $request->message;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag i srequired.");
        } else if ($invoiceNo == "") {  
            return $this->AppHelper->responseMessageHandle(0, "Invoice No is required.");
        } else if ($mesage == "") {
            return $this->AppHelper->responseMessageHandle(0, "Message is required.");
        } else {
            try {
                $prefix = substr($invoiceNo, 0, 2);
                if ($prefix === "NS") {
                    $order = $this->NotaryOrder->get_by_invoice_id(($invoiceNo));
                } else if ($prefix === "TR") {
                    $order = $this->Order->find_by_invoice(($invoiceNo));
                }else{

                }
                

                if ($order) {

                    $adminInfo = $this->AdminUser->find_by_token($request_token);

                    $dataList = array();
                    $dataList['orderId'] = $order->id;
                    $dataList['sentFrom'] = $adminInfo['id'];
                    $dataList['sentTo'] = $order->client_id;
                    $dataList['message'] = $mesage;
                    $dataList['createTime'] = $this->AppHelper->get_date_and_time();

                    $resp = $this->AdminMessage->add_log($dataList);

                    if ($resp) {
                        return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                    }   
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getMessageList(Request $request) {

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
                $prefix = substr($invoiceNo, 0, 2);
                if ($prefix === "NS") {
                    $order = $this->NotaryOrder->get_by_invoice_id(($invoiceNo));
                } else if ($prefix === "TR") {
                    $order = $this->Order->find_by_invoice(($invoiceNo));
                }else{

                }

                if ($order) {
                    $messageList = $this->AdminMessage->get_by_order_id($order->id);

                    $dataList = array();
                    foreach ($messageList as $key => $value) {

                        $dataList[$key]['toUser'] = $this->findUser($value->sent_to);
                        $dataList[$key]['fromUser'] = $this->findUser($value->sent_from);
                        $dataList[$key]['message'] = $value->message;
                        $dataList[$key]['time'] = $value->create_time;
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    private function findUser($uid) {

        $userName = null;
        $admin = $this->AdminUser->find_by_id($uid);

        if ($admin) {
            $userName = $admin->first_name . " " . $admin->last_name;
        } else {
            $client = $this->ClientInfo->get_by_id($uid);
            $userName = $client->full_name;
        }

        return $userName;
    }
}
