<?php
namespace app\lib\success;
class BaseSuccess {
    protected $code;
    protected $msg;
    public function __construct($param=[]){
        if(is_array($param)){
            return;
        }
      if(array_key_exists("code",$param)) {
                $this->error_code=$param['code'];
           }
     if(array_key_exists("msg",$param)){
                $this->error_msg=$param['msg'];
        }     
    }
    public function create($data=[]){
        $temp["error_code"]=$this->code;
        $temp['error_msg']=$this->msg;
        $temp['data']=$data;
        return $temp;
    }
}