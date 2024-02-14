<?php

namespace App\Http\Controllers\AdminUser;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminOrderAssign;
use App\Models\NotaryServiceOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    private $AppHelper;
    private $TrOrder;
    private $AdminAssign;
    private $NotaryOrder;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->TrOrder = new Order();
        $this->AdminAssign = new AdminOrderAssign();
        $this->NotaryOrder = new NotaryServiceOrder();
    }

    public function getDashboardCounts(Request $request) {

        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Role is required.");
        } else {

            try {
                $total_tr_orders = $this->TrOrder->get_all();
                $total_ns_orders = $this->NotaryOrder->get_all();
                $total_assigned_orders = $this->AdminAssign->get_assigned_count();

                $dataList = array();
                $dataList['totalOrderCount'] = (count($total_tr_orders) + count($total_ns_orders));
                $dataList['totalAssignedCount'] = (count($total_assigned_orders));
                $dataList['totalNotAssignedCount'] = ((count($total_tr_orders) + count($total_ns_orders)) - count($total_assigned_orders));

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $dataList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
