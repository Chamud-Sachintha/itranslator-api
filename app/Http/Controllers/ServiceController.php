<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Service;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    private $SuperAdmin;
    private $Service;
    private $AppHelper;

    public function __construct()
    {   
        $this->SuperAdmin = new SuperAdmin();
        $this->Service = new Service();
        $this->AppHelper = new AppHelper();
    }

    public function addNewService(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $serviceName = (is_null($request->serviceName) || empty($request->serviceName)) ? "" : $request->serviceName;
        $firstPrice = (is_null($request->firstPrice) || empty($request->firstPrice)) ? "" : $request->firstPrice;
        $secondPrice = (is_null($request->secondPrice) || empty($request->secondPrice)) ? "" : $request->secondPrice;
        $thirdPrice = (is_null($request->thirdPrice) || empty($request->thirdPrice)) ? "" : $request->thirdPrice;
        $description = (is_null($request->description) || empty($request->description)) ? "" : $request->description;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($serviceName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Service Name is required.");
        } else if ($firstPrice == "") {
            return $this->AppHelper->responseMessageHandle(0, "First Price is required.");
        } else if ($secondPrice == "") {
            return $this->AppHelper->responseMessageHandle(0, "Second Price is required.");
        } else if ($thirdPrice == "") {
            return $this->AppHelper->responseMessageHandle(0, "Third Price is required.");
        } else if ($description == "") {
            return $this->AppHelper->responseMessageHandle(0, "Descripotion is required.");
        } else {
            
            try {

                $userPerm = $this->checkPermission($request_token, $flag);

                $serviceInfo = array();
                if ($userPerm == true) {
                    $serviceInfo['serviceName'] = $serviceName;
                    $serviceInfo['firstPrice'] = $firstPrice;
                    $serviceInfo['secondPrice'] = $secondPrice;
                    $serviceInfo['thirdPrice'] = $thirdPrice;
                    $serviceInfo['description'] = $description;
                    $serviceInfo['createTime'] = $this->AppHelper->get_date_and_time();

                    $service = $this->Service->add_log($serviceInfo);

                    if ($service) {
                        return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $service);
                    } else {
                        return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                    }
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Permissions");
                }
            } catch (\Exception $e) {   
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getTranslateServiceList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $allServiceList = $this->Service->query_all();

                $serviceList = array();
                foreach ($allServiceList as $key => $value) {
                    $serviceList[$key]['serviceId'] = $value['id'];
                    $serviceList[$key]['serviceName'] = $value['service_name'];
                    $serviceList[$key]['firstPrice'] = $value['price_1'];
                    $serviceList[$key]['secondPrice'] = $value['price_2'];
                    $serviceList[$key]['thirdPrice'] = $value['price_3'];
                    $serviceList[$key]['description'] = $value['description'];
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $serviceList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    private function checkPermission($token, $flag) {
        
        $perm = null;

        try {
            if ($flag == "SA") {
                $perm = $this->SuperAdmin->check_permission($token, $flag);
            } else {
                return false;
            }

            if (!empty($perm)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
