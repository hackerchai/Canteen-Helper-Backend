<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function curl_get($url){   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    return $file_contents;
}
function curl_post($url,$data){
        $data=json($data);
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $res = curl_exec($curl);
        curl_close($curl);
        print_r($res);
        return $res;
}
function getRandChar($length){
    $str=null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}
    function getRandNum($length){
        $str=null;
        $strPol = "0123456789";
        $max = strlen($strPol) - 1;
        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }
    
        return $str;
}
function getAddressByStr($str){
    $str=$str."";
    $l=strlen("$str");
    if($l<5)
    {
        return;
    }
    $address=config("address");
    $garden=$address['garden'];
    $building=$address['building'];
    $address=$garden[$str[0]].$building[$str[1]].substr($str,2);
    return $address;
}

