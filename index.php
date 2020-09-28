<?php
$username='248919427';//接口用户名     (这个不用改   我已经改好 )
$password='zengyuhu0727';//接口密码    (这个不用改   我已经改好 )
$real_page = 'lp.php';//广告URL    (这个是广告页的url  )
$safe_page = 'safe.html';//伪装URL    (安全页url)
$jsonData = array();
$jsonData['country']= 'US';//投放的国家地区，ALL为全可以访问，留空为全部不允许   (国家地区   自己根据投放地区更改代码  代码固定的在Country.html)
$jsonData['mobile']='0';//1为只允许手机 2为只允许pc 0为不限制


//后面的都不用改了    改前面就行了

$jsonData['domain'] = $_SERVER['HTTP_HOST'];   // 请求头中host的内容
$jsonData['ua'] = $_SERVER['HTTP_USER_AGENT'];   //用户代理信息   包括用户使用的浏览器信息和系统信息
$jsonData['referer'] = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';   //判断是用户代理到当前页的前一页的地址是否设置
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {  // 判断请求信息中时候存在真实ip
    $jsonData['ip'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
} else {
    //获取客户端的代理ip是否存在
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $jsonData['ip'] = getenv('HTTP_CLIENT_IP');
        //通过客户端的代理ip获取的客户端的真实ip   如果没有代理ip  则获取到的事空值
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $jsonData['ip'] = getenv('HTTP_X_FORWARDED_FOR');
        //获取客户端的真实ip
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $jsonData['ip'] = getenv('REMOTE_ADDR');

    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $jsonData['ip'] = $_SERVER['REMOTE_ADDR'];
    }
}
$ch = curl_init('https://cloakplus.com/v2/');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_TIMEOUT,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($jsonData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$return = curl_exec($ch);
$return = json_decode($return, true);
$boolean = $return['result'];
if ($boolean){
    echo file_get_contents($real_page);
    //推荐include或file_get_contents实现，跳转目前容易被封
}else{
    echo file_get_contents($safe_page);
}