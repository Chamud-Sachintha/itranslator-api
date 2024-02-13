<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainNotaryServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'create_time'
    ];

    public function add_log($mainCategoryInfo) {
        $map['category_name'] = $mainCategoryInfo['categoryName'];
        $map['create_time'] = $mainCategoryInfo['createTime'];

        return $this->create($map);
    }

    public function find_by_id($mainCategoryId) {
        $map['id'] = $mainCategoryId;

        return $this->where($map)->first();
    }

    public function update_by_id($info) {
        $map['id'] = $info['categoryId'];
        $map1['category_name'] = $info['categoryName'];

        return $this->where($map)->update($map1);
    } 

    public function find_all() {
        return $this->all();
    }
}
