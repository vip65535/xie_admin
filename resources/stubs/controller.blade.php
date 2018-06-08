@php
echo"<?php";
@endphp

namespace App\Http\Controllers\Admin;

use App\Model\{{$TableName}};
use Illuminate\Http\Request;
use App\Utils\Constant;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class {{$TableName}}Controller extends AdminBaseController
{
    public $path = 'admin/{{$table_name}}/{{$table_name}}_';
    public $perPage = 10;

    public function lists(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns = ['*'];
        $currentPage = $request->get("p",1);
        $results =  {{$TableName}}::getByList($columns,$currentPage,$perPage,$input,"id desc",true);
        $input['list'] = $results;
        return view($this->path."list",$input);
    }

    public function show(Request $request)
    {
        $id =  $request->get("id");
        if(empty($id)){
            return view($this->path."index");
        }
        ${{$tableName}} = {{$TableName}}::find($id);
        return view($this->path."index",array("{{$tableName}}"=>${{$tableName}}));
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="GET"){
            return view($this->path."index");
        }
        if($request->getMethod()=="POST"){
            $validator = Validator::make($request->all(), [
@foreach($colums as $colum)
@if($colum['column']!="id"&&$colum['column']!="created_at"&&$colum['column']!="updated_at")
                '{{$colum['column']}}' => 'required',//{{$colum['column_comment']}}({{$colum['type']}})
@endif
@endforeach
            ]);
            if ($validator->fails()) {
                return $this->returnJson(Constant::ERROR,$validator->errors()->first());
            }
            {{$TableName}}::create(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"新增成功!");
        }
    }
    public function edit(Request $request)
    {
       $id =  $request->get("id");
       if($request->getMethod()=="GET"){
            ${{$tableName}} = {{$TableName}}::find($id);
            return view($this->path."index",array("{{$tableName}}"=>${{$tableName}}));
       }
       if($request->getMethod()=="POST"){
         $validator = Validator::make($request->all(), [
@foreach($colums as $colum)
    @if($colum['column']!="id"&&$colum['column']!="created_at"&&$colum['column']!="updated_at")
        '{{$colum['column']}}' => 'bail|required',//{{$colum['column_comment']}}({{$colum['type']}})
    @endif
@endforeach
          ]);
       if ($validator->fails()) {
            return $this->returnJson(Constant::ERROR,$validator->errors()->first());
       }
       {{$TableName}}::find($id)->update(array_filter($request->all()));
            return $this->returnJson(Constant::SUCCESS,"修改成功!");
       }

    }


    public function delete(Request $request)
    {
        $id=  $request->get("id");
        $obj = {{$TableName}}::destroy($id);
        if($obj){
            return $this->returnJson(1,"成功!");
        }
        return $this->returnJson(0,"失败!");
    }

    public function export(Request $request)
    {
        $input = Input::all();
        $perPage =$this->perPage ;
        $columns      = [@php echo"'".str_replace(",","','",$colum_str)."'"; @endphp];
        $columns_name = [@php echo"'".str_replace(",","','",$colum_comment_str)."'"; @endphp];
        $currentPage = $request->get("p",1);
        $lists =  {{$TableName}}::getByList($columns,$currentPage,$perPage,$input,"id desc",false);
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
