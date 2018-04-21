<?php
namespace app\lib\qrcode;
use think\Loader;
use Endroid\QrCode\QrCode;

class Qcode{
    private static $name;
    private static $label;
    public static function png($text){
        $root=config("qr.root");
        $fileRoot=$root.config("qr.fileRoot");
        $logoPath=$root.config("qr.logo");
        $fileRoot=Date::createDateDir("$fileRoot");
        self::$name=md5($text.time());
        $qrcode=new QrCode();
        $qrcode->setText($text);
        $qrcode->setSize(300);
        $qrcode->setLogoPath($logoPath);
        $qrcode->setLogoWidth(100);
        $pngData=$qrcode->writeString();
        $filename=$fileRoot.self::$name.".png";
        $file=fopen($filename,"a");
        fwrite($file,$pngData);
        fclose($file);
        return $filename;
    }
    public static function getName(){
           return self::$name;
    }
    public static function getRelativeName(){
        return Date::getTime().DS.self::$name;
    }
}
    
