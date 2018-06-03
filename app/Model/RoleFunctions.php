<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoleFunctions extends Model
{
    protected $table = "role_functions";
    public $timestamps = true;
    protected $guarded = [];

    /**
    id;//(int)
    role_id;//角色id(int)
    functions_id;//权限id(int)
    created_at;//(datetime)
    updated_at;//(datetime)
    **/
    public   static  function getById($id){
        return RoleFunctions::find($id);
    }
    public   static  function getByRoleId($id=0){
        return RoleFunctions::where("role_id",$id)->get();
    }
    public   static  function deleteByRoleId($id=0){
        return RoleFunctions::where("role_id",$id)->delete();
    }

    public static function getByList($columns,$currentPage,$perPage,$input,$orderby,$is_page=true){
    $pageName = 'page';
    $param = array();

    if(!empty($input['id'])){
    $param[]= ['id', '=', $input['id']];
    }
    if(!empty($input['role_id'])){
    $param[]= ['role_id', '=', $input['role_id']];
    }
    if(!empty($input['functions_id'])){
    $param[]= ['functions_id', '=', $input['functions_id']];
    }
    if(!empty($input['created_at'])){
    $param[]= ['created_at', '=', $input['created_at']];
    }
    if(!empty($input['updated_at'])){
    $param[]= ['updated_at', '=', $input['updated_at']];
    }
    if(!empty($input['setime'])){
        list($startTime,$endTime)= explode(" - ",$input['setime']);
        $param[]= ['created_at', '>=', $startTime];
        $param[]= ['created_at', '<=', $endTime];
    }
    /*DB::listen(function($sql) {
        var_dump($sql->sql) ;
    });*/
    list($key,$asc) = explode(" ",$orderby);

    if($is_page){
    return RoleFunctions::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
    return RoleFunctions::where($param)->orderBy($key,$asc)->get();
    }
    protected static function getByUserName($username)
    {
    return Admin::where("user_name", $username)->first();
    }
}
