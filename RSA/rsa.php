<?php

header("Content-Type: text/html;charset=UTF-8");

/**
 * PHP实现rsa
 */
class rsa {



    /**
     * RSA私钥加密
     * @param string $private_key 私钥
     * @param string $data 要加密的字符串
     * @return string $encrypted 返回加密后的字符串
     * @author mosishu
     */
    public function privateEncrypt($private_key,$data){
        $encrypted = '';
        $pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        //最大允许加密长度为117，得分段加密
        $plainData = str_split($data, 100);//生成密钥位数 1024 bit key
        foreach($plainData as $chunk){
            $partialEncrypted = '';
            $encryptionOk = openssl_private_encrypt($chunk,$partialEncrypted,$pi_key);//私钥加密
            if($encryptionOk === false){
                return false;
            }
            $encrypted .= $partialEncrypted;
        }
        
        $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        return $encrypted;
    }



    /**
     * RSA公钥解密(私钥加密的内容通过公钥可以解密出来)
     * @param string $public_key 公钥
     * @param string $data 私钥加密后的字符串
     * @return string $decrypted 返回解密后的字符串
     * @author mosishu
     */
    public function publicDecrypt($public_key,$data){
        $decrypted = '';
        $pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
        $plainData = str_split(base64_decode($data), 128);//生成密钥位数 1024 bit key
        foreach($plainData as $chunk){
            $str = '';
            $decryptionOk = openssl_public_decrypt($chunk,$str,$pu_key);//公钥解密
            if($decryptionOk === false){
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }



    //RSA公钥加密
    public function publicEncrypt($public_key,$data){
        $encrypted = '';
        $pu_key = openssl_pkey_get_public($public_key);
        $plainData = str_split($data, 100);
        foreach($plainData as $chunk){
            $partialEncrypted = '';
            $encryptionOk = openssl_public_encrypt($chunk,$partialEncrypted,$pu_key);//公钥加密
            if($encryptionOk === false){
                return false;
            }
            $encrypted .= $partialEncrypted;
        }
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }



    //RSA私钥解密
    public function privateDecrypt($private_key,$data){
        $decrypted = '';
        $pi_key = openssl_pkey_get_private($private_key);
        $plainData = str_split(base64_decode($data), 128);  
        foreach($plainData as $chunk){
            $str = '';
            $decryptionOk = openssl_private_decrypt($chunk,$str,$pi_key);//私钥解密
            if($decryptionOk === false){
                return false;
            }
            $decrypted .= $str;
        }
        return $decrypted;
    }

}





//=============================================================
//

    $private1_key = file_get_contents('pkcs1_pri.key');
    $public1_key = file_get_contents('pkcs1_pub.key');
    $private8_key = file_get_contents('pkcs8_pri.key');
    $public8_key = file_get_contents('pkcs8_pub.key');


	$rsa =new rsa;

	$data = [
		'status'=>'error',
		'code'=>-100,
		'message'=>'操作失败',	//帐号已锁定,无法登录
	];
	$data = json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

	//php加密解密
	var_dump($rsa->privateEncrypt($private1_key,$data));
	var_dump($rsa->publicDecrypt($public1_key,'fQNHTZmOk6naUg58GNEZBOEkJu76aTav7y17+zTM2Jp41QFGXZodIvrTrTpxm808Kh3dMT1CR7kF42gJ4e+Mwdy6ZY5a1lONd/z5b9dj1vMgb7DD+VdIHadJPu08HTjYRFdNGYmw/8P/3dKRjOP+zZleUl/M7HJBUUAUj+/PuOw='));

	//java加密解密
	var_dump($rsa->publicEncrypt($public8_key,$data));
	var_dump($rsa->privateDecrypt($private8_key,'HCH7SVHZ9LMem7rso/r4h6n/I8rcI+GLkBKotiBh2Z0AHBJfl/+ciDJuz/yYXTW9q3O0aVIYTgSGh34CEtm8QbwU1Q41XWTYRoO2k9zmkceBs22xHwo2DelWdftTmOUKzItwzViNhf1R7mByBhdquwSR9X0WiGS/SADNdA8ZHKA='));
	exit;

