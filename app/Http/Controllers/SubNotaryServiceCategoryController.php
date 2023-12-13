<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\MainNotaryServiceCategory;
use App\Models\SubNotaryServiceCategory;
use Illuminate\Http\Request;

class SubNotaryServiceCategoryController extends Controller
{
    private $AppHelper;
    private $MainNotaryServiceCategory;
    private $SubNotaryServiceCategory;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->MainNotaryServiceCategory = new MainNotaryServiceCategory();
        $this->SubNotaryServiceCategory = new SubNotaryServiceCategory();
    }

    public function addNewSubNotaryServiceCategory(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $mainCategoryId = (is_null($request->mainCategoryId) || empty($request->mainCategoryId)) ? "" : $request->mainCategoryId;
        $subCategoryName = (is_null($request->subCategoryName) || empty($request->subCategoryName)) ? "" : $request->subCategoryName;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($mainCategoryId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Main Category is required.");
        } else if ($subCategoryName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Category Name is required.");
        } else {

            try {

                $validateSubCategory = $this->SubNotaryServiceCategory->find_by_name($subCategoryName);

                if (!empty($validateSubCategory)) {
                    return $this->AppHelper->responseMessageHandle(0, "Already Added.");
                }

                if ($this->validateMainCategory($mainCategoryId)) {
                    $subCategoryInfo = array();
                    $subCategoryInfo['mainCategoryId'] = $mainCategoryId;
                    $subCategoryInfo['subCategoryName'] = $subCategoryName;
                    $subCategoryInfo['createTime'] = $this->AppHelper->get_date_and_time();

                    $subCategory = $this->SubNotaryServiceCategory->add_log($subCategoryInfo);

                    if ($subCategory) {
                        return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                    } else {
                        return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                    }
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Main Category ID");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getAllSubNotaryCategoryList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    private function validateMainCategory($mainCategoryId) {

        $isValidMainCategory = false;

        try {
            $mainCategory = $this->MainNotaryServiceCategory->find_by_id($mainCategoryId);

            if ($mainCategory) {
                $isValidMainCategory = true;
            }
        } catch (\Exception $e) {
            return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
        }

        return $isValidMainCategory;
    }
}
