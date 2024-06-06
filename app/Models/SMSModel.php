<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'client_id',
        'message_id',
        'code',
        'content',
        'create_time'
    ];

    public function add_log($smsData) {
        $map['client_id'] = $smsData['clientId'];
        $map['message_id'] = $smsData['messageId'];
        $map['code'] = $smsData['verifyCode'];
        $map['content'] = $smsData['content'];
        $map['create_time'] = $smsData['createTime'];

        return $this->create($map);
    }
}
