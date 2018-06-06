<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminLog
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (!empty(Session::get("admin"))) {
            $admin = Session::get("admin");
            if('/adminLog/lists'==$request->getPathInfo()){
                return $next($request);
            }
            \App\Model\AdminLog::create(array(
                "aid"=>$admin->id,
                "method"=>$request->getMethod(),
                "url"=>$request->getPathInfo(),
                "param"=>json_encode($request->all(),JSON_UNESCAPED_UNICODE),
            ));
            return $next($request);
        }

        return $next($request);
    }

}
