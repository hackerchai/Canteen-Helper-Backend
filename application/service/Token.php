<?php
namespace app\service;
use app\lib\exception\WeChatException;
use Exception;
use app\student\model\User;
use app\lib\exception\TokenException;
use think\Cache;
use think\Db;
class Token{
    protected $code;
    protected $appId;
    protected $secert;
    protected $loginUrl;
    protected $name;
    /**
     * 构造函数 初始化类里面的属性
     * @param string $code 微信客户端传递的code
     * @param string $name 数据库表名，不要带前缀
     * @param string $option 选择一个微信后台appid配置
     */
    public function __construct($code,$name,$option)
    {
        $wx=config($option);
        $this->name=$name;
        $this->code=$code;
        $this->appId=$wx['appId'];
        $this->secert=$wx['appSecret'];     
        $this->loginUrl=sprintf($wx['loginUrl'],$this->appId,$this->secert,$this->code);
    }
    
    public static function getVarByToken($token,$key){
            $value=Cache::get($token);
            $value=json_decode($value,true);
           return $value[$key];
    }
    /**
     * 生成令牌
     */
    public function generateToken(){
        $randChars=getRandChar(32);
        //加强安全性
        $timestamp=$_SERVER['REQUEST_TIME_FLOAT'];
        $salt=config('token_salt');
        return md5($randChars.$timestamp.$salt);
    }
    /**
     * 用code换取令牌
     * 并将令牌数组放入缓存数据里面
     * 一个用户的令牌数组 
     * key:token
     * value:uid wxResult
     */
    public function get(){
        $result=curl_get($this->loginUrl);     
        $wxResult=json_decode($result,true);
        $token='';
        if(empty($wxResult))
        {
            throw new Exception("获取session_key以及oprnID时异常，微信内部错误");
        }else{
            $loginFail=array_key_exists("errcode",$wxResult);
            if($loginFail)
            {
                $this->processLoginError($wxResult);

            }else{
               $token=$this->grantToken($wxResult);
            }
        }
        return $token;
    }
    /**
     * 准备缓存数据
     */
    private function prepareCachedValue($wxResult,$uid){
        $cachedValue=$wxResult;
        $cachedValue['uid']=$uid;
        $cachedValue['scope']=16;
        return $cachedValue;
    }
    /**
     * 解析$wxResult
     * 若openid存在数据里面则不添加数据
     * 反之，openid不存在则添加数据
     * @param array $wxResult 微信返回的数据数组
     * @return string $token 生成的令牌
     */
    protected function grantToken($wxResult){
        $openId=$wxResult['openid'];
        $user=Db::name($this->name)->where("open_id","=",$openId)->find();
        if($user){
            $uid=$user['id'];
        }else{
            $uid=db($this->name)->insertGetId([
                "open_id" => $openId
            ]);
        }
        $cachedValue=$this->prepareCachedValue($wxResult,$uid);
        $token=$this->saveToCache($cachedValue);
        db($this->name)->where('id',"=",$uid)->update(["token" => $token]);
        return $token;
    }
    protected function saveToCache($cachedValue){
        $key=$this->generateToken();
        $value=json_encode($cachedValue);
        $expire_in=config('token_expire_in');
        $result=cache($key,$value,$expire_in);
        if(!$result){
            $e=new TokenException([
                'msg' =>'服务器缓存异常'
            ]);
            throw $e;
        }
        return $key;
    }
  
    protected function processLoginError($wxResult){
        throw new WeChatException();
    }
   
}
