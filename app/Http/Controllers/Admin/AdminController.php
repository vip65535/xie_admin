<?php
namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\AdminRole;
use App\Model\Constant;
use App\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends AdminBaseController
{
    public $path = 'admin/admin/admin_';
    public $perPage = 10;

    public function lists(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $rows =  Admin::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        foreach ($rows as $row){
            $row->roles =  implode("、",array_column(Role::getByAdminId($row->id),'name'));
        }
        $input['list'] = $rows;
        return view($this->path."list",$input);
    }

    public function index(Request $request)
    {
       $id =  $request->get("id");
       if(empty($id)){
           return view($this->path."index");
       }
        $admin = Admin::find($id);
        return view($this->path."index",array("admin"=>$admin));

    }

    public function add(Request $request)
    {
        $id = $request->get("id");
        $validator = Validator::make($request->all(), [
        'user_name' => 'required|max:50',//用户名(varchar)
        'status' => 'required|max:50',//状态值:1-normal-正常,2-delete-删除(int)
        'sex' => 'required|max:50',//性别:1-man-男,2-women-女(int)
        ]);
        if ($validator->fails()) {
            return $this->returnJson(Constant::ERROR,$validator->errors()->first());
        }

        $param = Input::all();
        $roles = $request->get("role",[]);
        unset($param["role"]);
        if(empty($id)){
            $count =Admin::where("user_name",$request->get("user_name"))->count();
            if($count>0){
                return $this->returnJson(Constant::ERROR,"用户已存在！");
            }
            $row =Admin::create($param);
            $this->addRole($row->id,$roles);
            return $this->returnJson(Constant::SUCCESS,"新增成功!");
        }else{
            Admin::find($id)->update($param);
            $this->addRole($id,$roles);
            return $this->returnJson(Constant::SUCCESS,"修改成功!");
        }

    }
    public function addRole($id,$roles=array()){
        AdminRole::deleteByAdminId($id);
        foreach ($roles as $role){
            $adminRole = new AdminRole;
            $adminRole->admin_id=$id;
            $adminRole->role_id=$role;
            $adminRole->save();
        }
    }

    public function delete(Request $request)
    {
        $id=  $request->get("id");
        DB::beginTransaction();
        try{
            $obj = Admin::destroy($id);
            AdminRole::where("admin_id",$id)->delete();
            DB::commit();
            if($obj){
                return $this->returnJson(1,"成功!");
            }

        } catch (\Exception $e){
            DB::rollback();//事务回滚
            Log::info($e->getMessage());
            echo $e->getCode();
            return $this->returnJson(0,"失败!");
            exit;
        }

        return $this->returnJson(0,"失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = ['id','user_name','password','status','sex','created_at','updated_at'];
        $columns_name = ['id','用户名','密码','状态值','性别','创建时间','修改时间'];
        $currentPage = $request->get("p",1);
        $lists =  Admin::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
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
