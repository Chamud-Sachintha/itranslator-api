<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\AdminUser;
use App\Models\Client;
use App\Models\Order;

class AdminMessageController extends Controller
{
    private $AppHelper;
    private $Order;
    private $AdminMessage;
    private $AdminUser;
    private $ClientInfo;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Order = new Order();
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
                $order = $this->Order->find_by_invoice(($invoiceNo));

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
                $order = $this->Order->find_by_invoice($invoiceNo);

                if ($order) {
                    $dataList = array();
                    
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
