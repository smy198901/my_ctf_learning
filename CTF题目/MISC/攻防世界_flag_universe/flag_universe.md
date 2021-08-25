https://adworld.xctf.org.cn/task/answer?type=misc&number=1&grade=1&id=5458&page=2

# 分析

使用binwalk查看文件

```
DECIMAL       HEXADECIMAL     DESCRIPTION
--------------------------------------------------------------------------------
10646         0x2996          PNG image, 1200 x 801, 8-bit/color RGB, non-interlaced
1162634       0x11BD8A        PNG image, 1200 x 801, 8-bit/color RGB, non-interlaced
1206154       0x12678A        PNG image, 1200 x 801, 8-bit/color RGB, non-interlaced
2361778       0x2409B2        PNG image, 1200 x 801, 8-bit/color RGB, non-interlaced
2382990       0x245C8E        PNG image, 1200 x 801, 8-bit/color RGB, non-interlaced
3528970       0x35D90A        PNG image, 1200 x 801, 8-bit/color RGB, non-interlaced
```

发现存在多张图片。

在wireshark中追踪TCP流，在流3、4、6、11、13、14处存在图片，保存成图片。、

扔到Stegsolve中，部分图片不全，无法使用。

使用zsteg分别查看，流14的图片发现flag

```
[!] ZPNG::ScanLine: #800: no data at pos 0, scanline dropped
imagedata           .. text: "\n\n\n111???"
b1,r,lsb,xy         .. text: "F2&*rq.9Qz"
b1,rgb,lsb,xy       .. text: "flag{Plate_err_klaus_Mail_Life}\n"
b3,g,msb,xy         .. file: PGP Secret Sub-key -
b3,b,msb,xy         .. text: "zC`)XUWS"
```

得到flag:

```
flag{Plate_err_klaus_Mail_Life}
```

