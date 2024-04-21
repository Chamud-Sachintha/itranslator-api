<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminOrderAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'admin_id',
        'create_time'
    ];
//fixed
    public function add_log($orderInfo) {
        $map['invoice_no'] = $orderInfo['invoiceNo'];
        $map['admin_id'] = $orderInfo['adminId'];
        $map['create_time'] = $orderInfo['createTime'];

        return $this->create($map);
    }

    public function get_assigned_count() {
        return $this->all();
    }

    public function get_by_invoice_id($invoiceId) {
        $map['invoice_no'] = $invoiceId;

        return $this->where($map)->first();
    }
}
