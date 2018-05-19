<?php
namespace app\lib\qrcode;
class Date{
    private static $time;
    public static function createDateDir($root=""){
        self::$time=date("Y-m-d",time());
        $fileDir=$root.self::$time;
        $fileDir = iconv("UTF-8", "GBK", $fileDir);
        if(!file_exists($fileDir)){
            mkdir($fileDir,0777,true);
            chmod($fileDir, 0777);
        }
        return $fileDir.DS;
    }
    public static function getTime(){
        return self::$time;
    }
}
// use  Qcloud\Sms\SmsSingleSender;
// class Sms {
//     private $appid;
//     private $appKey;
//     public function __construct(){
//         $this->appid=config("sms.appId");
//         $this->appKey=config("sms.appKey");
//     }
//     public function sendCode($phone){
//         try{
//             $smsSing=new SmsSingleSender($this->appid,$this->appKey);
//             $code=getRandNum(4);
//             $content="你的验证码是：".$code;
//             $result=$smsSing->send(0,"86",$phone,$content);
//             $result=json_decode($result,true);
//             return $result;
//         }catch(\Exception $e){
//                 echo $e;
//         }
//     }

// }