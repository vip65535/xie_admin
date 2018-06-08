<?php
/**
 * Created by PhpStorm.
 * User: xly
 * Date: 2018/5/5
 * Time: 下午2:45
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected static function getPage($sql,$param,$p=1,$pageSize=10,$pageName='p',$path="",$urlParam)
    {
        $rows = $p == 1 ? 0 : ($p - 1) * $pageSize;
        $sql = preg_replace("/^SELECT/i", "SELECT SQL_CALC_FOUND_ROWS ", $sql);
        $sql.=" limit ".$rows.",".$pageSize;
        $pagedData = DB::select($sql, $param);
        $total = DB::select("SELECT FOUND_ROWS() as total;");
        $options = array();
        $options['pageName'] =$pageName;
        $options['path'] =$path;
        $options['query'] =$urlParam;
        $query = new LengthAwarePaginator($pagedData, $total[0]->total, $pageSize, $p, $options);
        $query->withPath("toJoinClass");
        return $query;
    }

    protected static function getList($sql,$param)
    {
        $pagedData = DB::select($sql, $param);
        return $pagedData;
    }
}