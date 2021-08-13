页面上一段php代码：

```php
<?php
        function replaceSpecialChar($strParam){
             $regex = "/(select|from|where|join|sleep|and|\s|union|,)/i";
             return preg_replace($regex,"",$strParam);
        }
        if(strlen($password)!=strlen(replaceSpecialChar($password))){
            die("sql inject error");
        }
        if($password==$_SESSION['password']){
            echo $flag;
        }else{
            echo "error";
        }
    ?>
```

当输入的密码等于session中密码时，输出flag。

只需要将`sessionid`删除，这样session中获取到的password就是空值，我们只需要输入空值，就能输出flag：

```
ctfshow{48769733-b4db-42dc-bf32-2731efd52d58}
```

