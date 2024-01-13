<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $Clients;
    private $AppHelper;
    private $Order;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Clients = new Client();
        $this->Order = new Order();
    }

    public function getAllClientList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $resp = $this->Clients->get_all();

                $dataList = array();
                foreach ($resp as $key => $value) {

                    $orderCount = $this->Order->get_order_count_by_client($value['id']);

                    $dataList[$key]['fullName'] = $value['full_name'];
                    $dataList[$key]['address'] = $value['address'];
                    $dataList[$key]['mobileNumber'] = $value['mobile_number'];
                    $dataList[$key]['email'] = $value['email'];
                    $dataList[$key]['createTime'] = $value['create_time'];
                    $dataList[$key]['orderCount'] = $orderCount;
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
