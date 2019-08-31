<?php
/*
 * @Title: PHP实现jwt
 * @Author: yyy@300c.cn
 * @Date: 2019-08-31 12:13:14
 * @LastEditors: yyy@300c.cn
 * @LastEditTime: 2019-08-31 15:44:22
 */
class Jwt {

    //头部(不需要动)
    private static $header=array(
        'alg'=>'HS256', //生成signature的算法
        'typ'=>'JWT'    //类型
    );

    //使用HMAC生成信息摘要时所使用的密钥(每个项目最好不一致)
    private static $key='123456';



    

    // 获取帐号token=========================


    /**
     * 获取用户token access_token
     * @param array $data jwt载荷   格式如下非必须
     * [
     *  'appid'=>'123456',  //第三方应用唯一凭证
     *  'appsecret'=>000000,  //第三方应用唯一凭证密钥，即appsecret
     * ]
     * @return bool|string
     */
    public static function accessToken($data)
    {
        if(is_array($data))
        {
            $base64appid=self::base64UrlEncode(json_encode($data['appid'],JSON_UNESCAPED_UNICODE));
            $base64appsecret=self::base64UrlEncode(json_encode($data['appsecret'],JSON_UNESCAPED_UNICODE));
            $token=$base64appid.'.'.$base64appsecret.'.'.self::signature($base64appid.'.'.$base64appsecret,self::$key,self::$header['alg']);
            $data = ['access_token' => $token];
            return self::_return_status(200,$data);
        }else{
            return false;
        }
    }


    /**
     * 验证token的值是否有效并且同时获取appid和appsecret
     * @param string $Token 需要验证的token
     * @return bool|string
     * [
     *  'appid'=>'123456',  //第三方应用唯一凭证
     *  'appsecret'=>000000,  //第三方应用唯一凭证密钥，即appsecret
     * ]
     */
    public static function verifyaccessToken($Token)
    {
        $tokens = explode('.', $Token);
        if (count($tokens) != 3)
            return false;

        list($base64appid, $base64appsecret, $sign) = $tokens;

        //签名验证
        if (self::signature($base64appid . '.' . $base64appsecret, self::$key, self::$header['alg']) !== $sign)
            return false;

        $payload['appid'] = json_decode(self::base64UrlDecode($base64appid), JSON_OBJECT_AS_ARRAY);
        $payload['appsecret'] = json_decode(self::base64UrlDecode($base64appsecret), JSON_OBJECT_AS_ARRAY);

        return $payload;
    }

    // 获取帐号token=========================




    /**
     * 获取jwt token
     * @param array $payload jwt载荷   格式如下非必须
     * [
     *  'iss'=>'jwt_admin',  //该JWT的签发者
     *  'iat'=>time(),  //签发时间
     *  'exp'=>time()+7200,  //过期时间
     *  'nbf'=>time()+60,  //该时间之前不接收处理该Token
     *  'sub'=>'www.admin.com',  //面向的用户
     *  'jti'=>md5(uniqid('JWT').time())  //该Token唯一标识
     * ]
     * @return bool|string
     */
    public static function getToken($payload)
    {
        if(is_array($payload))
        {
            $base64header=self::base64UrlEncode(json_encode(self::$header,JSON_UNESCAPED_UNICODE));
            $base64payload=self::base64UrlEncode(json_encode($payload,JSON_UNESCAPED_UNICODE));
            $token=$base64header.'.'.$base64payload.'.'.self::signature($base64header.'.'.$base64payload,self::$key,self::$header['alg']);

            $data = ['jsapi_ticket' => $token, 'expires_in' => $payload['exp'],];
            return self::_return_status(200, $data,'');
        }else{
            return false;
        }
    }


    /**
     * 验证token是否有效,默认验证exp,nbf,iat时间
     * @param string $Token 需要验证的token
     * @return bool|string
     */
    public static function verifyToken($Token)
    {
        $tokens = explode('.', $Token);
        if (count($tokens) != 3)
            return false;

        list($base64header, $base64payload, $sign) = $tokens;

        //获取jwt算法
        $base64decodeheader = json_decode(self::base64UrlDecode($base64header), JSON_OBJECT_AS_ARRAY);
        if (empty($base64decodeheader['alg']))
            return false;

        //签名验证
        if (self::signature($base64header . '.' . $base64payload, self::$key, $base64decodeheader['alg']) !== $sign)
            return false;

        $payload = json_decode(self::base64UrlDecode($base64payload), JSON_OBJECT_AS_ARRAY);

        //签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time())
            return false;

        //过期时间小宇当前服务器时间验证失败
        if (isset($payload['exp']) && $payload['exp'] < time())
            return false;

        //该nbf时间之前不接收处理该Token
        if (isset($payload['nbf']) && $payload['nbf'] > time())
            return false;

        return self::_return_status(200, $payload);
    }




    /**
     * base64UrlEncode   https://jwt.io/  中base64UrlEncode编码实现
     * @param string $input 需要编码的字符串
     * @return string
     */
    private static function base64UrlEncode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * base64UrlEncode  https://jwt.io/  中base64UrlEncode解码实现
     * @param string $input 需要解码的字符串
     * @return bool|string
     */
    private static function base64UrlDecode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * HMACSHA256签名   https://jwt.io/  中HMACSHA256签名实现
     * @param string $input 为base64UrlEncode(header).".".base64UrlEncode(payload)
     * @param string $key
     * @param string $alg   算法方式
     * @return mixed
     */
    private static function signature($input, $key, $alg = 'HS256')
    {
        $alg_config=array(
            'HS256'=>'sha256'
        );
        return self::base64UrlEncode(hash_hmac($alg_config[$alg], $input, $key,true));
    }




    /*
	 * 私有返回状态_返回状态
	 * 
	 * @status [状态] 200操作成功
	 * @param  [type] $status [*状态]
	 * @param  [type] $data [数据组]
	 * @param  [type] $page [翻页数据]
	 * @param  [type] $message [返回信息]
	 *
	 * ------------------- eg:start ---------------------
	 * $data = [
	 *     'status' => 'success/error（成功/失败）',
	 *     'code'      => '状态码',
	 *     'message'      => '详细信息',
	 *     'data'      => '返回数据',
	 *     'page'      => '翻页',
	 * ];
	 * ------------------- eg:end -----------------------
	 *
	 * @return [json] [json]
	 */
    private static function _return_status($status = '', $data = '', $pages = '', $message = '')
    {
        $status = $status;    //状态
        $pages = $pages;    //成功：返回数据组
        $data = $data;    //成功：返回数据组
        //==================	操作失败-验证 START
        switch ($status) {
            case 200:    //操作成功
                $result = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => '操作成功',
                ];
                if ($message) {
                    $result['message'] = $message;
                }
                if ($data) {
                    $result['data'] = $data;
                }
                if ($pages) {
                    $result['page'] = $pages;
                }

                return json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                break;

            case -101:    //签名错误
                $result = [
                    'status' => 'error',
                    'code' => -101,
                    'message' => '错误',
                ];
                return json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                break;

            default:
                $result = [
                    'status' => 'error',
                    'code' => -100,
                    'message' => '操作失败',    //帐号已锁定,无法登录
                ];
                return json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                break;
        }
        //==================	操作失败-验证 END
    }



}



