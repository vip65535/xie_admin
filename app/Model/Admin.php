<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class Admin extends Model
{
    protected $table = "admin";
    public $timestamps = true;
    protected $guarded = [];

    const NORMAL=1;//正常
    const DELETE=2;//停用
    public static $STATUS=array(
    self::NORMAL=>array("name"=>"正常"),
    self::DELETE=>array("name"=>"停用"),
    );

    const MAN=1;//男
    const WOMEN=2;//女
    public static $SEX=array(
    self::MAN=>array("name"=>"男"),
    self::WOMEN=>array("name"=>"女"),
    );

    /**
    id;//id(int)
    user_name;//用户名(varchar)
    password;//密码(varchar)
    status;//状态值:1-normal-正常,2-delete-删除(int)
    sex;//性别:1-man-男,2-women-女(int)
    created_at;//创建时间(datetime)
    updated_at;//修改时间(datetime)
    **/
    protected  static  function getById($id){
        return Admin::find($id);
    }
    public  static  function isAuth($uri){
        $admin =Session::get("admin");
        $roles = Functions::getByAdminId($admin->id);
        if(in_array($uri,array_column($roles,'href'))||$admin->id==1){
            return true;
        }
        return false;
    }

    public static function getByList($columns,$currentPage,$perPage,$input,$orderby,$is_page=true){
    $pageName = 'page';
    $param = array();

    if(!empty($input['id'])){
    $param[]= ['id', '=', $input['id']];
    }
    if(!empty($input['user_name'])){
    $param[]= ['user_name', '=', $input['user_name']];
    }
    if(!empty($input['password'])){
    $param[]= ['password', '=', $input['password']];
    }
    if(!empty($input['status'])){
    $param[]= ['status', '=', $input['status']];
    }
    if(!empty($input['sex'])){
    $param[]= ['sex', '=', $input['sex']];
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
    return Admin::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
    return Admin::where($param)->orderBy($key,$asc)->get();
    }
    protected static function getByUserName($username)
    {
    return Admin::where("user_name", $username)->first();
    }
}
