<?php

namespace App\Utils;

class StringUtil
{
    //uuid
    public static function create_uuid($prefix = "")
    {    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }
    public static function create_rand_num($num)
    {
        $str ="";
        for ($i=0;$i<$num;$i++){
            $str =$str.rand(0,9);
        }

        return $str ;
    }
}