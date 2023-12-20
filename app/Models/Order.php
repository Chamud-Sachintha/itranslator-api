<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /*
        'payment_status'                     0- pending 1- paid
        'order_status',                      0- pending 1- taken 2- processing 3- complete
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
        'bank_slip',
        'delivery_time_type',
        'delivery_method',
        'payment_type',
        'total_amount',
        'create_time',
    ];

    public function find_all_pending() {
        $map['order_status'] = 0;
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

    public function find_by_invoice($invoiceNo) {
        $map['invoice_no'] = $invoiceNo;

        return $this->where($map)->first();
    }
}
