<?php
namespace App\Utils;
use Illuminate\Support\Facades\Redirect;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/17 0017
 * Time: 下午 10:56
 */
class WebUtil
{
    public static function showError($url,$msg){
        \Session::put("error_url",$url);
        \Session::put("error_msg",$msg);
       return redirect('/error');
    }
}