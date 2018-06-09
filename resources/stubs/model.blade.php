@php
echo"<?php
";
@endphp
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class {{$TableName}} extends BaseModel
{
    protected $table = "{{$table_name}}";
    public $timestamps = true;
    protected $fillable = [@foreach($colums as $colum)'{{$colum['column']}}',@endforeach];
    protected $guarded = [];
    /**
@foreach($colums as $colum)
    {{$colum['column']}};//{{$colum['column_comment']}}({{$colum['type']}})
@endforeach
    **/

@foreach($show_const as $k=>$v)
@foreach($v as $const)
    const {{$const["const"]}}={{$const["value"]}};//{{$const["name"]}}
@endforeach
    public static ${{$k}}=array(
@foreach($v as $const)
        self::{{$const["const"]}}=>array("name"=>"{{$const["name"]}}"),
@endforeach
    );
@endforeach

    public static function getByList($columns,$currentPage,$perPage,$input,$orderby,$is_page=true){
    $pageName = 'page';
    $param = array();
@foreach($colums as $colum)
    if(!empty($input['{{$colum['column']}}'])){
        $param[]= ['{{$colum['column']}}', '=', $input['{{$colum['column']}}']];
    }
@endforeach
    if(!empty($input['setime'])){
        list($startTime,$endTime)= explode(" - ",$input['setime']);
        $param[]= ['created_at', '>=', $startTime];
        $param[]= ['created_at', '<=', $endTime];
    }
    list($key,$asc) = explode(" ",$orderby);
    if($is_page){
        return {{$TableName}}::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
        return {{$TableName}}::where($param)->orderBy($key,$asc)->get();
    }
}
