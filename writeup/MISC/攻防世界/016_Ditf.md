https://adworld.xctf.org.cn/task/answer?type=misc&number=1&grade=1&id=5562&page=1

# 分析

附件是一张png图片

![image-20210731170832134](C:\my_ctf_learning\writeup\MISC\攻防世界\images\image-20210731170832134.png)

kali中使用binwalk查看，可以看到存在一个rar文件。

```
DECIMAL       HEXADECIMAL     DESCRIPTION
--------------------------------------------------------------------------------
0             0x0             PNG image, 926 x 1100, 8-bit/color RGB, non-interlaced
1822          0x71E           Zlib compressed data, default compression
989714        0xF1A12         RAR archive data, version 4.x, first volume type: MAIN_HEAD
```

使用forest分离文件，得到rar，解压发现需要密码。

使用Advance Archer Recovery暴力破解，同时查看是否有信息能得到密码。

发现图片是IHDR，尝试修改图片高度，得到密码：StRe1izia

![image-20210731171224459](C:\my_ctf_learning\writeup\MISC\攻防世界\images\image-20210731171224459.png)

解压rar文件，得到一个pcapng。

使用binwalk查看，发现一个png图片。

```
DECIMAL       HEXADECIMAL     DESCRIPTION
--------------------------------------------------------------------------------
1090181       0x10A285        MPEG transport stream data
1666431       0x196D7F        Zlib compressed data, default compression
1728092       0x1A5E5C        HTML document header
1728245       0x1A5EF5        HTML document footer
1732134       0x1A6E26        XML document, version: "1.0"
2219867       0x21DF5B        MPEG transport stream data
2622867       0x280593        MPEG transport stream data
3380654       0x3395AE        gzip compressed data, from Unix, last modified: 1970-01-01 00:00:00 (null date)
3385591       0x33A8F7        PNG image, 753 x 1033, 8-bit/color RGBA, non-interlaced
4057667       0x3DEA43        MPEG transport stream data
4444403       0x43D0F3        MPEG transport stream data
4836867       0x49CE03        MPEG transport stream data
5101878       0x4DD936        Certificate in DER format (x509 v3), header length: 4, sequence length: 1551
5103524       0x4DDFA4        Certificate in DER format (x509 v3), header length: 4, sequence length: 1752
5249179       0x50189B        MPEG transport stream data
```

在010editor中分离出png图片，发现没有内容。

查看writeup后，才知道是flag是隐藏在http流中的BASE64加密串。

在wireshark中搜索字符串png，在追踪http流，得到：

![image-20210731175239709](C:\my_ctf_learning\writeup\MISC\攻防世界\images\image-20210731175239709.png)

Base64解密得到flag:

```
flag{Oz_4nd_Hir0_lov3_For3ver}
```

