<?php

namespace app\push\controller;

use think\worker\Server;

use think\Db;

class Worker extends Server{

    protected $socket = 'websocket://127.0.0.1:2346';

    private $userId = 0;

    /**
     * 收到信息
     * @param $connection
     * @param $data
     */
    public function onMessage($connection, $data){
        $msgData = json_decode($data,true);
        if(!isset($connection->isAuthed)){
        	if(!isset($msgData["token"])){
        		$connection->send('{"error":"bad auth"}');
        		return false;
        	}
        	$menchantUser = Db::name("merchant_member")->where("token",$msgData["token"])->find();
        	if(empty($menchantUser)){
        		$connection->send('{"error":"bad auth,user not found"}');
        		return false;
        	}
        	$connection->uid = $menchantUser["id"];
        	$connection->merchantId = $menchantUser["merchant_id"];
        	$this->midList[$connection->merchantId] = $connection;
        	$connection->isAuthed = true;
            $this->sendUncomplete($connection);
        }
    }

    private function sendUncomplete($connection){
        $unConfirmed = Db::name("order")->where("merchant_id",$connection->merchantId)->where("status",3)->where("is_pushed",0)->select();
        $unfinishedOrder = Db::name("order")->where("merchant_id",$connection->merchantId)->where("status",4)->select();
        $dishInfoList=[];
        $dishArray = $unConfirmed+$unfinishedOrder;
        foreach ($dishArray as $key => $value) {
            $dishList = explode(",", $value["goods"]);
            foreach ($dishList as $dishKey => $dishId) {
                if(!array_key_exists($dishId, $dishInfoList)){
                    $dishInfoList[$dishId] = Db::name("menu")->where("id",$dishId)->find();
                }
            }
        }
        $returnJson = [
            "unFinished" => $unfinishedOrder,
            "unConfirmed" => $unConfirmed,
            "dishList" => $dishInfoList,
        ];
        $connection->send(json_encode($returnJson));
    }

    /**
     * 当连接建立时触发的回调函数
     * @param $connection
     */
    public function onConnect($connection){
         $connection->uid = ++$this->userId;
    }

    /**
     * 当连接断开时触发的回调函数
     * @param $connection
     */
    public function onClose($connection)
    {
        
    }

    /**
     * 当客户端的连接上发生错误时触发
     * @param $connection
     * @param $code
     * @param $msg
     */
    public function onError($connection, $code, $msg)
    {
        echo "error $code $msg\n";
    }

    /**
     * 每个进程启动
     * @param $worker
     */
    public function onWorkerStart($worker)
    {

    }
}