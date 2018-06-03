<?php

namespace App\Http\Middleware;

use App\Model\Functions;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminLogin
{
    protected $except = [
        '/login',
        '/code',
    ];
    public function handle($request, Closure $next, $guard = null)
    {
        if($this->inExceptArray($request)){
            return $next($request);
        }
        $method = $request->getMethod();
        $uri =$request->getRequestUri();
        $admin =Session::get("admin");
        if (empty(Session::get("admin"))) {
            return redirect('/login');
        }
        if($uri=='/'||$uri=='/login'){
            return redirect('/index');
        }
        $roles = Functions::getByAdminId($admin->id);
        if(in_array(explode("?",$uri)[0],array_column($roles,'href'))||$admin->id==1){
            return $next($request);
        }else{
            if($method=="GET"){
                return redirect('/404')->with("error","无权限操作！");
            }
            if($method=="POST"){

            }
        }
        return $next($request);
    }

    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            $except = "/".$except;
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
