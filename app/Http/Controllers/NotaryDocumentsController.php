<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\NotaryDocuments;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class NotaryDocumentsController extends Controller
{
    private $AppHelper;
    private $NotaryDocument;
    private $NotaryOrder;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->NotaryDocument = new NotaryDocuments();
        $this->NotaryOrder = new NotaryServiceOrder();
    }

    public function submitNotaryDocumentsForOrder(Request $request) {

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
                $order = $this->NotaryOrder->get_by_invoice_id($invoiceNo);

                if ($order) {
                    $docInfo = array();
                    $docInfo['orderId'] = $order->id;
                    $docInfo['createTime'] = $this->AppHelper->get_date_and_time();

                    $resp = null;

                    foreach ($document as $key => $value) {
                        $uniqueId = uniqid();
                        $ext = $value->getClientOriginalExtension();

                        $value->move(public_path('/notary_docs'), $uniqueId . '.' . $ext);
                        $docInfo['document'] = $uniqueId . '.' . $ext;

                        $resp = $this->NotaryDocument->add_log($docInfo);
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
                $order = $this->NotaryOrder->get_by_invoice_id($invoiceNo);

                if ($order) {
                    $resp = $this->NotaryDocument->get_doc_list_by_invoice($order->id);

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
