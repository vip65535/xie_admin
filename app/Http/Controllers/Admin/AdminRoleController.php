<?php
namespace App\Http\Controllers\Admin;

use App\Model\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminRoleController extends AdminBaseController
{
    public $path = 'admin/admin_role/admin_role_';
    public $perPage = 10;

    public function getList(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $links =  AdminRole::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $links;
        return view($this->path."list",$input);
    }

    public function index(Request $request)
    {
       $id =  $request->get("id");
       if(empty($id)){
           return view($this->path."index");
       }
        $adminRole = AdminRole::find($id);
        return view($this->path."index",array("adminRole"=>$adminRole));

    }

    public function add(Request $request)
    {
        $id = $request->get("id");
        $validator = Validator::make($request->all(), [
        'admin_id' => 'required|max:50',//管理员id(int)
        'role_id' => 'required|max:50',//角色id(int)
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if(empty($id)){
            AdminRole::create(Input::all());
            return redirect("/admin/adminRole/getList")->withErrors(array("添加成功!"));
        }else{
            AdminRole::find($id)->update(Input::all());
            return redirect("/admin/adminRole/getList")->withErrors(array("修改成功!"));
        }

    }

    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $obj = AdminRole::destroy($id);
        if($obj){
            return $this->returnJson(1,"成功!");
        }
        return $this->returnJson(0,"失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = ['id','admin_id','role_id','created_at','updated_at'];
        $columns_name = ['','管理员id','角色id','',''];
        $currentPage = $request->get("p",1);
        $lists =  AdminRole::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
        $lists = $lists->toArray();
        Excel::create('links', function($excel) use($lists,$columns_name){
            $excel->sheet('sheet1', function($sheet) use($lists,$columns_name) {
                $sheet->fromArray($lists);
                $sheet->row(1,$columns_name);
            });

        })->download('xls');
        return redirect()->back()->withErrors(array("导出成功!"))->withInput();
    }
}
