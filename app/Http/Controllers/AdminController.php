<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private $AdminUser;
    private $AppHelper;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->AdminUser = new AdminUser();
    }

    public function createAdminUser(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;
        $firstName = (is_null($request->firstName) || empty($request->firstName)) ? "" : $request->firstName;
        $lastName = (is_null($request->lastName) || empty($request->lastName)) ? "" : $request->lastName;
        $emailAddress = (is_null($request->eemailAddress) || empty($request->emailAddress)) ? "" : $request->emailAddress;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else if ($firstName == "") {
            return $this->AppHelper->responseMessageHandle(0, "First Name is required.");
        } else if ($lastName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Last Nmae is required.");
        } else if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email Adress is required.");
        } else {

            try {

                $validateAdmin = $this->AdminUser->validate_email($emailAddress);

                if ($validateAdmin) {
                    return $this->AppHelper->responseMessageHandle(0, "Already Registred Admin User");
                }

                $adminUserInfo = array();
                $adminUserInfo['firstName'] = $firstName;
                $adminUserInfo['lastName'] = $lastName;
                $adminUserInfo['emailAddress'] = $emailAddress;
                $adminUserInfo['password'] = Hash::make(123);

                $resp = $this->AdminUser->add_log($adminUserInfo);

                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occured.");
                }

            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getAdminUserList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {

        } else if ($flag == "") {

        } else {
            try {

            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function updateAdminUserDetails(Request $request) {

    }
}
