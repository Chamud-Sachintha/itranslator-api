<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    use HasFactory;

    protected $fillable=  [
        'full_name',
        'email',
        'mobile_number',
        'token',
        'login_time',
        'create_time',
        'flag'
    ];

    public function add_log($info) {
        $map['full_name'] = $info['fullName'];
        $map['email'] = $info['email'];
        $map['mobile_number'] = $info['mobileNumber'];
        $map['create_time'] = $info['createTime'];
        $map['flag'] = "SA";

        return $this->create($map);
    }

    public function find_by_token($token) {
        $map['token'] = $token;

        return $this->where($map)->first();
    }

    public function verify_email($email) {
        $map['email'] = $email;

        return $this->where($map)->first();
    }

    public function update_login_token($uid, $tokenInfo) {
        $map['token'] = $tokenInfo['token'];
        $map['login_time'] = $tokenInfo['loginTime'];

        return $this->where(array('id' => $uid))->update($map);
    }

    public function query_all() {
        return $this->all();
    }

    public function check_permission($token, $flag) {
        $map['flag'] = $flag;
        $map['token'] = $token;

        return $this->where($map)->first();
    }
}
