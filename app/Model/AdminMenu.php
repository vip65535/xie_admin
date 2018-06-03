<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $table = "admin_menu";
    public $timestamps = true;
    protected $guarded = [];

    /**
    id;//id(int)
    sort;//排序(int)
    pid;//父节点(int)
    name;//功能名称(varchar)
    icon;//图标(varchar)
    href;//链接(varchar)
    created_at;//(datetime)
    updated_at;//(datetime)
    **/
    protected  static  function getById($id){
        return AdminMenu::find($id);
    }

    public static function getByList($columns,$currentPage,$perPage,$input,$orderby,$is_page=true){
    $pageName = 'page';
    $param = array();

    if(!empty($input['id'])){
    $param[]= ['id', '=', $input['id']];
    }
    if(!empty($input['sort'])){
    $param[]= ['sort', '=', $input['sort']];
    }
    if(!empty($input['pid'])){
    $param[]= ['pid', '=', $input['pid']];
    }
    if(!empty($input['name'])){
    $param[]= ['name', '=', $input['name']];
    }
    if(!empty($input['icon'])){
    $param[]= ['icon', '=', $input['icon']];
    }
    if(!empty($input['href'])){
    $param[]= ['href', '=', $input['href']];
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
    return AdminMenu::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
    return AdminMenu::where($param)->orderBy($key,$asc)->get();
    }
    protected static function getByUserName($username)
    {
    return Admin::where("user_name", $username)->first();
    }

    public  static  function getTree(){
        $p_menus = AdminMenu::where("pid",0)->orderBy('sort','asc')->get();
        foreach ($p_menus as &$pmenu){
            $pmenu->child=AdminMenu::where("pid",$pmenu->id)->orderBy('sort','asc')->get();
        }
        return $p_menus;
    }
}
