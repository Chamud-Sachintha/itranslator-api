<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'nic_number',
        'address',
        'mobile_number',
        'bdate',
        'password',
        'token',
        'login_time',
        'create_time',
        'flag'
    ];

    public function add_log($info) {
        $map['full_name'] = $info['fullName'];
        $map['email'] = $info['emailAddress'];
        $map['nic_number'] = $info['nicNumber'];
        $map['address'] = $info['address'];
        $map['mobile_number'] = $info['mobileNumber'];
        $map['bdate'] = $info['birthDate'];
        $map['password'] = $info['password'];
        $map['create_time'] = $info['createTime'];
        $map['flag'] = "C";

        return $this->create($map);
    }

    public function check_permission($token, $flag) {
        $map['flag'] = $flag;
        $map['token'] = $token;

        return $this->where($map)->first();
    }

    public function update_login_token($uid, $tokenInfo) {
        $map['token'] = $tokenInfo['token'];
        $map['login_time'] = $tokenInfo['loginTime'];

        return $this->where(array('id' => $uid))->update($map);
    }

    public function verify_email($email) {
        $map['email'] = $email;

        return $this->where($map)->first();
    }

    public function find_by_token($token) {
        $map['token'] = $token;

        return $this->where($map)->first();
    }

    public function get_by_id($uid) {
        $map['id'] = $uid;

        return $this->where($map)->first();
    }
}
