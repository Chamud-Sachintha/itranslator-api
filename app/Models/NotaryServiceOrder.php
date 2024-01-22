<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaryServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_no',
        'main_category',
        'sub_category',
        'description_of_service',
        'doc_1',
        'doc_2',
        'doc_3',
        'date_of_signing',
        'start_date',
        'end_date',
        'value',
        'monthly_rent',
        'advance_amount',
        'village_officer_number',
        'devisional_sec',
        'local_gov',
        'district',
        'land_reg_office',
        'notary_person_json',
        'total_amt',
        'payment_status',
        'order_status',
        'is_customer_complete',
        'create_time',
        'modified_time'
    ];

    public function find_all_pending() {
        $map['order_status'] = 0;

        return $this->where($map)->get();
    }

    public function find_all_payment_pending() {
        $map['payment_status'] = 0;

        return $this->where($map)->get();
    }

    public function get_by_invoice_id($invoiceId) {
        $map['invoice_no'] = $invoiceId;

        return $this->where($map)->first();
    }

    public function get_taken_or_processing_list() {
        $orderStatusValues = [1, 2];
        return $this->whereIn('order_status', $orderStatusValues)->get();
    }

    public function update_order_status($invoiceNo) {
        $map['invoice_no'] = $invoiceNo;
        $map1['order_status'] = 1;

        return $this->where($map)->update($map1);
    }

    public function update_order_status_admin($orderInfo) {
        $map['invoice_no'] = $orderInfo['invoiceNo'];
        $map1['order_status'] = $orderInfo['orderStatus'];

        return $this->where($map)->update($map1);
    }

    public function set_total_amount_of_order($invoiceNo, $totalAmount) {
        $map['invoice_no'] = $invoiceNo;
        $map1['total_amt'] = $totalAmount;

        return $this->where($map)->update($map1);
    }
}
