<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use App\Models\TranslatedDocuments;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TranslatedDocumentsController extends Controller
{
    private $AppHelper;
    private $TranslateDocument;
    private $Order;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->TranslateDocument = new TranslatedDocuments();
        $this->Order = new Order();
    }

    public function submitTranslatedDocumentsForOrder(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;
        $document = $request->files;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Order id is required.");
        } else {

            try {
                $order = $this->Order->find_by_invoice($invoiceNo);

                if ($order) {
                    $docInfo = array();
                    $docInfo['orderId'] = $order->id;
                    $docInfo['createTime'] = $this->AppHelper->get_date_and_time();

                    $resp = null;

                    foreach ($document as $key => $value) {
                        $uniqueId = uniqid();
                        $ext = $value->getClientOriginalExtension();

                        $value->move(public_path('/translated_docs'), $uniqueId . '.' . $ext);
                        $docInfo['document'] = $uniqueId . '.' . $ext;

                        $resp = $this->TranslateDocument->add_log($docInfo);
                    }

                    if ($resp) {
                        return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                    }
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getUploadedDocumentsByOrder(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $invoiceNo = (is_null($request->invoiceNo) || empty($request->invoiceNo)) ? "" : $request->invoiceNo;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Toekn is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($invoiceNo == "") {
            return $this->AppHelper->responseMessageHandle(0, "Invoice nO is required.");
        } else {

            try {
                $order = $this->Order->find_by_invoice($invoiceNo);

                if ($order) {
                    $resp = $this->TranslateDocument->get_doc_list_by_invoice($order->id);

                    $dataList = array();
                    foreach ($resp as $key => $value) {
                        $dataList[$key]['id'] = $value['id'];
                        $dataList[$key]['orderId'] = $value['order_id'];
                        $dataList[$key]['document'] = $value['document'];
                        $dataList[$key]['createTime'] = $value['create_time']; 
                    }

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
