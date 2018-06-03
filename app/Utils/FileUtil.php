<?php
namespace App\Utils;

class FileUtil
{
    //获取文件后缀
    public static function getSuffix($name){
        $last = strrpos($name,'.');
        if($last==-1){
            return -1;
        }
        return strtolower(substr($name,$last+1));
    }
    //判断文件是不是图片
    public static function isImg($name){
        $suf = self::getSuffix($name);
        if($suf=='png'||$suf=='jpg'||$suf=='gif'||$suf=='jpeg'){
            return true;
        }
        return false;
    }
}