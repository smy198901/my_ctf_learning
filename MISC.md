# 正则表达式

工具：Tools->AWD->regulex->docs->index.html

可以快速帮助分析正则表达式。

# 协议分析

1、binwalk查看是否存在文件。例如：flag.txt

2、strings查看异常字符串，如flag的base64编码：ZmxhZw==，flag的16进制：666c6167

3、追踪流。

4、对流量长度进行排序，查看异常的长度。

# 文章

该类型题目，给出一篇文章。

## .rock

## 大小写

文章内部分字符采用大写，其余均为小写。提取大写字母分析。

例如：攻防世界_sherlock

# 文件头

## 常用文件头和尾

**JPEG (jpg)，**
文件头：FFD8FF　　 文件尾：FF D9　
　　　　　　　　　　　　　　
**PNG (png)，** 　
文件头：89504E47　 文件尾：AE 42 60 82

**GIF (gif)，** 　
文件头：47494638　 文件尾：00 3B

**ZIP Archive (zip)，**
文件头：504B0304　　 文件尾：50 4B

**TIFF (tif)，** 
文件头：49492A00

Windows Bitmap (**bmp)，** 
文件头：424D
　　　　　　
**CAD (dwg)，** 
文件头：41433130　
　　　　　　　　　　　　　　　　　　　　　
Adobe Photoshop **(psd)，**
文件头：38425053　
　　　　　　　　　　　　　　　　　　　　　
**Rich Text Format (rtf)**，
文件头：7B5C727466　
　　　　　　　　　　　　　　　　　　　
**XML (xml)，**
文件头：3C3F786D6C　
　　　　　　　　　　　　　　　　　　　
**HTML (html)，**
文件头：68746D6C3E

**Email [thorough only] (eml)**，
文件头：44656C69766572792D646174653A

**Outlook Express (dbx)，**
文件头：CFAD12FEC5FD746F
**Outlook (pst)**，
文件头：2142444E

**MS Word/Excel (\**xls.or.doc\**)，**
文件头：D0CF11E0

**MS Access (\**mdb\**)**，
文件头：5374616E64617264204A

**WordPerfect (\**wpd\**)，**
文件头：FF575043

**Adobe Acrobat \**(pdf\**)，**
文件头：255044462D312E

**Quicken \**(qdf)，\****
文件头：AC9EBD8F

**Windows Password (\**pwl)\**，**
文件头：E3828596

**RAR Archive (rar)**，
文件头：52617221

**Wave (wav)，** 文件头：57415645
**AVI (avi)，** 文件头：41564920
**Real Audio (ram)，** 文件头：2E7261FD
**Real Media (rm)**， 文件头：2E524D46
**MPEG (mpg)**， 文件头：000001BA
**MPEG (mpg)，** 文件头：000001B3
**Quicktime (mov)，** 文件头：6D6F6F76
**Windows Media (asf)，** 文件头：3026B2758E66CF11
**MIDI (mid)，** 文件头：4D546864

**PYC**（Python编译文件），文件头：03F30D0A

## 偏移

文件无法打开，对比文件头，存在偏移。

见：攻防世界_banana-princess

