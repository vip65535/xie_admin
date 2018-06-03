<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Functions extends Model
{
    protected $table = "functions";
    public $timestamps = true;
    protected $guarded = [];

    /**
    id;//id(int)
    sort;//排序(int)
    pid;//父节点(int)
    name;//功能名称(varchar)
    icon_class;//图标(varchar)
    href;//链接(varchar)
    created_at;//(datetime)
    updated_at;//(datetime)
    **/
    public static function deleteByHref($href=''){
       $fun =Functions::where("href",$href)->first();
       if(empty($fun)){
           return;
       }
       Functions::where("pid",$fun->id)->delete();
       return Functions::where("href",$href)->delete();
    }
    public   static  function getById($id){
        return Functions::find($id);
    }
    public   static  function getByAdminId($id=0){
        return DB::select("select a.* from functions a left join role_functions b on a.id=b.functions_id left join admin_role c on b.role_id = c.role_id where c.admin_id=?",[$id]);
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
    if(!empty($input['icon_class'])){
    $param[]= ['icon_class', '=', $input['icon_class']];
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
    return Functions::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
    return Functions::where($param)->orderBy($key,$asc)->get();
    }

    public  static  function getTree(){
        $p_menus = Functions::where("pid",0)->orderBy('sort','desc')->get();
        foreach ($p_menus as &$pmenu){
            $pmenu->child=Functions::where("pid",$pmenu->id)->orderBy('sort','desc')->get();
        }
        return $p_menus;
    }
    public  static  function getMyMenu(){
        $admin =Session::get("admin");
        if($admin->id==1){
            $p_menus = Functions::where("pid",0)->where("type","2")->orderBy('sort','desc')->get();
            foreach ($p_menus as &$pmenu){
                $pmenu->child=Functions::where("pid",$pmenu->id)->where("type","2")->orderBy('sort','desc')->get();
            }
        }else{
            $p_menus =  DB::select("select a.* from functions a left join role_functions b on a.id=b.functions_id left join admin_role c on b.role_id = c.role_id where a.pid=0 and c.admin_id=? order by a.sort desc",[$admin->id]);
            foreach ($p_menus as &$pmenu){
                $pmenu->child =  DB::select("select a.* from functions a left join role_functions b on a.id=b.functions_id left join admin_role c on b.role_id = c.role_id where a.pid=? and c.admin_id=? order by a.sort desc",[$pmenu->id,$admin->id]);
            }
        }
        return $p_menus;
    }
}
