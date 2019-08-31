# JSAPI 鉴权
> 为了保证平台用户的信息安全，项目导航jsapi在使用前需要需要开发者先进行鉴权然后才能调用。

## 获取 access_token

生成签名之前必须先了解一下`jsapi_ticket`，`jsapi_ticket`是用于调用项目导航 JS-SDK 接口的临时票据。正常情况下，`jsapi_ticket`的有效期为 7200 秒，通过access_token来获取。由于获取`jsapi_ticket`的 api 调用次数非常有限，频繁刷新`jsapi_ticket`会导致 api 调用受限，影响自身业务，开发者必须在自己的服务全局缓存`jsapi_ticket`。

### 第一步：获取`access_token` `http` 请求方式: `GET`

```
http://host:port/open-api/app/token?grant_type=client_credential&appid=APPID&appsecret=APPSECRET
```

#### 请求参数

参数 | 类型 | 是否必填 | 说明
--- | --- | --- | --- 
appid | String | 是 | 第三方应用唯一凭证
appsecret | String | 是 | 第三方应用唯一凭证密钥，即appsecret

#### 返回参数

参数 | 类型 | 是否必填 | 说明
--- | --- | --- | --- 
access_token | String | 是 | 获取到的凭证

#### 代码示例：
```js
"status":"success",
"code":"200",
"message":"成功",
"data": 
      {"access_token":"bxLdikRXVbTPdHSM05e5u5sUoXNKd8-41ZO3MhKoyN5OfkWITDGgnr2fwJ0m9E8NYzWKVZvdVtaUgWvsdshFKA"}
}
```

### 第二步：获取`jsapi_ticket` `http` 请求方式: `GET`
```
http://host:port/open-api/app/getticket?type=jsapi&iss=admin&iat=1567235867&exp=1567243067&nbf=1567235867&sub=www.admin.com&jti=ACCESS_TOKEN
```

#### 请求参数

参数 | 类型 | 是否必填 | 说明
--- | --- | --- | --- 
iss | String | 是 | 该JWT的签发者
iat | Int | 是 | 签发时间
exp | Int | 是 | 过期时间
nbf | Int | 是 | 该时间之前不接收处理该Token
sub | String | 是 | 面向的用户
jti | String | 是 | 该Token唯一标识

#### 返回参数

参数 | 类型 | 是否必填 | 说明
--- | --- | --- | --- 
token | String | 是 | 获取到的临时票据
expires_in | String | 是 | 凭证有效时间，单位：秒

#### 代码示例：

```js
{
"status":"success",
"code":"200",
"message":"成功",
"data": 
      {"token":"bxLdikRXVbTPdHSM05e5u5sUoXNKd8-41ZO3MhKoyN5OfkWITDGgnr2fwJ0m9E8NYzWKVZvdVtaUgWvsdshFKA",
      "expires_in":7200}
}
```

----

## 签名验证
`http`请求方式: `GET`

```
http://host:port/open-api/app/checkSignature?appid=APPID&noncestr=NONCESTR&timestamp=TIMESTAMP&signature=SIGNATURE
```

#### 请求参数

参数 | 类型 | 是否必填 | 说明
--- | --- | --- | --- 
appId | String | 是 | 企业ID
timeStamp | String | 是 | 生成签名的时间戳
nonceStr | String | 是 | 生成签名的随机串
signature | String | 是 | JS-API签名，可以参考简易教程中的获取签名的示例代码

#### 返回参数

参数 | 类型 | 是否必填 | 说明
--- | --- | --- | --- 
success | String | 是 | 是否验证通过,true:成功，false:失败
domain_url | String | 是 | appId对应的有效域名地址
appid | String | 是 | 第三方应用唯一标识

----

## 签名算法

签名生成规则如下：参与签名的字段包括`noncestr`（随机字符串）, 有效的`jsapi_ticket`, `timestamp`（时间戳）, `appid`（第三方应用唯一凭证） 。对所有待签名参数按照字段名的`ASCII`码从小到大排序（字典序）后，使用URL键值对的格式 （即`key1=value1&key2=value2…`）拼接成字符串`stringA`。这里需要注意的是所有参数名区分大小写。对stringA进行MD5运算，再将得到的字符串所有字符转换为大写，得到sign值signValue。



**举例：**

假设传送的参数如下：
```
appid：	123456
jsapi_ticket：	IjEyMzQ1NiI.IjAwMDAwMCI.X-XzRmzakWhHNC1YB9CpQmfsUGVxtt3UkDk0N08bOGE
timestamp：	1567234956
body：	test
title：	biaoti
noncestr：	ibuaiVcKdpRxkhJA
```

第一步：对参数按照key=value的格式，并按照参数名ASCII字典序排序如下：
```
stringA="appid=123456&body=test&jsapi_ticket=IjEyMzQ1NiI.IjAwMDAwMCI.X-XzRmzakWhHNC1YB9CpQmfsUGVxtt3UkDk0N08bOGE&noncestr=ibuaiVcKdpRxkhJA&timestamp=1567234956&title=biaoti";
```

第二步：拼接API密钥：
```
stringSignTemp=stringA+"&key=192006250b4c09247ec02edce69f6a2d" //注：key为商户平台设置的密钥key

sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A9CF3B7" //注：MD5签名方式，将所有小写转换成大写

```