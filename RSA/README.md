
## 1.什么是RSA加密
[RSA](https://en.wikipedia.org/wiki/RSA_%28cryptosystem%29) （详见维基百科）算法是现今使用最广泛的公钥密码算法，也是号称地球上最安全的加密算法，与 `md5` 和 `sha1` 不同，到目前为止，也只有极短的RSA加密被破解。
那么什么是公匙密码算法呢，根据密钥的使用方法，可以将密码分为对称密码和公钥密码，接下来我们来简单说明下它们两个。

对称密码：加密和解密使用同一种密钥的方式，常用的算法有 `DES` 以及 `AES`。
公钥密码：加密和解密使用不同的密码的方式，因此公钥密码通常也称为非对称密码，常用的算法有 `RSA`。

由于本文讨论的是 `php` 的 `RSA` 加密实例，这里就不详细说明了，对于 `RSA` 算法有兴趣的朋友可以查看下面的文章

[《带你彻底理解RSA算法原理》](https://blog.csdn.net/dbs1215/article/details/48953589)

对于 `php` 更多加密方式有兴趣的朋友可以查看下面的文章

[《PHP数据加密技术与密钥安全管理》](https://segmentfault.com/a/1190000007041679)

---

## 2.使用场景
- 为移动端（IOS，安卓）编写 `API` 接口
- 进行支付、真实信息验证等安全性需求较高的通信
- 与其他第三方或合作伙伴进行重要的数据传输

---

## 3.前端和后端代码
大家可以先到[http://web.chacuo.net/netrsakeypair](http://web.chacuo.net/netrsakeypair)这个网站，在线生成`公钥`和`私钥`

![image](https://img-blog.csdn.net/20170302145741008?watermark/2/text/aHR0cDovL2Jsb2cuY3Nkbi5uZXQvTTQ3MTU4NjY1MQ==/font/5a6L5L2T/fontsize/400/fill/I0JBQkFCMA==/dissolve/70/gravity/Center)

```
<!doctype html>
<html>
  <head>
    <title>JavaScript RSA Encryption</title>
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script src="http://travistidwell.com/jsencrypt/bin/jsencrypt.js"></script>
    <script type="text/javascript">

      // Call this code when the page is done loading.
      $(function() {

        // Run a quick encryption/decryption when they click.
        $('#testme').click(function() {

          // Encrypt with the public key...
          var encrypt = new JSEncrypt();
          encrypt.setPublicKey($('#pubkey').val());
          var encrypted = encrypt.encrypt($('#input').val());
          console.log(encrypted);

          // Decrypt with the private key...
          var decrypt = new JSEncrypt();
          decrypt.setPrivateKey($('#privkey').val());
          var uncrypted = decrypt.decrypt(encrypted);
          console.log(uncrypted);

          // Now a simple check to see if the round-trip worked.
          if (uncrypted == $('#input').val()) {
            alert('It works!!!');
          }
          else {
            alert('Something went wrong....');
          }
        });
      });
    </script>
  </head>
  <body>
    <label for="privkey">Private Key</label><br/>
    <textarea id="privkey" rows="15" cols="65">-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAM9PA5PXnEW/cqV/
/MA7uo//UMJQ2ezlvOHewOW3FAz8Hp+khWUeevNGIDIDBZJeHbUgtcGwkBNVT7DL
5dts/7JLa1v8nOx2kcl5ZP3xNUH+FFolj3Xp1P2MzCjxBTEI65e53Hy8ck5/fxd4
qAvI9gaSrkV8JjJICiEhi/lPELbLAgMBAAECgYBfliUmJAJQPqgUG2FlGaU6BBUu
o6z4CC4BT35N7Q53tkBAh9FiAJ3cUfdCWBZXmMHF5GEp/8lOwMVP1ZQUiruSmojd
PF3aTUhO+LF55nwpAiSv/Wm2Bixy1TCil4zHtmyxCYCdF03yATQzgdlwejGFVal0
RooAq7qqwMCGIGpEIQJBAP/uQYviod4LgjnLRGaa6P96ScQ/bIlbJmcyZLt3LYjY
i3bPaZ1oFA7292+Cic/iViZTZZdw3z5X0rtwTVQv8ykCQQDPXWMMyRPTHDFQpFOO
/aQeoLASehQNYXvosXA8RQtIu74Mg0SQLh7c2Z4YYz9JSaQyaxcPxsQWZJCv2j7O
BmzTAkBqRTeIa2HFPsgjUWkkpdxsAQ5SY/egjW3D2iQDx7fro+c9PWDgkJALqrcR
4YVyAcy9+1Eq8h5w16zUUgx6EbMJAkBp5HufXNOV2/DHCJNvEtGLnm0rklHJH34C
LxJshKmlg9IiW6pYomS6TRrxw0TfLQ7/fDZzpQIfmU1Vr/KgjSFnAkEArZ6qCgmB
HzwDHDKedkj0bj5IAwg+/IRWuOmogW31dD25BWRvOqWaZQGIjYzXzNZ5QjuYD57f
kWs9v65Uz1z1eQ==
-----END PRIVATE KEY-----</textarea><br/>
    <label for="pubkey">Public Key</label><br/>
    <textarea id="pubkey" rows="15" cols="65">-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPTwOT15xFv3Klf/zAO7qP/1DC
UNns5bzh3sDltxQM/B6fpIVlHnrzRiAyAwWSXh21ILXBsJATVU+wy+XbbP+yS2tb
/JzsdpHJeWT98TVB/hRaJY916dT9jMwo8QUxCOuXudx8vHJOf38XeKgLyPYGkq5F
fCYySAohIYv5TxC2ywIDAQAB
-----END PUBLIC KEY-----</textarea><br/>
    <label for="input">Text to encrypt:</label><br/>
    <textarea id="input" name="input" type="text" rows=4 cols=70>This is a test!</textarea><br/>
    <input id="testme" type="button" value="Test Me!!!" /><br/>
  </body>
</html>



<?php 
$private_key = '-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAM9PA5PXnEW/cqV/
/MA7uo//UMJQ2ezlvOHewOW3FAz8Hp+khWUeevNGIDIDBZJeHbUgtcGwkBNVT7DL
5dts/7JLa1v8nOx2kcl5ZP3xNUH+FFolj3Xp1P2MzCjxBTEI65e53Hy8ck5/fxd4
qAvI9gaSrkV8JjJICiEhi/lPELbLAgMBAAECgYBfliUmJAJQPqgUG2FlGaU6BBUu
o6z4CC4BT35N7Q53tkBAh9FiAJ3cUfdCWBZXmMHF5GEp/8lOwMVP1ZQUiruSmojd
PF3aTUhO+LF55nwpAiSv/Wm2Bixy1TCil4zHtmyxCYCdF03yATQzgdlwejGFVal0
RooAq7qqwMCGIGpEIQJBAP/uQYviod4LgjnLRGaa6P96ScQ/bIlbJmcyZLt3LYjY
i3bPaZ1oFA7292+Cic/iViZTZZdw3z5X0rtwTVQv8ykCQQDPXWMMyRPTHDFQpFOO
/aQeoLASehQNYXvosXA8RQtIu74Mg0SQLh7c2Z4YYz9JSaQyaxcPxsQWZJCv2j7O
BmzTAkBqRTeIa2HFPsgjUWkkpdxsAQ5SY/egjW3D2iQDx7fro+c9PWDgkJALqrcR
4YVyAcy9+1Eq8h5w16zUUgx6EbMJAkBp5HufXNOV2/DHCJNvEtGLnm0rklHJH34C
LxJshKmlg9IiW6pYomS6TRrxw0TfLQ7/fDZzpQIfmU1Vr/KgjSFnAkEArZ6qCgmB
HzwDHDKedkj0bj5IAwg+/IRWuOmogW31dD25BWRvOqWaZQGIjYzXzNZ5QjuYD57f
kWs9v65Uz1z1eQ==
-----END PRIVATE KEY-----';
$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPTwOT15xFv3Klf/zAO7qP/1DC
UNns5bzh3sDltxQM/B6fpIVlHnrzRiAyAwWSXh21ILXBsJATVU+wy+XbbP+yS2tb
/JzsdpHJeWT98TVB/hRaJY916dT9jMwo8QUxCOuXudx8vHJOf38XeKgLyPYGkq5F
fCYySAohIYv5TxC2ywIDAQAB
-----END PUBLIC KEY-----';


var_dump(privateEncrypt($private_key,'1ee1e1'));
var_dump(publicDecrypt($public_key,'uhb+d+YoPRol04TmW8LGNH99Mue0be/iji0lBToCNIm45I4wZVR9m3O/tvOA+OR7BkC7hHnXlPbaMGwy9LbdNaRPCPo8lK3/EQ85qgd6IGkTlQf6uml2fG0fr7zdfvyoM4q4tW5JORiqA2x3YQBJMT7iHg4ok8RYJ+hchi9CFtQ='));


var_dump(publicEncrypt($public_key,'1ee1e1'));
var_dump(privateDecrypt($private_key,'vxXycEJOj9rtzPLQrF42kF2ihm8ekvhbky3qwjYj8xrzbbVNcqNJwwrUdlEi1KCkIbUIfiNdKAlUjIKovhTFUab2QH7g5S59xMLJSArw45UC5TtwA99d60PFI3Y9Y7WN9FLLSuoyVLTtIaBKDynIQl+j5TpPCT1ymCgoeEn83iQ='));




/**
 * RSA私钥加密
 * @param string $private_key 私钥
 * @param string $data 要加密的字符串
 * @return string $encrypted 返回加密后的字符串
 * @author mosishu
 */
function privateEncrypt($private_key,$data){
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
function publicDecrypt($public_key,$data){
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
function publicEncrypt($public_key,$data){
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
function privateDecrypt($private_key,$data){
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

?>
```


---

## 4.补充
如果是多个端（一对多），可以多生成几对私钥和公钥。
- pubkey_server、prikey_server`（服务端）`
- pubkey_client、prikey_client`（客户端）`
- pubkey_ios、prikey_ios`（IOS端）`


---

## 最后说一句
最后要说明的是，公钥、私钥都可以加密，也都可以解密。其中：用公钥加密需要私钥解密，称为“加密”。由于私钥是不公开的，确保了内容的保密，没有私钥无法获得内容；用私钥加密需要公钥解密，称为“签名”。由于公钥是公开的，任何人都可以解密内容，但只能用发布者的公钥解密，验证了内容是该发布者发出的。

---

## 番外
> PKCS1和PKCS8有什么区别，什么时候改用PKCS1/PKCS8?

**秘钥格式**

- PKCS1语法格式私钥：传统格式，PHP、.NET一般使用此格式
- PKCS8语法格式私钥：java一般使用此格式
- PKCS1格式和PKCS8格式的私钥，导出的公钥内容是一摸一样的

**公钥是不分 pkcs1 格式和 pkcs8格式的，因为公钥就只有一种语法格式。**

---

# PHP实现RSA-类文件
> 新建rsa.php，复制粘贴如下：

```php

<?php
/**
 * PHP实现rsa
 */
class rsa {

    //私钥
    private static $private_key='-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAM9PA5PXnEW/cqV/
/MA7uo//UMJQ2ezlvOHewOW3FAz8Hp+khWUeevNGIDIDBZJeHbUgtcGwkBNVT7DL
5dts/7JLa1v8nOx2kcl5ZP3xNUH+FFolj3Xp1P2MzCjxBTEI65e53Hy8ck5/fxd4
qAvI9gaSrkV8JjJICiEhi/lPELbLAgMBAAECgYBfliUmJAJQPqgUG2FlGaU6BBUu
o6z4CC4BT35N7Q53tkBAh9FiAJ3cUfdCWBZXmMHF5GEp/8lOwMVP1ZQUiruSmojd
PF3aTUhO+LF55nwpAiSv/Wm2Bixy1TCil4zHtmyxCYCdF03yATQzgdlwejGFVal0
RooAq7qqwMCGIGpEIQJBAP/uQYviod4LgjnLRGaa6P96ScQ/bIlbJmcyZLt3LYjY
i3bPaZ1oFA7292+Cic/iViZTZZdw3z5X0rtwTVQv8ykCQQDPXWMMyRPTHDFQpFOO
/aQeoLASehQNYXvosXA8RQtIu74Mg0SQLh7c2Z4YYz9JSaQyaxcPxsQWZJCv2j7O
BmzTAkBqRTeIa2HFPsgjUWkkpdxsAQ5SY/egjW3D2iQDx7fro+c9PWDgkJALqrcR
4YVyAcy9+1Eq8h5w16zUUgx6EbMJAkBp5HufXNOV2/DHCJNvEtGLnm0rklHJH34C
LxJshKmlg9IiW6pYomS6TRrxw0TfLQ7/fDZzpQIfmU1Vr/KgjSFnAkEArZ6qCgmB
HzwDHDKedkj0bj5IAwg+/IRWuOmogW31dD25BWRvOqWaZQGIjYzXzNZ5QjuYD57f
kWs9v65Uz1z1eQ==
-----END PRIVATE KEY-----';

    //公钥
    private static $public_key='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPTwOT15xFv3Klf/zAO7qP/1DC
UNns5bzh3sDltxQM/B6fpIVlHnrzRiAyAwWSXh21ILXBsJATVU+wy+XbbP+yS2tb
/JzsdpHJeWT98TVB/hRaJY916dT9jMwo8QUxCOuXudx8vHJOf38XeKgLyPYGkq5F
fCYySAohIYv5TxC2ywIDAQAB
-----END PUBLIC KEY-----';
    //$private_key = file_get_contents('private_key.pem');
    //$public_key = file_get_contents('rsa_public_key.pem');



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
    $private_key='-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAM9PA5PXnEW/cqV/
/MA7uo//UMJQ2ezlvOHewOW3FAz8Hp+khWUeevNGIDIDBZJeHbUgtcGwkBNVT7DL
5dts/7JLa1v8nOx2kcl5ZP3xNUH+FFolj3Xp1P2MzCjxBTEI65e53Hy8ck5/fxd4
qAvI9gaSrkV8JjJICiEhi/lPELbLAgMBAAECgYBfliUmJAJQPqgUG2FlGaU6BBUu
o6z4CC4BT35N7Q53tkBAh9FiAJ3cUfdCWBZXmMHF5GEp/8lOwMVP1ZQUiruSmojd
PF3aTUhO+LF55nwpAiSv/Wm2Bixy1TCil4zHtmyxCYCdF03yATQzgdlwejGFVal0
RooAq7qqwMCGIGpEIQJBAP/uQYviod4LgjnLRGaa6P96ScQ/bIlbJmcyZLt3LYjY
i3bPaZ1oFA7292+Cic/iViZTZZdw3z5X0rtwTVQv8ykCQQDPXWMMyRPTHDFQpFOO
/aQeoLASehQNYXvosXA8RQtIu74Mg0SQLh7c2Z4YYz9JSaQyaxcPxsQWZJCv2j7O
BmzTAkBqRTeIa2HFPsgjUWkkpdxsAQ5SY/egjW3D2iQDx7fro+c9PWDgkJALqrcR
4YVyAcy9+1Eq8h5w16zUUgx6EbMJAkBp5HufXNOV2/DHCJNvEtGLnm0rklHJH34C
LxJshKmlg9IiW6pYomS6TRrxw0TfLQ7/fDZzpQIfmU1Vr/KgjSFnAkEArZ6qCgmB
HzwDHDKedkj0bj5IAwg+/IRWuOmogW31dD25BWRvOqWaZQGIjYzXzNZ5QjuYD57f
kWs9v65Uz1z1eQ==
-----END PRIVATE KEY-----';

    //公钥
    $public_key='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPTwOT15xFv3Klf/zAO7qP/1DC
UNns5bzh3sDltxQM/B6fpIVlHnrzRiAyAwWSXh21ILXBsJATVU+wy+XbbP+yS2tb
/JzsdpHJeWT98TVB/hRaJY916dT9jMwo8QUxCOuXudx8vHJOf38XeKgLyPYGkq5F
fCYySAohIYv5TxC2ywIDAQAB
-----END PUBLIC KEY-----';


$rsa =new rsa;
var_dump($rsa->privateEncrypt($private_key,'1ee1e1'));
var_dump($rsa->publicDecrypt($public_key,'uhb+d+YoPRol04TmW8LGNH99Mue0be/iji0lBToCNIm45I4wZVR9m3O/tvOA+OR7BkC7hHnXlPbaMGwy9LbdNaRPCPo8lK3/EQ85qgd6IGkTlQf6uml2fG0fr7zdfvyoM4q4tW5JORiqA2x3YQBJMT7iHg4ok8RYJ+hchi9CFtQ='));


var_dump($rsa->publicEncrypt($public_key,'1ee1e1'));
var_dump($rsa->privateDecrypt($private_key,'vxXycEJOj9rtzPLQrF42kF2ihm8ekvhbky3qwjYj8xrzbbVNcqNJwwrUdlEi1KCkIbUIfiNdKAlUjIKovhTFUab2QH7g5S59xMLJSArw45UC5TtwA99d60PFI3Y9Y7WN9FLLSuoyVLTtIaBKDynIQl+j5TpPCT1ymCgoeEn83iQ='));
exit;

```

---


# Javascript 实现RSA加密解密
> [参考：https://github.com/travist/jsencrypt](https://github.com/travist/jsencrypt)

```html

<!doctype html>
<html>
  <head>
    <title>JavaScript RSA Encryption</title>
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script src="http://travistidwell.com/jsencrypt/bin/jsencrypt.js"></script>
    <script type="text/javascript">

      // Call this code when the page is done loading.
      $(function() {

        // Run a quick encryption/decryption when they click.
        $('#testme').click(function() {

          // Encrypt with the public key...
          var encrypt = new JSEncrypt();
          encrypt.setPublicKey($('#pubkey').val());
          var encrypted = encrypt.encrypt($('#input').val());
          console.log(encrypted);

          // Decrypt with the private key...
          var decrypt = new JSEncrypt();
          decrypt.setPrivateKey($('#privkey').val());
          var uncrypted = decrypt.decrypt(encrypted);
          console.log(uncrypted);

          // Now a simple check to see if the round-trip worked.
          if (uncrypted == $('#input').val()) {
            alert('It works!!!');
          }
          else {
            alert('Something went wrong....');
          }
        });
      });
    </script>
  </head>
  <body>
    <label for="privkey">Private Key</label><br/>
    <textarea id="privkey" rows="15" cols="65">-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAM9PA5PXnEW/cqV/
/MA7uo//UMJQ2ezlvOHewOW3FAz8Hp+khWUeevNGIDIDBZJeHbUgtcGwkBNVT7DL
5dts/7JLa1v8nOx2kcl5ZP3xNUH+FFolj3Xp1P2MzCjxBTEI65e53Hy8ck5/fxd4
qAvI9gaSrkV8JjJICiEhi/lPELbLAgMBAAECgYBfliUmJAJQPqgUG2FlGaU6BBUu
o6z4CC4BT35N7Q53tkBAh9FiAJ3cUfdCWBZXmMHF5GEp/8lOwMVP1ZQUiruSmojd
PF3aTUhO+LF55nwpAiSv/Wm2Bixy1TCil4zHtmyxCYCdF03yATQzgdlwejGFVal0
RooAq7qqwMCGIGpEIQJBAP/uQYviod4LgjnLRGaa6P96ScQ/bIlbJmcyZLt3LYjY
i3bPaZ1oFA7292+Cic/iViZTZZdw3z5X0rtwTVQv8ykCQQDPXWMMyRPTHDFQpFOO
/aQeoLASehQNYXvosXA8RQtIu74Mg0SQLh7c2Z4YYz9JSaQyaxcPxsQWZJCv2j7O
BmzTAkBqRTeIa2HFPsgjUWkkpdxsAQ5SY/egjW3D2iQDx7fro+c9PWDgkJALqrcR
4YVyAcy9+1Eq8h5w16zUUgx6EbMJAkBp5HufXNOV2/DHCJNvEtGLnm0rklHJH34C
LxJshKmlg9IiW6pYomS6TRrxw0TfLQ7/fDZzpQIfmU1Vr/KgjSFnAkEArZ6qCgmB
HzwDHDKedkj0bj5IAwg+/IRWuOmogW31dD25BWRvOqWaZQGIjYzXzNZ5QjuYD57f
kWs9v65Uz1z1eQ==
-----END PRIVATE KEY-----</textarea><br/>
    <label for="pubkey">Public Key</label><br/>
    <textarea id="pubkey" rows="15" cols="65">-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPTwOT15xFv3Klf/zAO7qP/1DC
UNns5bzh3sDltxQM/B6fpIVlHnrzRiAyAwWSXh21ILXBsJATVU+wy+XbbP+yS2tb
/JzsdpHJeWT98TVB/hRaJY916dT9jMwo8QUxCOuXudx8vHJOf38XeKgLyPYGkq5F
fCYySAohIYv5TxC2ywIDAQAB
-----END PUBLIC KEY-----</textarea><br/>
    <label for="input">Text to encrypt:</label><br/>
    <textarea id="input" name="input" type="text" rows=4 cols=70>This is a test!</textarea><br/>
    <input id="testme" type="button" value="Test Me!!!" /><br/>
  </body>
</html>



//=============================================================
//
    $private_key='-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAM9PA5PXnEW/cqV/
/MA7uo//UMJQ2ezlvOHewOW3FAz8Hp+khWUeevNGIDIDBZJeHbUgtcGwkBNVT7DL
5dts/7JLa1v8nOx2kcl5ZP3xNUH+FFolj3Xp1P2MzCjxBTEI65e53Hy8ck5/fxd4
qAvI9gaSrkV8JjJICiEhi/lPELbLAgMBAAECgYBfliUmJAJQPqgUG2FlGaU6BBUu
o6z4CC4BT35N7Q53tkBAh9FiAJ3cUfdCWBZXmMHF5GEp/8lOwMVP1ZQUiruSmojd
PF3aTUhO+LF55nwpAiSv/Wm2Bixy1TCil4zHtmyxCYCdF03yATQzgdlwejGFVal0
RooAq7qqwMCGIGpEIQJBAP/uQYviod4LgjnLRGaa6P96ScQ/bIlbJmcyZLt3LYjY
i3bPaZ1oFA7292+Cic/iViZTZZdw3z5X0rtwTVQv8ykCQQDPXWMMyRPTHDFQpFOO
/aQeoLASehQNYXvosXA8RQtIu74Mg0SQLh7c2Z4YYz9JSaQyaxcPxsQWZJCv2j7O
BmzTAkBqRTeIa2HFPsgjUWkkpdxsAQ5SY/egjW3D2iQDx7fro+c9PWDgkJALqrcR
4YVyAcy9+1Eq8h5w16zUUgx6EbMJAkBp5HufXNOV2/DHCJNvEtGLnm0rklHJH34C
LxJshKmlg9IiW6pYomS6TRrxw0TfLQ7/fDZzpQIfmU1Vr/KgjSFnAkEArZ6qCgmB
HzwDHDKedkj0bj5IAwg+/IRWuOmogW31dD25BWRvOqWaZQGIjYzXzNZ5QjuYD57f
kWs9v65Uz1z1eQ==
-----END PRIVATE KEY-----';

    //公钥
    $public_key='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPTwOT15xFv3Klf/zAO7qP/1DC
UNns5bzh3sDltxQM/B6fpIVlHnrzRiAyAwWSXh21ILXBsJATVU+wy+XbbP+yS2tb
/JzsdpHJeWT98TVB/hRaJY916dT9jMwo8QUxCOuXudx8vHJOf38XeKgLyPYGkq5F
fCYySAohIYv5TxC2ywIDAQAB
-----END PUBLIC KEY-----';


```

