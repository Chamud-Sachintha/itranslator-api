<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sent_from',
        'sent_to',
        'message',
        'create_time'
    ];

    public function add_log($messageInfo) {
        $map['order_id'] = $messageInfo['orderId'];
        $map['sent_from'] = $messageInfo['sentFrom'];
        $map['sent_to'] = $messageInfo['sentTo'];
        $map['message'] = $messageInfo['message'];
        $map['create_time'] = $messageInfo['createTime'];

        return $this->create($map);
    }
}
