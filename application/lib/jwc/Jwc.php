<?php
namespace app\lib\jwc;
ini_set("display_errors", "on");
error_reporting(E_ALL | E_STRICT);
header("Content-type: text/html; charset=gb2312");
use app\lib\exception\CheckJwc;
class Jwc{
 protected $host = "http://222.198.128.126";
  protected $login_data = [];
  public function __construct($json){
 
    $this->login_data=array(
        'server1' => array(
            "__VIEWSTATE" => "dDw1OTgzNjYzMjM7dDw7bDxpPDE+O2k8Mz47aTw1Pjs+O2w8dDxwPGw8VGV4dDs+O2w86YeN5bqG5aSn5a2mOz4+Ozs+O3Q8cDxsPFRleHQ7PjtsPFw8c2NyaXB0IHR5cGU9InRleHQvamF2YXNjcmlwdCJcPgpcPCEtLQpmdW5jdGlvbiBvcGVuV2luTG9nKHRoZVVSTCx3LGgpewp2YXIgVGZvcm0scmV0U3RyXDsKZXZhbCgiVGZvcm09J3dpZHRoPSIrdysiLGhlaWdodD0iK2grIixzY3JvbGxiYXJzPW5vLHJlc2l6YWJsZT1ubyciKVw7CnBvcD13aW5kb3cub3Blbih0aGVVUkwsJ3dpbktQVCcsVGZvcm0pXDsgLy9wb3AubW92ZVRvKDAsNzUpXDsKZXZhbCgiVGZvcm09J2RpYWxvZ1dpZHRoOiIrdysicHhcO2RpYWxvZ0hlaWdodDoiK2grInB4XDtzdGF0dXM6bm9cO3Njcm9sbGJhcnM9bm9cO2hlbHA6bm8nIilcOwppZih0eXBlb2YocmV0U3RyKSE9J3VuZGVmaW5lZCcpIGFsZXJ0KHJldFN0cilcOwp9CmZ1bmN0aW9uIHNob3dMYXkoZGl2SWQpewp2YXIgb2JqRGl2ID0gZXZhbChkaXZJZClcOwppZiAob2JqRGl2LnN0eWxlLmRpc3BsYXk9PSJub25lIikKe29iakRpdi5zdHlsZS5kaXNwbGF5PSIiXDt9CmVsc2V7b2JqRGl2LnN0eWxlLmRpc3BsYXk9Im5vbmUiXDt9Cn0KZnVuY3Rpb24gc2VsVHllTmFtZSgpewogIGRvY3VtZW50LmFsbC50eXBlTmFtZS52YWx1ZT1kb2N1bWVudC5hbGwuU2VsX1R5cGUub3B0aW9uc1tkb2N1bWVudC5hbGwuU2VsX1R5cGUuc2VsZWN0ZWRJbmRleF0udGV4dFw7Cn0KZnVuY3Rpb24gd2luZG93Lm9ubG9hZCgpewoJdmFyIHNQQz13aW5kb3cubmF2aWdhdG9yLnVzZXJBZ2VudCt3aW5kb3cubmF2aWdhdG9yLmNwdUNsYXNzK3dpbmRvdy5uYXZpZ2F0b3IuYXBwTWlub3JWZXJzaW9uKycgU046TlVMTCdcOwp0cnl7ZG9jdW1lbnQuYWxsLnBjSW5mby52YWx1ZT1zUENcO31jYXRjaChlcnIpe30KdHJ5e2RvY3VtZW50LmFsbC50eHRfZHNkc2RzZGpramtqYy5mb2N1cygpXDt9Y2F0Y2goZXJyKXt9CnRyeXtkb2N1bWVudC5hbGwudHlwZU5hbWUudmFsdWU9ZG9jdW1lbnQuYWxsLlNlbF9UeXBlLm9wdGlvbnNbZG9jdW1lbnQuYWxsLlNlbF9UeXBlLnNlbGVjdGVkSW5kZXhdLnRleHRcO31jYXRjaChlcnIpe30KfQpmdW5jdGlvbiBvcGVuV2luRGlhbG9nKHVybCxzY3IsdyxoKQp7CnZhciBUZm9ybVw7CmV2YWwoIlRmb3JtPSdkaWFsb2dXaWR0aDoiK3crInB4XDtkaWFsb2dIZWlnaHQ6IitoKyJweFw7c3RhdHVzOiIrc2NyKyJcO3Njcm9sbGJhcnM9bm9cO2hlbHA6bm8nIilcOwp3aW5kb3cuc2hvd01vZGFsRGlhbG9nKHVybCwxLFRmb3JtKVw7Cn0KZnVuY3Rpb24gb3Blbldpbih0aGVVUkwpewp2YXIgVGZvcm0sdyxoXDsKdHJ5ewoJdz13aW5kb3cuc2NyZWVuLndpZHRoLTEwXDsKfWNhdGNoKGUpe30KdHJ5ewpoPXdpbmRvdy5zY3JlZW4uaGVpZ2h0LTMwXDsKfWNhdGNoKGUpe30KdHJ5e2V2YWwoIlRmb3JtPSd3aWR0aD0iK3crIixoZWlnaHQ9IitoKyIsc2Nyb2xsYmFycz1ubyxzdGF0dXM9bm8scmVzaXphYmxlPXllcyciKVw7CnBvcD1wYXJlbnQud2luZG93Lm9wZW4odGhlVVJMLCcnLFRmb3JtKVw7CnBvcC5tb3ZlVG8oMCwwKVw7CnBhcmVudC5vcGVuZXI9bnVsbFw7CnBhcmVudC5jbG9zZSgpXDt9Y2F0Y2goZSl7fQp9CmZ1bmN0aW9uIGNoYW5nZVZhbGlkYXRlQ29kZShPYmopewp2YXIgZHQgPSBuZXcgRGF0ZSgpXDsKT2JqLnNyYz0iLi4vc3lzL1ZhbGlkYXRlQ29kZS5hc3B4P3Q9IitkdC5nZXRNaWxsaXNlY29uZHMoKVw7Cn0KXFwtLVw+Clw8L3NjcmlwdFw+Oz4+Ozs+O3Q8O2w8aTwxPjs+O2w8dDw7bDxpPDA+Oz47bDx0PHA8bDxUZXh0Oz47bDxcPG9wdGlvbiB2YWx1ZT0nU1RVJyB1c3JJRD0n5a2m5Y+3J1w+5a2m55SfXDwvb3B0aW9uXD4KXDxvcHRpb24gdmFsdWU9J1RFQScgdXNySUQ9J+W4kOWPtydcPuaVmeW4iFw8L29wdGlvblw+Clw8b3B0aW9uIHZhbHVlPSdTWVMnIHVzcklEPSfluJDlj7cnXD7nrqHnkIbkurrlkZhcPC9vcHRpb25cPgpcPG9wdGlvbiB2YWx1ZT0nQURNJyB1c3JJRD0n5biQ5Y+3J1w+6Zeo5oi357u05oqk5ZGYXDwvb3B0aW9uXD4KOz4+Ozs+Oz4+Oz4+Oz4+Oz62hRbfKCEh4NgqUfD+QNlfnJS/3A==",
            "__VIEWSTATEGENERATOR" => "CAA0A5A7",
            "Sel_Type" => "STU",
            "txt_dsdsdsdjkjkjc" =>$json['userid'],
            "txt_dsdfdfgfouyy" => '',
            "txt_ysdsdsdskgf" => '',
            "pcInfo" => "Mozilla%2F4.0+%28compatible%3B+MSIE+7.0%3B+Windows+NT+6.1%3B+WOW64%3B+Trident%2F5.0%3B+SLCC2%3B+.NET+CLR+2.0.50727%3B+.NET+CLR+3.5.30729%3B+.NET+CLR+3.0.30729%3B+.NET4.0C%3B+.NET4.0E%29x860+SN%3ANULL",
            "typeName" => "%D1%A7%C9%FA",
            "aerererdsdxcxdfgfg" => '',
            "efdfdfuuyyuuckjg" => $this->encrpyt_pass($json['userid'], $json['passwd'])
        )
    );
  }
 
        function login($host, $login_data)
        {
            $login_data = http_build_query($login_data);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $host. '/_data/index_login.aspx');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $login_data);
            $res = curl_exec($curl);
            curl_close($curl);
            preg_match('/SessionId=(.*);/', $res, $str);
            $cookie = $str[1];
            return $cookie;
        }
        function get($url, $cookie)
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_COOKIE, "ASP.NET_SessionId=$cookie");
            $res = curl_exec($curl);
            curl_close($curl);
            return $res;
        }
          function encrpyt_pass($username, $password)
        {
            return strtoupper(substr(md5($username.strtoupper(substr(md5($password), 0, -2)).'10611'), 0, -2));
        }
        function getName(){
        $cookie = $this->login($this->host, $this->login_data['server1']);
        $res = $this->get($this->host."/PUB/foot.aspx", $cookie);
        preg_match('/](.*?)</', $res, $str);
        $str=$str[1];
        if(!empty($str)){
            return $str=iconv("GB2312//IGNORE","UTF-8", $str); 
        }else{
            throw new CheckJwc();
        }
     
        }
}