<?php
namespace App\Http\Controllers\Admin;

use App\Model\Link;
use Illuminate\Http\Request;
use App\Utils\Constant;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LinkController extends AdminBaseController
{
    public $path = 'admin/link/link_';
    public $perPage = 10;

    public function lists(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $results =  Link::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $results;
        return view($this->path."list",$input);
    }

    public function show(Request $request)
    {
        $id =  $request->get("id");
        if(empty($id)){
            return view($this->path."index");
        }
        $link = Link::find($id);
        return view($this->path."index",array("link"=>$link));
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="GET"){
            return view($this->path."index");
        }
        if($request->getMethod()=="POST"){
            $validator = Validator::make($request->all(), [
                'name' => 'required',//名称(varchar)
                'href' => 'required',//链接(varchar)
                'email' => 'required',//站长邮箱(varchar)
                'type' => 'required',//类型:1-INDEX-首页,2-INNER-内页(int)
            ]);
            if ($validator->fails()) {
                return $this->returnJson(Constant::ERROR,$validator->errors()->first());
            }
            Link::create(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"新增成功!");
        }
    }
    public function edit(Request $request)
    {
       $id =  $request->get("id");
       if($request->getMethod()=="GET"){
            $link = Link::find($id);
            return view($this->path."index",array("link"=>$link));
       }
       if($request->getMethod()=="POST"){
         $validator = Validator::make($request->all(), [
                'name' => 'bail|required',//名称(varchar)
                'href' => 'bail|required',//链接(varchar)
                'email' => 'bail|required',//站长邮箱(varchar)
                'type' => 'bail|required',//类型:1-INDEX-首页,2-INNER-内页(int)
                      ]);
       if ($validator->fails()) {
            return $this->returnJson(Constant::ERROR,$validator->errors()->first());
       }
       Link::find($id)->update(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"修改成功!");
       }

    }


    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $obj = Link::destroy($id);
        if($obj){
            return $this->returnJson(Constant::SUCCESS,"删除成功!");
        }
        return $this->returnJson(Constant::ERROR,"删除失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = ['id','name','href','email','type','created_at','updated_at'];
        $columns_name = ['id','名称','链接','站长邮箱','类型','',''];
        $currentPage = $request->get("p",1);
        $lists =  Link::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
        $lists = $lists->toArray();
        Excel::create('Link-'.date("Ymdhmi"), function($excel) use($lists,$columns_name){
            $excel->sheet('sheet1', function($sheet) use($lists,$columns_name) {
                $sheet->fromArray($lists);
                $sheet->row(1,$columns_name);
            });

        })->download('xls');
        return redirect()->back()->withErrors(array("导出成功!"))->withInput();
    }
}
