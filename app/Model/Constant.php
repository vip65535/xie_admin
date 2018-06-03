<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15 0015
 * Time: 下午 9:40
 */

namespace App\Model;


interface Constant
{
     const SUCCESS =1;
     const ERROR =0;

     const PAPER_TYPE_ARRAY ="paper:paper_type_array";
     const MOBILE_SEND_NUM ="mobile_send_num_";
     const IP_SEND_NUM ="ip_send_num_";
     const MOBILE_SEND_CODE ="mobile_send_code_";
     const CODE_INTIVE ="mobile_send_timer_";
     const MOBILE_MAX_SEND_NUM =3;
     const IP_MAX_SEND_NUM =5;
     const MOBILE_MAX_SEND_TIME =60*60*24;
     const IP_MAX_SEND_TIME =60*60*24;
}