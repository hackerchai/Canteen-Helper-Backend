<?php
namespace app\admin\controller;
use app\admin\model\Merchant;
class Business extends BaseController{
    public function index(){
        $merchants=Merchant::paginate(1);
        $show=$merchants->render();
        $this->assign("show",$show);
        $this->assign("merchants",$merchants);
        return $this->fetch();
    }
    public function company(){
        $merchants=(new Merchant())->where("is_verified=1")->paginate(1);
        $show=$merchants->render();
        $this->assign("show",$show);
        $this->assign("merchants",$merchants);
        return $this->fetch();
    }
}