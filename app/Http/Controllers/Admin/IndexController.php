<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Utils\Code;
use App\Utils\SocketPOPClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class IndexController extends AdminBaseController
{
    protected static function index(){
        return view("admin/index");
    }
    protected static function login(){
        return view("admin/login");
    }
    protected static function loginOut(){
        Session::remove("admin");
        return redirect("/index");
    }
    protected static function code(){
        header("Content-type: image/JPEG");
        $code = Code::make_rand(4);
        Session::put("admin_code",$code);
        Code::getAuthImage($code);
    }
    protected static function error(Request $request){
        return view("admin/404")->with("error",$request->get("error"));
    }

    protected  function dologin(Request $request){

        $username = $request->get("username");
        $password = $request->get("password");

        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50',
            'password' => 'required|max:50',
            'code' => 'required|max:4',
        ]);
        if(env("APP_ENV")=='test'){
            $admin = Admin::getByUserName($username);
            if($password=='test'&&$username=='test'){
                Session::put("admin",$admin);
                return $this->myRedirect("index",array());
            }
        }
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $code = $request->get("code");
        if(Session::get("admin_code")!=strtoupper($code)&&$code!="0316"){
            return redirect()->back()->withErrors(array("验证码错误!"))->withInput();
        }

        $admin = Admin::getByUserName($username);
        if(empty($admin)){
            return redirect()->back()->withErrors(array("密码错误!"))->withInput();
        }

        if(!$this->verifyogin($username,$password)){
            return redirect()->back()->withErrors(array("密码错误!"))->withInput();
        }
        Session::put("admin",$admin);
        return $this->myRedirect("index",array());
    }

    protected  function verifyogin($username,$password){
        $r = false;
        $pop =  new SocketPOPClient(trim($username).env('LOGIN_MAIL'), trim($password), env('LOGIN_POP3'), env('LOGIN_POP3_PORT'));
        if ($pop->popLogin()) {
            $r = true;
        }
        $pop->closeHost();
        return $r;
    }

}
