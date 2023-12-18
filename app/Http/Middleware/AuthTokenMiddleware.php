<?php

namespace App\Http\Middleware;

use App\Helpers\AppHelper;
use App\Models\Admin;
use App\Models\AdminUser;
use App\Models\Client;
use App\Models\SuperAdmin;
use Closure;
use Illuminate\Http\Request;

class AuthTokenMiddleware
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

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ((is_null($request->token) || empty($request->token)) && (is_null($request->flag) || empty($request->flag))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {

            $user = null;

            if ($request->flag == "SA") {
                $user = $this->SuperAdmin->find_by_token($request->token);
            } else if ($request->flag == "A") {
                $user = $this->AdminUser->find_by_token($request->token);
            } else if ($request->flag == "C") {
                $user = $this->Client->find_by_token($request->token);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $yesterday = $this->AppHelper->day_time() - 86400;

            if (empty($user) || ($user['login_time'] < $yesterday)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        return $next($request);
    }
}
