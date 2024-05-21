<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LGServicesController extends Controller
{
    public function getLegalRequest(Request $request){
        /*
                $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        
                if ($request_token == "") {
                    return $this->AppHelper->responseMessageHandle(0, "Token is required.");
                } else {
        
                    $client = $this->Client->find_by_token($request->token);
                    $resp = $this->LegalAdvice->Get_Details($client['id']);
        
                    $dataList = array();
                            foreach ($resp as $key => $value) {
                                $dataList[$key]['id'] = $value['ID'];
                                $dataList[$key]['message'] = $value['Message'];
                                $dataList[$key]['A_Admin_ID'] = $value['A_Admin_ID'];
                                $dataList[$key]['Status'] = $value['Status'];
                                $dataList[$key]['createTime'] = $value['create_time'];
                            }
        
                            return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }*/
            }
}
