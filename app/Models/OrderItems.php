<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'service_id',
        'json_value',
        'create_time',
        'modified_time'
    ];

    public function get_by_orderId_and_serviceId($oid) {
        $map['order_id'] = $oid;

        return $this->where($map)->get();
    }
}
