<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'token',
        'login_time',
        'flag',
        'create_time'
    ];

    public function add_log($adminInfo) {
        $map['first_name'] = $adminInfo['firstName'];
        $map['last_name'] = $adminInfo['lastName'];
        $map['email'] = $adminInfo['emailAddress'];
        $map['password'] = Hash::make($adminInfo['password']);
        $map['flag'] = "A";
        $map['create_time'] = $adminInfo['createTime'];

        return $this->create($map);
    }

    public function validate_email($email) {
        $map['email'] = $email;

        return $this->where($map)->first();
    }

    public function find_all() {
        return $this->all();
    }
}
