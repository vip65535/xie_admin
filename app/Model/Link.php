<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Link extends BaseModel
{
    protected $table = "link";
    public $timestamps = true;
    protected $fillable = ['id','name','href','email','type','created_at','updated_at',];
    protected $guarded = [];

    const INDEX=1;//首页
    const INNER=2;//内页
    public static $TYPE=array(
    self::INDEX=>array("name"=>"首页"),
    self::INNER=>array("name"=>"内页"),
    );

    /**
    id;//id(int)
    name;//名称(varchar)
    href;//链接(varchar)
    email;//站长邮箱(varchar)
    type;//类型:1-INDEX-首页,2-INNER-内页(int)
    created_at;//(datetime)
    updated_at;//(datetime)
    **/
    public static function getById($id){
        return Link::find($id);
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
    if(!empty($input['href'])){
        $param[]= ['href', '=', $input['href']];
    }
    if(!empty($input['email'])){
        $param[]= ['email', '=', $input['email']];
    }
    if(!empty($input['type'])){
        $param[]= ['type', '=', $input['type']];
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
        return Link::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
        return Link::where($param)->orderBy($key,$asc)->get();
    }
}
