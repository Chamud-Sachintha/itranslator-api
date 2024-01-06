<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /*
        'payment_status'                     0- pending 1- paid 2 - gateway pending
        'order_status',                      0- pending 1- taken 2- processing 3- complete
        'is_customer_complete'               0- pending 1- complete
        'bank_slip',                         if not it will blank
        'delivery_time_type',                
        'delivery_method',
        'payment_type',                      1- Bank Deposit 2- online payment
    */

    protected $fillable = [
        'invoice_no',
        'client_id',
        'payment_status', 
        'order_status',
        'is_customer_complete',
        'bank_slip',
        'delivery_time_type',
        'delivery_method',
        'payment_type',
        'total_amount',
        'create_time',
    ];

    public function find_all_pending() {
        $map['order_status'] = 0;
        $map['payment_status'] != 2;
        
        return $this->where($map)->get();
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

    public function get_by_id($oid) {
        $map['id'] = $oid;

        return $this->where($map)->first();
    }

    public function find_by_invoice($invoiceNo) {
        $map['invoice_no'] = $invoiceNo;

        return $this->where($map)->first();
    }
}
