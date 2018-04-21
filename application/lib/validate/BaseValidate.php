<?php
namespace app\lib\validate;
use think\Validate;
use think\Request;
use app\lib\exception\ParamerException;
use think\Cache;
use app\lib\exception\TokenException;
use app\lib\validate\Token;
class BaseValidate extends Validate
{


    /**
     * 验证一般的数据
     * @param array $data 须验证的数据
     * @throw ParamerException
    */
    public function check_data($data){
       if(!$this->check($data))
       {
           $e=new ParamerException(
            [
                'msg' => is_array($this->error) ? implode(
                    ';', $this->error) : $this->error,
            ]
           );
           throw $e;

       }
    }
    /**
     * 客户端表单传递参数验证;
     * @throw ParamerException
     * @return array $param 客户端传递的参数;
     */
    public function goCheck(){
        $request=Request::instance();
        $param=$request->param();    
        $header=$request->header(); 
        if(array_key_exists("token",$header))
        {    
        $param['token']=$header['token'];
        $flag=(new Token())->check($header);
        if(!$flag)
        {
            throw new TokenException();
        }
        }
        if(!$this->check($param))
        {
            $e=new ParamerException(
                [
                    
                    'msg' => is_array($this->error) ? implode(
                        ';', $this->error) : $this->error,
                ]
                    );
          throw $e;
        }
        return $param;
    }
    /**
     * 过滤掉post传递而来对数据库害的自动；
     * @param array $post 一般为经过验证的post数组
     * @param array $flied 数据库一些关键性数据如id以及外键关联id
     * @throw ParamerExcetion
     * @return array 过滤post的新数组
     */
    public function filterPostData($post,$field=[]){
        if(!empty($field)){
            foreach($field as $key)
            {
                if(array_key_exists($key,$post))
                {
                    $e=new ParamerException(
                        [
                            'msg' =>"参数中包含非法参数名$key"
                        ]
                    );
                   throw $e;
                }
            }
        }
        $newArray=[];
        foreach($this->rule as $key=>$value)
        {
            $newArray[$key]=$post[$key];
        }
        return $key;       
    }
    protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }
    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        if(!empty($value))
        {
            return true;
        }else{
            return $field.'不能为空';
        }
    }
    protected function isExistToken($value,$rule='',$data='',$field=''){
        if(!empty($value))
        {
           $value=Cache::get($value);
           if(!empty($value)){
               return true;
           }else{
               return $field."不存在或已经过期";
           }
        }else{
            return $field.'不能为空';
        }
    }
}