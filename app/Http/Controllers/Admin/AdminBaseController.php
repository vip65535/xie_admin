<?php

namespace App\Http\Controllers\Admin;

use App\Model\AdminSys;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;

class AdminBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected  function returnJson($code,$msg='',$data=[]){
        return response()->json(["code"=>$code,"m"=>$msg,"data"=>$data]);
    }

    protected  function getAdmin(){
        return Session::get("admin");
    }
    protected  function getAdminId(){
        return $this->getAdmin()->id;
    }

    protected  function myRedirect($path,$array=[]){
        return redirect("/".$path)->withErrors($array);
    }

    public function __construct()
    {
    }
}
