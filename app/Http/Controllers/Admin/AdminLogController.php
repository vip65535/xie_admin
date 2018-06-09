<?php
namespace App\Http\Controllers\Admin;

use App\Model\AdminLog;
use Illuminate\Http\Request;
use App\Utils\Constant;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminLogController extends AdminBaseController
{
    public $path = 'admin/admin_log/admin_log_';
    public $perPage = 10;

    public function lists(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $results =  AdminLog::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $results;
        return view($this->path."list",$input);
    }

    public function show(Request $request)
    {
        $id =  $request->get("id");
        if(empty($id)){
            return view($this->path."index");
        }
        $adminLog = AdminLog::find($id);
        return view($this->path."index",array("adminLog"=>$adminLog));
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="GET"){
            return view($this->path."index");
        }
        if($request->getMethod()=="POST"){
            $validator = Validator::make($request->all(), [
                'aid' => 'required',//管理员(int)
                'method' => 'required',//访问类型(varchar)
                'url' => 'required',//访问链接(varchar)
                'param' => 'required',//请求数据(longtext)
                'ip' => 'required',//IP地址(varchar)
            ]);
            if ($validator->fails()) {
                return $this->returnJson(Constant::ERROR,$validator->errors()->first());
            }
            AdminLog::create(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"新增成功!");
        }
    }
    public function edit(Request $request)
    {
       $id =  $request->get("id");
       if($request->getMethod()=="GET"){
            $adminLog = AdminLog::find($id);
            return view($this->path."index",array("adminLog"=>$adminLog));
       }
       if($request->getMethod()=="POST"){
         $validator = Validator::make($request->all(), [
                'aid' => 'bail|required',//管理员(int)
                'method' => 'bail|required',//访问类型(varchar)
                'url' => 'bail|required',//访问链接(varchar)
                'param' => 'bail|required',//请求数据(longtext)
                'ip' => 'bail|required',//IP地址(varchar)
                      ]);
       if ($validator->fails()) {
            return $this->returnJson(Constant::ERROR,$validator->errors()->first());
       }
       AdminLog::find($id)->update(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"修改成功!");
       }

    }


    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $obj = AdminLog::destroy($id);
        if($obj){
            return $this->returnJson(Constant::SUCCESS,"删除成功!");
        }
        return $this->returnJson(Constant::ERROR,"删除失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = ['id','aid','method','url','param','ip','created_at','updated_at'];
        $columns_name = ['id','管理员','访问类型','访问链接','请求数据','IP地址','',''];
        $currentPage = $request->get("p",1);
        $lists =  AdminLog::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
        $lists = $lists->toArray();
        Excel::create('AdminLog-'.date("Ymdhmi"), function($excel) use($lists,$columns_name){
            $excel->sheet('sheet1', function($sheet) use($lists,$columns_name) {
                $sheet->fromArray($lists);
                $sheet->row(1,$columns_name);
            });

        })->download('xls');
        return redirect()->back()->withErrors(array("导出成功!"))->withInput();
    }
}
