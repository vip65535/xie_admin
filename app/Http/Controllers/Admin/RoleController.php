<?php
namespace App\Http\Controllers\Admin;

use App\Model\AdminRole;
use App\Model\Role;
use App\Model\RoleFunctions;
use App\Utils\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends AdminBaseController
{
    public $path = 'admin/role/role_';
    public $perPage = 10;

    public function lists(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $links =  Role::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $links;
        return view($this->path."list",$input);
    }

    public function index(Request $request)
    {
       $id =  $request->get("id");
       if(empty($id)){
           return view($this->path."index");
       }
        $role = Role::find($id);
        return view($this->path."index",array("role"=>$role));

    }

    public function add(Request $request)
    {
        $id = $request->get("id");
        $data = $request->get("data");
        $validator = Validator::make($request->all(), [
        'name' => 'required|max:50',//角色名称(varchar)
        ]);
        $param = Input::all();
        unset($param["data"]);
        if ($validator->fails()) {
            return $this->returnJson(Constant::ERROR,"角色名称不能为空!");
        }
        if(empty($id)){
            Role::create($param);
            $this->addFunctions($data,$id);
            return $this->returnJson(Constant::SUCCESS,"添加成功!");
        }else{
            Role::find($id)->update($param);
            RoleFunctions::deleteByRoleId($id);
            $this->addFunctions($data,$id);
            return $this->returnJson(Constant::SUCCESS,"修改成功!");
        }

    }

    public function addFunctions($data,$role_id){
        foreach ($data as $function){
            $roleFunctions = new RoleFunctions();
            $roleFunctions->functions_id=$function['id'];
            $roleFunctions->role_id = $role_id;
            $roleFunctions->save();
        }
    }

    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $obj = Role::destroy($id);
        if($obj){
            return $this->returnJson(Constant::SUCCESS,"成功!");
        }
        return $this->returnJson(Constant::ERROR,"失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = ['id','name','created_at','updated_at'];
        $columns_name = ['id','角色名称','',''];
        $currentPage = $request->get("p",1);
        $lists =  Role::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
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
