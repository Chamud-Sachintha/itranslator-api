<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\MainNotaryServiceCategory;
use Illuminate\Http\Request;

class MainNotaryServiceCategoryController extends Controller
{
    private $AppHelper;
    private $MainNotaryServiceCategory;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->MainNotaryServiceCategory = new MainNotaryServiceCategory();
    }

    public function addNewMainCategory(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $categoryName = (is_null($request->categoryName) || empty($request->categoryName)) ? "" : $request->categoryName;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flage is required");
        } else if ($categoryName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Category name is required.");
        } else {

            try {
                $mainCategoryInfo = array();
                $mainCategoryInfo['categoryName'] = $categoryName;
                $mainCategoryInfo['createTime'] = $this->AppHelper->get_date_and_time();

                $mainCategory = $this->MainNotaryServiceCategory->add_log($mainCategoryInfo);

                if ($mainCategory) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getAllMainNotaryServiceCategoryList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $allMainCategories = $this->MainNotaryServiceCategory->find_all();

                $categoryList = array();
                foreach ($allMainCategories as $key => $value) {
                    $categoryList[$key]['id'] = $value['id'];
                    $categoryList[$key]['categoryName'] = $value['category_name'];
                    $categoryList[$key]['createTime'] = $value['create_time'];
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $categoryList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
