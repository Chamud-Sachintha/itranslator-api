<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppHelper;
use App\Models\AdminMessage;
use App\Models\AdminOrderAssign;
use App\Models\AdminUser;
use App\Models\Client;
use App\Models\Order;
use App\Models\LegalAdvice;
use App\Models\LegalAdviceSerivce;
use App\Models\NotaryServiceOrder;

class LegalAdviceController extends Controller
{

    private $AppHelper;
    private $AdminMessage;
    private $Order;
    private $Client;
    private $AdminUser;
    private $AdminOrderAssign;
    private $LegalAdvice;
    private $LegalAdviceSerivce;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Order = new Order();
        $this->AdminMessage = new AdminMessage();
        $this->Client = new Client();
        $this->AdminUser = new AdminUser();
        $this->AdminOrderAssign = new AdminOrderAssign();
        $this->NotaryServiceOrder = new NotaryServiceOrder();
        $this->LegalAdvice = new LegalAdvice();
        $this->LegalAdviceSerivce = new LegalAdviceSerivce();
    }

    /*public function sendLegalRequest(Request $request){
        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $message = (is_null($request->message) || empty($request->message)) ? "" : $request->message;
        $LegalDoc = $request->hasFile('LegalDoc') ? $request->file('LegalDoc') : [];
        $uploadedFile = $request->file('LegalDoc');

        
        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($message == "") {
            return $this->AppHelper->responseMessageHandle(0, "Message is required.");
        } else {
            try {

                $client = $this->Client->find_by_token($request->token);
               
                $LegalMessage = array();
                $LegalMessage['Client_Id'] = $client['id'];
                $LegalMessage['Message'] = $message;
                if($LegalDoc == "")
                {
                    $LegalMessage['UploadFiles'] = "";
                }
                else{
                    foreach ($LegalDoc as $key => $value) {
                   // $LegalMessage['UploadFiles'] = $this->AppHelper->storeImage($value, "Legal");
                   $fileNames[] = $file->store('Legal', 'public');
                    }
                }
                $LegalMessage['UploadFiles'] = json_encode($fileNames);
                $LegalMessage['createtime'] = $this->AppHelper->get_date_and_time();
               
                $resp = $this->LegalAdvice->submit_Details($LegalMessage);

                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }*/

    public function sendLegalRequest(Request $request)
{
    $request_token = $request->filled('token') ? $request->token : "";
    $flag = $request->filled('flag') ? $request->flag : "";
    $message = $request->filled('message') ? $request->message : "";
    $LegalDoc = $request->hasFile('LegalDoc') ? $request->file('LegalDoc') : [];

    if ($request_token == "") {
        return $this->AppHelper->responseMessageHandle(0, "Token is required.");
    } elseif ($flag == "") {
        return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
    } elseif ($message == "") {
        return $this->AppHelper->responseMessageHandle(0, "Message is required.");
    } else {
        try {
            $client = $this->Client->find_by_token($request_token);
            $LegalMessage = [];
            $LegalMessage['Client_Id'] = $client['id'];
            $LegalMessage['OrderNo'] = 'lg-' . uniqid(); 
           
            $LegalMessage['Message'] = $message;
            $fileNames = [];

            if (!empty($LegalDoc)) {
                foreach ($LegalDoc as $key => $file) {
                    $formated_dir = "Legal/"; 
                    $fileName = $file->getClientOriginalName(); 
                    $file->move(public_path($formated_dir), $fileName); 
                    $filePaths[] = basename(public_path($formated_dir . $fileName)); 
                }
            }

           
            $LegalMessage['FileName'] = !empty($filePaths) ? json_encode($filePaths) : "";
            $LegalMessage['createtime'] = $this->AppHelper->get_date_and_time();

            
            $resp = $this->LegalAdvice->submit_Details($LegalMessage);

            if ($resp) {
                return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Error Occurred.");
            }
        } catch (\Exception $e) {
            return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
        }
    }
}


    private function decodeImageData($base64Array) {

        $jsonEncodeImageData = array();

        foreach ($base64Array as $key => $value) {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $value));
            $imageFileName = 'image_' . time() . $key . '.png';

            file_put_contents(public_path() . '/images' . '/' . $imageFileName, $imageData);
            $jsonEncodeImageData[$key] = $imageFileName;    
        }

        return json_encode($jsonEncodeImageData);
    }

    public function getLegalRequest(Request $request){

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else {

            $adminInfo = $this->AdminUser->find_by_token($request_token);
          
            $resp = $this->LegalAdvice->Get_All_Details();

            $dataList = array();
                    foreach ($resp as $key => $value) {
                        $dataList[$key]['id'] = $value['ID'];
                        $dataList[$key]['message'] = $value['Message'];
                        $dataList[$key]['OrderNo'] = $value['OrderNo'];
                        $dataList[$key]['Status'] = $value['Status'];
                        $dataList[$key]['createTime'] = $value['create_time'];
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
        }
    }

    public function AssignLegalRequest(Request $request){


        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $OrderNo =  (is_null($request->OrderNo) || empty($request->OrderNo)) ? "" : $request->OrderNo;
        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else {
            //
            try {
            $adminInfo = $this->AdminUser->find_by_token($request_token);
            $resp = $this->LegalAdvice->GetClientID( $OrderNo);
           
            $wer = $this->LegalAdvice->updateStatus($OrderNo,$adminInfo['id']);
                $dataList = array();
                foreach ($resp as $key => $value) {
                $dataList['Adminid'] = $adminInfo['id'];
                $dataList['OrderNo'] =  $OrderNo;
                $dataList['Message'] = "Dear Sir, I am your legal advisor and from now on I will assist you with the legal advice required for your problem, ";
                $dataList['Client_ID'] = $value['Client_ID'];
                //dd($value);
                //$dataList['create_time'] = date("Y-m-d H:i:s");
                }
               
           
          
                $resp = $this->LegalAdviceSerivce->submit_msg_Details($dataList);

            if ($resp) {
                return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Error Occurred.");
            }
        } catch (\Exception $e) {
            return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
        }
        }
    }

    public function getLegalTask(Request $request){

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else {

            $adminInfo = $this->AdminUser->find_by_token($request_token);
          
            $resp = $this->LegalAdviceSerivce->Get_All_Task_Details($adminInfo['id']);

            $dataList = array();
            foreach($resp as $key => $value){
                $wer = $this->LegalAdvice->gettaskdata( $value->order_no);

               // DD($wer);
                foreach($wer as $key => $value2){
                $dataList[$key]['id'] = $value2['ID'];
                $dataList[$key]['message'] = $value2['Message'];
                $dataList[$key]['OrderNo'] = $value2['OrderNo'];
                $dataList[$key]['Status'] = $value2['Status'];
                $dataList[$key]['createTime'] = $value2['create_time'];
                }
            }

          
                  
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
        }
    }

    public function getadminLmessage(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $OrderNo = (is_null($request->OrderNo) || empty($request->OrderNo)) ? "" : $request->OrderNo;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($OrderNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "OrderNo No is required.");
        } else {

            try {
                
                $resp = $this->LegalAdviceSerivce->Get_message_Details($OrderNo);
                
                    $dataList = array();
                    foreach ($resp as $key => $value) {

                        $dataList[$key]['toUser'] = $this->findUser($value->sent_to);
                        $dataList[$key]['fromUser'] = $this->findUser($value->sent_from);
                        $dataList[$key]['message'] = $value->message;
                        $dataList[$key]['time'] = $value->created_at;
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                
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

    public function sendadminLmessage(Request $request){
        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $OrderNo = (is_null($request->OrderNo) || empty($request->OrderNo)) ? "" : $request->OrderNo;
        $LegalDoc = $request->hasFile('Doc') ? $request->file('Doc') : [];

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($OrderNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "OrderNo No is required.");
        } else {
            try {
                $adminInfo = $this->AdminUser->find_by_token($request_token);
                $resp = $this->LegalAdvice->GetClientID( $OrderNo);
               
                    $dataList = array();
                    foreach ($resp as $key => $value) {
                    $dataList['Adminid'] = $adminInfo['id'];
                    $dataList['OrderNo'] =  $OrderNo;
                    $dataList['Message'] = $request->message;

                    
                    $fileNames = [];

                    if (!empty($LegalDoc)) {
                        foreach ($LegalDoc as $key => $file) {
                            $formated_dir = "Legal/"; 
                            $fileName = $file->getClientOriginalName(); 
                            $file->move(public_path($formated_dir), $fileName); 
                            $filePaths[] = basename(public_path($formated_dir . $fileName)); 
                        }
                    }
        
                   
                    $dataList['filename'] = !empty($filePaths) ? json_encode($filePaths) : "";

                    $dataList['Client_ID'] = $value['Client_ID'];
                    //dd($value);
                    //$dataList['create_time'] = date("Y-m-d H:i:s");
                    }
                   
               
                   
                    $resp = $this->LegalAdviceSerivce->submit_Lmsg_Details($dataList);
    
                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occurred.");
                } 
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
                
    }


    public function getLegalDocs(Request $request){


        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $OrderNo = (is_null($request->OrderNo) || empty($request->OrderNo)) ? "" : $request->OrderNo;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($OrderNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "OrderNo No is required.");
        } else {

            try {
                
                $resp = $this->LegalAdviceSerivce->Get_Doc_Details($OrderNo);
                foreach ($resp as $resp) {
                    if ($resp !== null) {
                        $filteredItems[] = $resp;
                    }
                }
               
               
          
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $filteredItems);
                
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }

    }

    public function getLegalFDocs(Request $request){
        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $OrderNo = (is_null($request->OrderNo) || empty($request->OrderNo)) ? "" : $request->OrderNo;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($OrderNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "DocName  is required.");
        } else {

            try {
                
                $resp = $this->LegalAdvice->Get_Doc_Details($OrderNo);
               
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $resp);
                
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function viewLegalDocs(Request $request){
        $request_token = $request->input('token', ''); 
        $flag = $request->input('flag', ''); 
        $DocName = $request->input('DocName', ''); 
    
        if (empty($request_token)) {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if (empty($flag)) {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if (empty($DocName)) {
            return $this->AppHelper->responseMessageHandle(0, "DocName is required.");
        } else {
            
            $formated_dir = "Legal/";
            $filePath = public_path($formated_dir . $DocName); 
    
            if (!file_exists($filePath)) {
                return $this->AppHelper->responseMessageHandle(0, "File not found.");
            }
            else
            {
                return $this->AppHelper->responseMessageHandle(1, "File  found.");
            }
    
        }
    }
    
    public function getLegalComplete(Request $request){
        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else {

            $adminInfo = $this->AdminUser->find_by_token($request_token);
          
            $resp = $this->LegalAdviceSerivce->Get_All_Task_Details($adminInfo['id']);

            $dataList = array();
            foreach($resp as $key => $value){
                $wer = $this->LegalAdvice->gettaskdata( $value->order_no);

               // DD($wer);
                foreach($wer as $key => $value2){
                $dataList[$key]['id'] = $value2['ID'];
                $dataList[$key]['message'] = $value2['Message'];
                $dataList[$key]['OrderNo'] = $value2['OrderNo'];
                $dataList[$key]['Status'] = $value2['Status'];
                $dataList[$key]['createTime'] = $value2['create_time'];
                }
            }

          
                  
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
        }
    }

}
