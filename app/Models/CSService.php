<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_index',
        'invoice_no',
        'client',
        'json_value',
        'total_amount',
        'payment_type',
        'bank_slip',
        'payment_status',
        'order_status',
        'is_customer_completed',
        'create_time'
    ];

    public function find_all_pending() {
        $map['order_status'] = 0;

        return $this->where($map)->get();
    }

    public function get_taken_or_processing_list() {
        $orderStatusValues = [1];
        return $this->whereIn('order_status', $orderStatusValues)->get();
    }

    public function get_taken_or_complete_list() {
        $orderStatusValues = [2];
        return $this->whereIn('order_status', $orderStatusValues)->get();
    }

    public function update_order_status($invoiceNo) {
        $map['invoice_no'] = $invoiceNo;
        $map1['order_status'] = 1;

        return $this->where($map)->update($map1);
    }

    public function set_total_amount_of_order($invoiceNo, $totalAmount) {
        $map['invoice_no'] = $invoiceNo;
        $map1['total_amount'] = $totalAmount;

        return $this->where($map)->update($map1);
    }

    public function get_by_invoice_id($invoiceId) {
        $map['invoice_no'] = $invoiceId;

        return $this->where($map)->first();
    }

    public function update_order_status_admin($orderInfo){
        $map['invoice_no'] = $orderInfo['invoiceNo'];
        $map1['order_status'] = $orderInfo['orderStatus'];

        return $this->where($map)->update($map1);
    }
}
