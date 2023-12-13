<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubNotaryServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_category_id',
        'sub_category_name',
        'create_time'
    ];

    public function add_log($subCategoryInfo) {
        $map['main_category_id'] = $subCategoryInfo['mainCategoryId'];
        $map['sub_category_name'] = $subCategoryInfo['subCategoryName'];
        $map['create_time'] = $subCategoryInfo['createTime'];

        return $this->create($map);
    }

    public function find_by_name($categoryName) {
        $map['sub_category_name'] = $categoryName;

        return $this->where($map)->first();
    }

    public function find_all() {
        return $this->all();
    }
}
