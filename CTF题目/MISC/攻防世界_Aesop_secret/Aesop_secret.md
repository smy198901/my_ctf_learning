https://adworld.xctf.org.cn/task/answer?type=misc&number=1&grade=1&id=5492&page=1

# 分析

下载附件，发现是一个gif文件。第一时间想到使用Stegsolve。分离出每一帧后，得到图片：

![捕获](images\捕获.PNG)

以为ISCC为flag，尝试了多次无效。

使用binwalk查看图片信息，同样一无所获。

使用`strings`命令查看字符串，发现最后有一串加密字符。

```
U2FsdGVkX19QwGkcgD0fTjZxgijRzQOGbCWALh4sRDec2w6xsY/ux53Vuj/AMZBDJ87qyZL5kAf1fmAH4Oe13Iu435bfRBuZgHpnRjTBn5+xsDHONiR3t0+Oa8yG/tOKJMNUauedvMyN4v4QKiFunw==
```

初看以为是base64，发现无法解密，查看writeup后，才知道是AES加密串，ISCC密钥，解密两次得到flag

```
flag{DugUpADiamondADeepDarkMine}
```

