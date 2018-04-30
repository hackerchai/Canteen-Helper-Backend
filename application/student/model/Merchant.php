<?php
namespace app\student\model;
class Merchant extends BaseModel{
        public static function getAMerchant($id){
            $merchant=self::get($id);
            $merchant=$merchant->toArray();
            return $merchant;
        }
    
}