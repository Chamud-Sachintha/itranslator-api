<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TranslatedDocuments extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'document',
        'create_time'
    ];

    public function add_log($docInfo) {
        $map['order_id'] = $docInfo['orderId'];
        $map['document'] = $docInfo['document'];
        $map['create_time'] = $docInfo['createTime'];

        return $this->create($map);
    }

    public function get_doc_list_by_invoice($orderId) {
        $map['order_id'] = $orderId;

        return $this->where($map)->get();
    }
}
