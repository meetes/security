<?php

header("Content-Type: text/html;charset=UTF-8");
require 'jwt.php';
require 'sign.php';

// ====================== 参数加密

// 1、先生成用户帐号token
$accessArr = array('appid'=>'123456','appsecret'=>'000000');
$jwt=new Jwt;
$access_token=Jwt::accessToken($accessArr);
echo "<pre>";
echo $access_token;
$access_token = json_decode($access_token, true)['data']['access_token'];



// 2、利用用户帐号token获取接口临时票据
$jwtArr = array('iss'=>'admin','iat'=>time(),'exp'=>time()+7200,'nbf'=>time(),'sub'=>'www.admin.com','jti'=>$access_token);
$token_test=Jwt::getToken($jwtArr);
echo "<pre>";
echo $token_test;
$token_test = json_decode($token_test,true)['data']['jsapi_ticket'];



// 3、对参数和token进行签名加密
$signArr = ['appid'=>'123456','body'=>'test','title'=>'biaoti','timestamp'=>time(),'jsapi_ticket'=>$token_test, 'noncestr'=> 'ibuaiVcKdpRxkhJA'];
$sign=new sign;
$sign_test = sign::signature($signArr);
echo "<pre>";
echo $sign_test;
$sign_test = json_decode($sign_test,true);





// ====================== 参数解密
// 1、对签名解密校验
$sign_test2 = sign::signature($signArr,'DECODE',$sign_test['data']['sign']);
echo "<pre>";
echo $sign_test2;


// 2、对token进行解密校验
$getPayload_test=Jwt::verifyToken($token_test);
echo "<br><br>";
var_dump($getPayload_test);
echo "<br><br>";



 ?>