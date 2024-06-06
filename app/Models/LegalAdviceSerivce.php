<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalAdviceSerivce extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'filename',
        'order_status',
        'message',
        'sent_from',
        'sent_to'
        
       
    ];

    

    public function submit_msg_Details($dataList){

        $map['order_no'] = $dataList['OrderNo'];
        $map['message'] = $dataList['Message'];
        $map['sent_from'] = $dataList['Adminid'];
        $map['sent_to'] = $dataList['Client_ID'];
        $map['filename'] = "[]";
        
       // DD($LegalMessage);
        return $this->create($map);


    }

    public function submit_Lmsg_Details($dataList){
        $map['order_no'] = $dataList['OrderNo'];
        $map['message'] = $dataList['Message'];
        $map['sent_from'] = $dataList['Adminid'];
        $map['sent_to'] = $dataList['Client_ID'];
        if(!empty($dataList['filename']))
        {
            $map['filename'] = $dataList['filename'];
        }
        else
        {
            $map['filename'] = "[]";
        }
       
        
        //DD($dataList);
        return $this->create($map);
    }

    public function Get_All_Task_Details($id){
        $query = $this->where('sent_from', $id)
        ->where('order_status', '=', 0)
        ->distinct()
        ->pluck('order_no');

            return $query;
    }

    public function Get_message_Details($OrderNo)
{
    $query = $this->where('order_no', $OrderNo)
                  ->where('order_status', '=', 0)
                  ->orderBy('id')
                  ->get();

    return $query;
}

    public function Get_Doc_Details($OrderNo){

        $query = $this->where('order_no', $OrderNo)->pluck('filename');

        return $query;
    }

    
}
