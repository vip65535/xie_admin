<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Students extends BaseModel
{
    protected $table = "students";
    public $timestamps = true;
    protected $fillable = ['id','name','grade',];
    protected $guarded = [];
    /**
    id;//(int)
    name;//姓名(varchar)
    grade;//年级(varchar)
    **/


    public static function getByList($columns,$currentPage,$perPage,$input,$orderby,$is_page=true){
    $pageName = 'page';
    $param = array();
    if(!empty($input['id'])){
        $param[]= ['id', '=', $input['id']];
    }
    if(!empty($input['name'])){
        $param[]= ['name', '=', $input['name']];
    }
    if(!empty($input['grade'])){
        $param[]= ['grade', '=', $input['grade']];
    }
    if(!empty($input['setime'])){
        list($startTime,$endTime)= explode(" - ",$input['setime']);
        $param[]= ['created_at', '>=', $startTime];
        $param[]= ['created_at', '<=', $endTime];
    }
    list($key,$asc) = explode(" ",$orderby);
    if($is_page){
        return Students::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
        return Students::where($param)->orderBy($key,$asc)->get();
    }
}
