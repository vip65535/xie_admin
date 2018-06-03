<?php
namespace App\Http\Controllers\Admin;

use App\Model\AdminRole;
use App\Model\Functions;
use App\Model\RoleFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class FunctionsController extends AdminBaseController
{
    public $path = 'admin/functions/functions_';
    public $perPage = 10;

    public function getList(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $links =  Functions::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $links;
        return view($this->path."list",$input);
    }

    public function getAllJson(Request $request)
    {
        $rdata = array();
        $functions_ids = array();
        $list =  Functions::orderBy("sort","desc")->get();
        $list= $list->toArray();
        $roleId = $request->get("role_id");
        $functions = RoleFunctions::getByRoleId($roleId);
        if(count($functions)>0){
            $functions_ids =  array_column($functions->toArray(),"functions_id");
        }
        foreach ($list as $k=>&$v){
            $v['pId']=$v['pid'];
            $v['open']=false;
        }
        $rdata = array("tree"=>$list,"functions_ids"=>$functions_ids);
        return $this->returnJson(1,"成功!",$rdata);
    }

    public function index(Request $request)
    {
        $id =  $request->get("id");
        if(empty($id)){
            return view($this->path."index");
        }
        $Functions = Functions::find($id);
        return view($this->path."index",array("Functions"=>$Functions));

    }

    public function add(Request $request)
    {
        $param = array();
        $id = $request->get("id","");
        if ($request->filled('sort')) {
            $param['sort']= $request->get("sort");
        }
        if ($request->filled('pid')) {
            $param['pid']= $request->get("pid");
        }
        if ($request->filled('name')) {
            $param['name']= $request->get("name");
        }
        if ($request->filled('type')) {
            $param['type']= $request->get("type");
        }
        if ($request->filled('icon_class')) {
            $param['icon_class']= $request->get("icon_class");
        }
        if ($request->filled('href')) {
            $param['href']= $request->get("href");
        }

        $validator = Validator::make($request->all(), [
            'sort' => 'required|max:50',//排序(int)
            'pid' => 'required|max:50',//父节点(int)
            'name' => 'required|max:50',//功能名称(varchar)
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if(empty($id)){
            Functions::create($param);
            return $this->returnJson(1,"添加成功!");
        }else{
            Functions::find($id)->update($param);
            return $this->returnJson(1,"修改成功!");
        }

    }

    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $count =  Functions::where("pid",$id)->count();
        if($count>0){
            return $this->returnJson(0,"请先删除该节点下的子节点!");
        }
        $obj = Functions::destroy($id);
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
        $lists =  Functions::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
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
