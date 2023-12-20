<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'price_1',
        'price_2',
        'price_3',
        'description',
        'create_time'
    ];

    public function add_log($info) {
        $map['service_name'] = $info['serviceName'];
        $map['price_1'] = $info['firstPrice'];
        $map['price_2'] = $info['secondPrice'];
        $map['price_3'] = $info['thirdPrice'];
        $map['description'] = $info['description'];
        $map['create_time'] = $info['createTime'];

        return $this->create($map);
    }

    public function query_all() {
        return $this->all();
    }

    public function find_by_id($sid) {
        $map['id'] = $sid;
        return $this->where($map)->first();
    }
}
