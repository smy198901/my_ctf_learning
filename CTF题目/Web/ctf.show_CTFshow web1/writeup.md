扫描后台得到源码包：www.zip

查看源码可以看到，登录和注册把SQL注入相关的都过滤掉了。

user_main.php，可以利用order by pwd来判断密码，当我们按照pwd排序时，比如 flag用户的密码为flag{123}，我们从小到大 一直到f都在他的上面，当我们注册的密码为g时，则出现第一个在下面的，则密码的第一个字母为f。

```php
<?php
	
	if(isset($_SESSION["login"]) && $_SESSION["login"] === true){
		$con = mysqli_connect("localhost","root","root","web15");
        if (!$con)
        {
            die('Could not connect: ' . mysqli_error());
        }
		$order=$_GET['order'];
		if(isset($order) && strlen($order)<6){
			if(preg_match("/group|union|select|from|or|and|regexp|substr|like|create|drop|\,|\`|\~|\!|\@|\#|\%|\^|\&|\*|\(|\)|\（|\）|\-|\_|\+|\=|\{|\}|\[|\]|\;|\:|\'|\’|\“|\"|\<|\>|\?|\,|\.|\?/i",$order)){
				die("error");
			}
			$sql="select * from user order by $order";
        }else{
            $sql="select * from user order by id";
        }   

?>
```

python脚本见：test.py

flag: ctfshow{aac161e9-513a-4f80-8925-f987d598dff2}

