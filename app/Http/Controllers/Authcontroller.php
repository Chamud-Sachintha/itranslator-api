<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Admin;
use App\Models\AdminUser;
use App\Models\Client;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Authcontroller extends Controller
{
    private $SuperAdmin;
    private $AdminUser;
    private $Client;
    private $AppHelper;

    public function __construct()
    {
        $this->SuperAdmin = new SuperAdmin();
        $this->AdminUser = new AdminUser();
        $this->Client = new Client();
        $this->AppHelper = new AppHelper();
    }

    public function checkMenuPermission(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is required.");
        } else {

            try {
                $user = null;

                if ($flag == "SA") {
                    $user = $this->SuperAdmin->check_permission($request_token, $flag);
                } else if ($flag == "A") {
                    $user = $this->AdminUser->check_permission($request_token, $flag);
                } else if ($flag == "C") {
                    $user = $this->Client->check_permission($request_token, $flag);
                } else {

                }

                if (!empty($user)) {
                    return $this->AppHelper->responseMessageHandle(1, "Permission Granted.");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Permission Not Granted.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function createNewClient(Request $request) {

        $fullname = (is_null($request->fullName) || empty($request->fullName)) ? "" : $request->fullName;
        $emailAddress = (is_null($request->emailAddress) || empty($request->emailAddress)) ? "" : $request->emailAddress;
        $nicNumber = (is_null($request->nicNumber) || empty($request->nicNumber)) ? "" : $request->nicNumber;
        $address = (is_null($request->address) || empty($request->address)) ? "" : $request->address;
        $mobileNumber = (is_null($request->mobileNumber) || empty($request->mobileNumber)) ? "" : $request->mobileNumber;
        $birthDate = (is_null($request->birthDate) || empty($request->birthDate)) ? "" : $request->birthDate;
        $password = (is_null($request->password) || empty($request->password)) ? "" : $request->password;

        if ($fullname == "") {
            return $this->AppHelper->responseMessageHandle(0, "Full Name is required.");
        } else if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email Address is required.");
        } else if ($nicNumber == "") {
            return $this->AppHelper->responseMessageHandle(0, "NIC Number is required.");
        } else if ($address == "") {
            return $this->AppHelper->responseMessageHandle(0, "Address is required.");
        } else if ($mobileNumber == "") {
            return $this->AppHelper->responseMessageHandle(0, "Mobile Number is required.");
        } else if ($birthDate == "") {
            return $this->AppHelper->responseMessageHandle(0, "Birth Date is required.");
        } else if ($password == "") {
            return $this->AppHelper->responseMessageHandle(0, "Password is required.");
        } else {

            try {

                $checkEmail = $this->Client->verify_email($emailAddress);

                if (!empty($checkEmail)) {
                    return $this->AppHelper->responseMessageHandle(0, "Email Already Exist.");
                }

                $clientInfo = array();
                $clientInfo['fullName'] = $fullname;
                $clientInfo['emailAddress'] = $emailAddress;
                $clientInfo['nicNumber'] = $nicNumber;
                $clientInfo['address'] = $address;
                $clientInfo['mobileNumber'] = $mobileNumber;
                $clientInfo['birthDate'] = strtotime($birthDate);
                $clientInfo['password'] = Hash::make($password);
                $clientInfo['createTime'] = $this->AppHelper->get_date_and_time();

                $client = $this->Client->add_log($clientInfo);

                if ($client) {
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $client);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Error Occured");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function authenticateUser(Request $request) {

        $username = (is_null($request->username) || empty($request->username)) ? "" : $request->username;
        $password = (is_null($request->password) || empty($request->password)) ? "" : $request->password;
        $flag = (is_null($request->flag) || empty($request->flag)) ? "" : $request->flag;

        if ($username == "") {
            return $this->AppHelper->responseMessageHandle(0, "Username is required.");
        } else if ($password == "") {
            return $this->AppHelper->responseMessageHandle(0, "Password is required.");
        } else if ($flag == "") {
            return $this->AppHelper->responseMessageHandle(0, "Flag is Required.");
        } else {

            try {
                $authInfo = array();
                $authenticateUser = null;

                $authInfo['userName'] = $username;
                $authInfo['password'] = $password;

                if ($flag == "SA") {
                    $authenticateUser = $this->authenticateSuperAdmin($authInfo);
                } else if ($flag == "A") {
                    $authenticateUser = $this->authenticateAdmin($authInfo);
                } else if ($flag == "C") {
                    $authenticateUser = $this->authenticateClient($authInfo);
                } else {

                }

                return $authenticateUser;
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    private function authenticateSuperAdmin($authInfo) {
        $loginInfo = array();
        $verify_username = $this->SuperAdmin->verify_email($authInfo['userName']);

        if (!empty($verify_username)) {
            if (Hash::check($authInfo['password'], $verify_username['password'])) {
                $loginInfo['id'] = $verify_username['id'];
                $loginInfo['fullName'] = $verify_username['full_name'];
                $loginInfo['email'] = $verify_username['email'];

                $token = $this->AppHelper->generateAuthToken($verify_username);

                $loginInfo['userRole'] = $verify_username['flag'];

                $tokenInfo = array();
                $tokenInfo['token'] = $token;
                $tokenInfo['loginTime'] = $this->AppHelper->day_time();
                $token_time = $this->SuperAdmin->update_login_token($verify_username['id'], $tokenInfo);

                return $this->AppHelper->responseEntityHandle(1, "Operation Successfully.", $loginInfo, $token);
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Invalid Username or Password");
            }
        } else {
            return $this->AppHelper->responseMessageHandle(0, "Invalid Username or Password");
        }
    }

    private function authenticateAdmin($authInfo) {
        $loginInfo = array();
        $verify_username = $this->AdminUser->validate_email($authInfo['userName']);

        if (!empty($verify_username)) {
            if (Hash::check($authInfo['password'], $verify_username['password'])) {
                $loginInfo['id'] = $verify_username['id'];
                $loginInfo['fullName'] = $verify_username['full_name'];
                $loginInfo['email'] = $verify_username['email'];

                $token = $this->AppHelper->generateAuthToken($verify_username);

                $loginInfo['userRole'] = $verify_username['flag'];

                $tokenInfo = array();
                $tokenInfo['token'] = $token;
                $tokenInfo['loginTime'] = $this->AppHelper->day_time();
                $token_time = $this->AdminUser->update_login_token($verify_username['id'], $tokenInfo);

                return $this->AppHelper->responseEntityHandle(1, "Operation Successfully.", $loginInfo, $token);
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Invalid Username or Password");
            }
        } else {
            return $this->AppHelper->responseMessageHandle(0, "Invalid Username or Password");
        }
    }

    private function authenticateClient($authInfo) {
        $loginInfo = array();
        $verify_username = $this->Client->verify_email($authInfo['userName']);

        if (!empty($verify_username)) {
            if (Hash::check($authInfo['password'], $verify_username['password'])) {
                $loginInfo['id'] = $verify_username['id'];
                $loginInfo['fullName'] = $verify_username['full_name'];
                $loginInfo['email'] = $verify_username['email'];

                $token = $this->AppHelper->generateAuthToken($verify_username);

                $loginInfo['userRole'] = $verify_username['flag'];

                $tokenInfo = array();
                $tokenInfo['token'] = $token;
                $tokenInfo['loginTime'] = $this->AppHelper->day_time();
                $token_time = $this->Client->update_login_token($verify_username['id'], $tokenInfo);

                return $this->AppHelper->responseEntityHandle(1, "Operation Successfully.", $loginInfo, $token);
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Invalid Username or Password");
            }
        } else {
            return $this->AppHelper->responseMessageHandle(0, "Invalid Username or Password");
        }
    }
}
