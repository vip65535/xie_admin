<?php
namespace App\Http\Controllers\Admin;

use App\Model\Students;
use Illuminate\Http\Request;
use App\Utils\Constant;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentsController extends AdminBaseController
{
    public $path = 'admin/students/students_';
    public $perPage = 10;

    public function lists(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $results =  Students::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $results;
        return view($this->path."list",$input);
    }

    public function show(Request $request)
    {
        $id =  $request->get("id");
        if(empty($id)){
            return view($this->path."index");
        }
        $students = Students::find($id);
        return view($this->path."index",array("students"=>$students));
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="GET"){
            return view($this->path."index");
        }
        if($request->getMethod()=="POST"){
            $validator = Validator::make($request->all(), [
                'name' => 'required',//姓名(varchar)
                'grade' => 'required',//年级(varchar)
            ]);
            if ($validator->fails()) {
                return $this->returnJson(Constant::ERROR,$validator->errors()->first());
            }
            Students::create(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"新增成功!");
        }
    }
    public function edit(Request $request)
    {
       $id =  $request->get("id");
       if($request->getMethod()=="GET"){
            $students = Students::find($id);
            return view($this->path."index",array("students"=>$students));
       }
       if($request->getMethod()=="POST"){
         $validator = Validator::make($request->all(), [
                'name' => 'bail|required',//姓名(varchar)
                'grade' => 'bail|required',//年级(varchar)
              ]);
       if ($validator->fails()) {
            return $this->returnJson(Constant::ERROR,$validator->errors()->first());
       }
       Students::find($id)->update(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"修改成功!");
       }

    }


    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $obj = Students::destroy($id);
        if($obj){
            return $this->returnJson(Constant::SUCCESS,"删除成功!");
        }
        return $this->returnJson(Constant::ERROR,"删除失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = ['id','name','grade'];
        $columns_name = ['','姓名','年级'];
        $currentPage = $request->get("p",1);
        $lists =  Students::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
        $lists = $lists->toArray();
        Excel::create('Students-'.date("Ymdhmi"), function($excel) use($lists,$columns_name){
            $excel->sheet('sheet1', function($sheet) use($lists,$columns_name) {
                $sheet->fromArray($lists);
                $sheet->row(1,$columns_name);
            });

        })->download('xls');
        return redirect()->back()->withErrors(array("导出成功!"))->withInput();
    }
}
