<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Client;
use App\Models\CSService;
use App\Models\LegalAdvice;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use App\Models\SMSModel;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GClient;

class SMSModelController extends Controller
{
    private $SMSModel;
    private $AppHelper;
    private $TranslationOrder;
    private $NotaryServiceOrder;
    private $CSOrder;
    private $LegalAdviceOrder;
    private $Client;
    
    public const API_URL = "http://sender.marxhal.com/api/v2/send.php";
    public const USER_ID = "105547";
    public const API_KEY = "bntz7067pk4iw3fm6";

    public function __construct()
    {
        $this->SMSModel = new SMSModel();
        $this->AppHelper = new AppHelper();
        $this->TranslationOrder = new Order();
        $this->NotaryServiceOrder = new NotaryServiceOrder();
        $this->CSOrder = new CSService();
        $this->LegalAdviceOrder = new LegalAdvice();
        $this->Client = new Client();
    }

    public function sendOrderCompleteNotificationSMS(Request $request) {

        $orderNumber = (is_null($request->orderNumber) || empty($request->orderNumber)) ? "" : $request->orderNumber;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;
        $orderType = (is_null($request->orderType) || empty($request->orderType)) ? "" : $request->orderType;

        $content = ""; $orderInfo = ""; $clientInfo = "";

        if ($orderNumber == "" || $orderType == "" || $clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Order Number & Order Type is Required.");
        } 

        if ($orderType != "TR" || $orderType != "NS" || $orderType != "CS" || $orderType != "LG") {
            return $this->AppHelper->responseMessageHandle(0, "Invalid Order Type");
        } else {

            if ($this->verifyOrder($orderType, $orderNumber, $clientId)) {
                $clientInfo = $this->Client->get_by_id($clientId);
                $content = "Hi [Customer Name], your order #[Order Number] has been successfully completed! Thank you for shopping with us. For more details, visit [Website Link]. - [Your Company Name]";
                
                $smsResponse = $this->sendCode("", $clientInfo->mobile_number, $content);

                if ($smsResponse["status"] == "success" && $smsResponse['result'] == "sent") {

                    $smsData['clientId'] = $clientInfo->id;
                    $smsData['messageId'] = $smsResponse['msg_id'];
                    $smsData['verifyCode'] = "N/A";
                    $smsData['content'] = $content;
                    $smsData['createTime'] = $this->AppHelper->day_time();
    
                    $smsLog = $this->SMSModel->add_log($smsData);
    
                    if ($smsLog) {
                        return $this->AppHelper->responseMessageHandle(1, "Message Sent Successfully.");
                    } else {
                        return $this->AppHelper->responseMessageHandle(0, "Error Occued.");
                    }
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occued." . $smsResponse['status']);
                }
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Invalid Client ID.");
            }
        }
    }

    private function verifyOrder($orderType, $orderNumber, $clientId) {

        $orderInfo = ""; $response = false;

        if ($orderType == "TR") {
            $orderInfo = $this->TranslationOrder->find_by_invoice($orderNumber);

            if ($orderInfo) {
                if ($orderInfo->client_id == $clientId) {
                    $response = true;
                }
            }
        } else if ($orderType == "NS") {
            $orderInfo = $this->NotaryServiceOrder->get_by_invoice_id($orderNumber);

            if ($orderInfo) {
                if ($orderInfo->client_id == $clientId) {
                    $response = true;
                }
            }
        } else if ($orderType == "CS") {
            $orderInfo = $this->CSOrder->get_by_invoice_id($orderNumber);

            if ($orderInfo) {
                if ($orderInfo->client == $clientId) {
                    $response = true;
                }
            }
        } else if ($orderType == "LG") {
            $orderInfo = $this->LegalAdviceOrder->GetClientID($orderNumber);

            if ($orderInfo) {
                if ($orderInfo->Client_ID == $clientId) {
                    $response = true;
                }
            }
        } else {
            $response = false;
        }

        return $response;
    }

    private function sendCode($clientName, $mobile, $content) {
        // Create a new Guzzle client
        $client = new GClient();

        $data = [
            'user_id' => self::USER_ID,
            'api_key' => self::API_KEY,
            'sender_id' => "My Demo sms",
            'to' => $mobile,
            'message' => $content
            // Add other key-value pairs as needed
        ];

        try {
            // Make the POST request
            $response = $client->post(self::API_URL, [
                'form_params' => $data,
            ]);

            // Get the response body
            $responseBody = $response->getBody()->getContents();

            // Decode JSON response to array if needed
            $responseArray = json_decode($responseBody, true);

            // Return the response or do something with it
            return $responseArray;

        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
