<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = "role";
    public $timestamps = true;
    protected $guarded = [];

    /**
    id;//id(int)
    name;//角色名称(varchar)
    created_at;//(datetime)
    updated_at;//(datetime)
    **/
    public static function getById($id){
        return Role::find($id);
    }
    public static function getByAdminId($id=0){
        return DB::select("select r.* from role r left join admin_role a on r.id=a.role_id where a.admin_id=?",[$id]);
    }
    public static function getByList($columns,$currentPage,$perPage,$input,$orderby,$is_page=true){
    $pageName = 'page';
    $param = array();

    if(!empty($input['id'])){
    $param[]= ['id', '=', $input['id']];
    }
    if(!empty($input['name'])){
    $param[]= ['name', '=', $input['name']];
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
    return Role::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
    return Role::where($param)->orderBy($key,$asc)->get();
    }
    protected static function getByUserName($username)
    {
    return Admin::where("user_name", $username)->first();
    }
}
