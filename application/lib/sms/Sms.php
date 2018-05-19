<?php
namespace app\lib\sms;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use app\lib\exception\PhoneException;
Config::load();
class Sms{
    static $acsClient = null;
    public static function getAcsClient() {
        $product = "Dysmsapi";
        $domain = "dysmsapi.aliyuncs.com";
        $accessKeyId = config("aliyun.accessKeyId"); 
        $accessKeySecret = config("aliyun.accessKeySecret"); 
        $region = "cn-hangzhou";
        $endPointName = "cn-hangzhou";
        if(static::$acsClient == null) {
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
     /**
     * 发送短信
     * @param string $phone 电话号码; 
     * @return stdClass
     */
    public static function sendSms($phone) {
        $request = new SendSmsRequest();
        //可选-启用https协议
        //$request->setProtocol("https");
        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);
        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName(config("aliyun.signName"));
        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode(config("aliyun.templateCode"));
        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $code=getRandNum(4);
        $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
            "code"=>$code,
        ), JSON_UNESCAPED_UNICODE));
        // 可选，设置流水号
        // $request->setOutId("yourOutId");
        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        // $request->setSmsUpExtendCode("1234567");
        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        $res=self::analysisAcsResponse($acsResponse,$phone,$code);
        return $res;
    }
    private static function analysisAcsResponse($acsResponse,$phone,$code){
        $msg=$acsResponse->Message;
        $rcode=$acsResponse->Code;
        $data=[];
        if($rcode=="OK"){
            $data["is_sent"]=true;
            cache($phone,$code,config("aliyun.codeExpire"));
        }else{
            throw new PhoneException();
        }
        return $data;
    }
}