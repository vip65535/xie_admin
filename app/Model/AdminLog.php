<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends BaseModel
{
    protected $table = "admin_log";
    public $timestamps = true;
    protected $fillable = ['id','aid','method','url','param','ip','created_at','updated_at',];
    protected $guarded = [];
    /**
    id;//id(int)
    aid;//管理员(int)
    method;//访问类型(varchar)
    url;//访问链接(varchar)
    param;//请求数据(longtext)
    ip;//IP地址(varchar)
    created_at;//(datetime)
    updated_at;//(datetime)
    **/


    public static function getByList($columns,$currentPage,$perPage,$input,$orderby,$is_page=true){
    $pageName = 'page';
    $param = array();
    if(!empty($input['id'])){
        $param[]= ['id', '=', $input['id']];
    }
    if(!empty($input['aid'])){
        $param[]= ['aid', '=', $input['aid']];
    }
    if(!empty($input['method'])){
        $param[]= ['method', '=', $input['method']];
    }
    if(!empty($input['url'])){
        $param[]= ['url', '=', $input['url']];
    }
    if(!empty($input['param'])){
        $param[]= ['param', '=', $input['param']];
    }
    if(!empty($input['ip'])){
        $param[]= ['ip', '=', $input['ip']];
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
    list($key,$asc) = explode(" ",$orderby);
    if($is_page){
        return AdminLog::where($param)->orderBy($key,$asc)->paginate($perPage, $columns, $pageName, $currentPage);
    }
        return AdminLog::where($param)->orderBy($key,$asc)->get();
    }
}
