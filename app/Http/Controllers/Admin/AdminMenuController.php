<?php
namespace App\Http\Controllers\Admin;

use App\Model\AdminMenu;
use App\Model\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminMenuController extends AdminBaseController
{
    public $path = 'admin/admin_menu/admin_menu_';
    public $perPage = 10;

    public function getList(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $links =  AdminMenu::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $links;
        return view($this->path."list",$input);
    }

    public function getAllJson(Request $request)
    {
        $array = array();
        $list =  AdminMenu::all();
        $list= $list->toArray();
        $roels = AdminRole::getMyRoles($this->getAdminId());
        foreach ($list as $k=>&$v){
            $v['pId']=$v['pid'];
            $v['open']=true;
        }
        return $this->returnJson(1,"成功!",array("tree"=>$list,"my_role_ids"=>array_column($roels->toArray(),"role_id")));
    }

    public function index(Request $request)
    {
       $id =  $request->get("id");
       if(empty($id)){
           return view($this->path."index");
       }
        $adminMenu = AdminMenu::find($id);
        return view($this->path."index",array("adminMenu"=>$adminMenu));

    }

    public function add(Request $request)
    {
        $id = $request->get("id");
        $validator = Validator::make($request->all(), [
        'sort' => 'required|max:50',//排序(int)
        'pid' => 'required|max:50',//父节点(int)
        'name' => 'required|max:50',//功能名称(varchar)
        'icon_class' => 'required|max:50',//图标(varchar)
        'href' => 'required|max:50',//链接(varchar)
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if(empty($id)){
            AdminMenu::create(Input::all());
            return $this->returnJson(1,"添加成功!");
        }else{
            AdminMenu::find($id)->update(Input::all());
            return $this->returnJson(1,"修改成功!");
        }

    }

    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $count =  AdminMenu::where("pid",$id)->count();
        if($count>0){
            return $this->returnJson(0,"请先删除该节点下的子节点!");
        }
        $obj = AdminMenu::destroy($id);
        if($obj){
            return $this->returnJson(1,"成功!");
        }
        return $this->returnJson(0,"失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = ['id','sort','pid','name','icon','href','created_at','updated_at'];
        $columns_name = ['id','排序','父节点','功能名称','图标','链接','',''];
        $currentPage = $request->get("p",1);
        $lists =  AdminMenu::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
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
