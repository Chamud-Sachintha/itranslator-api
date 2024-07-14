<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\AdminOrderAssign;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\LegalAdvice;
use App\Models\CSService;
use App\Models\LegalAdviceSerivce;

class SuperAdminController extends Controller
{
    private $AppHelper;
    private $TrOrder;
    private $AdminAssign;
    private $NotaryOrder;
    private $CsOrder;
    private $LegalAdvice;
    private $LegalAdviceSerivce;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->TrOrder = new Order();
        $this->CsOrder = new CSService();
        $this->AdminAssign = new AdminOrderAssign();
        $this->NotaryOrder = new NotaryServiceOrder();
        $this->LegalAdvice = new LegalAdvice();
        $this->LegalAdviceSerivce = new LegalAdviceSerivce();
    }

    public function getDashboardCounts(Request $request) {

        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Role is required.");
        } else {

            try {
                $total_tr_orders = $this->TrOrder->get_all();
                $total_ns_orders = $this->NotaryOrder->get_all();
                $total_cs_orders = $this->CsOrder->get_all();
                $total_legal_advice = $this->LegalAdviceSerivce->get_all();
                $total_assigned_orders = $this->AdminAssign->get_assigned_count();
               
                $NotAssign_tr_orders = $this->TrOrder->notassignget_all();
                $NotAssign_ns_orders = $this->NotaryOrder->notassignget_all();
                $NotAssign_cs_orders = $this->CsOrder->notassignget_all();
                $NotAssign_legal_advice = $this->LegalAdvice->notassignget_all();

                $complete_tr_orders = $this->TrOrder->completeget_all();
                $complete_ns_orders = $this->NotaryOrder->completeget_all();
                $complete_cs_orders = $this->CsOrder->completeget_all();
                $complete_legal_advice = $this->LegalAdviceSerivce->completeget_allDA();
                
                // Calculate total counts
                $total_tr_count = is_countable($total_tr_orders) ? count($total_tr_orders) : 0;
                $total_ns_count = is_countable($total_ns_orders) ? count($total_ns_orders) : 0;
                $total_cs_count = is_countable($total_cs_orders) ? count($total_cs_orders) : 0;
                $total_legal_count = is_countable($total_legal_advice) ? count($total_legal_advice) : 0;
              
                $total_notassign_tr_count = is_countable($NotAssign_tr_orders) ? count($NotAssign_tr_orders) : 0;
                $total_notassign_ns_count = is_countable($NotAssign_ns_orders) ? count($NotAssign_ns_orders) : 0;
                $total_notassign_cs_count = is_countable($NotAssign_cs_orders) ? count($NotAssign_cs_orders) : 0;
                $total_notassign_legal_count = is_countable($NotAssign_legal_advice) ? count($NotAssign_legal_advice) : 0;

                $totalcomplete_tr_count = is_countable($complete_tr_orders) ? count($complete_tr_orders) : 0;
                $totalcomplete_ns_count = is_countable($complete_ns_orders) ? count($complete_ns_orders) : 0;
                $totalcomplete_cs_count = is_countable($complete_cs_orders) ? count($complete_cs_orders) : 0;
                $totalcomplete_legal_count = is_countable($complete_legal_advice) ? count($complete_legal_advice) : 0;

              




                // Calculate total counts and handle potential null values
                $totalOrderCount = $total_tr_count + $total_ns_count + $total_cs_count + $total_legal_count;
                $totalAssignedCount = is_countable($total_assigned_orders) ? count($total_assigned_orders) : 0;
                $totalNotAssignedCount = ($totalOrderCount - ($total_notassign_tr_count + $total_notassign_ns_count + $total_notassign_cs_count + $total_notassign_legal_count));
                $totalCompleteCount = $totalcomplete_tr_count + $totalcomplete_ns_count + $totalcomplete_cs_count + $totalcomplete_legal_count;

            
                $dataList = array();
                $dataList['totalOrderCount'] =  $totalOrderCount;
                $dataList['totalAssignedCount'] = $totalAssignedCount;
                $dataList['totalNotAssignedCount'] = $totalNotAssignedCount;
                $dataList['totalNCompletedCount'] = $totalCompleteCount;

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
