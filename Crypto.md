# Base家族

Base家族有：Base16、Base32、Base36、Base58、Base62、Base64、Base64Url、Base85、Ascii85、Base91、Base92

使用Python脚本：BaseCrack ，具体用法可以查看脚本的Readme。

# Rabbit

```
密文：U2FsdGVkX19mGsGlfI3nciNVpWZZRqZO2PYjJ1ZQuRqoiknyHSWeQv8ol0uRZP94
MqeD2xz+
密钥：Rabbit
```

工具使用`AES_DES_Rabbit_RC4_TripleDes`文件夹中的网页。

# OoK

```
Ook. Ook? Ook. Ook? Ook! Ook. Ook? Ook. Ook. Ook. Ook. Ook! Ook. Ook. Ook.
```

使用ook.py 解密。

```
python ook.py -0 file_name
```

# BrainFuck

类似于下面这种类型：

```
+++++ +++++ [->++ +++++ +++<] >++.+ +++++ .<+++ [->-- -<]>- -.+++ +++.<
++++[ ->+++ +<]>+ +++.< +++++ +++[- >---- ----< ]>--. .--.- -.-.- --.-.
+++++ +..-- -..<+ +++++ +[->+ +++++ +<]>+ +.<++ ++++[ ->--- ---<] >----
----- .---- -.<++ ++++[ ->+++ +++<] >++++ +++++ +++.< +++++ ++[-> -----
--<]> .++.- ----. <++++ +++[- >++++ +++<] >+++. --.<+ +++++ [->-- ----<
]>--- ----- ---.+ .<+++ +++[- >++++ ++<]> +++++ +++++ ++.<+ +++++ [->--
----< ]>--- ----- ---.- .++++ .<+++ +++[- >++++ ++<]> +++++ +++.< +++++
+[->- ----- <]>-- ----- ---.- ----- .++++ +++++ .---- ----. <++++ ++[->
+++++ +<]>+ +++++ +++++ +.<++ +++[- >++++ +<]>+ ++.<
```

解密：

```python
python ook.py -b file_name
```

# Quoted-printable

**Quoted-printable**可译为“可打印字符引用编码”，编码常用在电子邮件中，如：Content-Transfer-Encoding: quoted-printable ，它是MIME编码常见一种表示方法！ 在邮件里面我们常需要用可打印的ASCII字符 (如字母、数字与"=")表示各种编码格式下的字符！Quoted-printable将任何8-bit字节值可编码为3个字符：一个等号"="后跟随两个十六进制数字(0–9或A–F)表示该字节的数值。例如，ASCII码换页符（十进制值为12）可以表示为"=0C"， 等号"="（十进制值为61）必须表示为"=3D"，gb2312下“中”表示为=D6=D0。除了可打印ASCII字符与换行符以外，所有字符必须表示为这种格式。因为Quoted-printable编码简单、方便因此在电子邮件中应用广泛！

```
=E7=94=A8=E4=BD=A0=E9=82=A3=E7=81=AB=E7=83=AD=E7=9A=84=E5=98=B4=E5=94=87=E8=AE=A9=E6=88=91=E5=9C=A8=E5=8D=88=E5=A4=9C=E9=87=8C=E6=97=A0=E5=B0=BD=E7=9A=84=E9=94=80=E9=AD=82
```

python脚本如下：

```python
import quopri

a = '=E7=94=A8=E4=BD=A0=E9=82=A3=E7=81=AB=E7=83=AD=E7=9A=84=E5=98=B4=E5=94=87=E8=AE=A9=E6=88=91=E5=9C=A8=E5=8D=88=E5=A4=9C=E9=87=8C=E6=97=A0=E5=B0=BD=E7=9A=84=E9=94=80=E9=AD=82'
b= '用你那火热的嘴唇让我在午夜里无尽的销魂'
print(quopri.decodestring(a).decode('utf-8'))
print(quopri.encodestring(b.encode('utf-8')))
```

# 埃特巴什码（Atbash Cipher）

埃特巴什码（Atbash Cipher）其实可以视为下面要介绍的简单替换密码的特例，其原理为：它使用字母表中的最后一个字母代表第一个字母，倒数第二个字母代表第二个字母。在罗马字母表中，它是这样出现的：
明文：A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
密文：Z Y X W V U T S R Q P O N M L K J I H G F E D C B A
下面给出一个例子：
明文：the quick brown fox jumps over the lazy dog
密文：gsv jfrxp yildm ulc qfnkh levi gsv ozab wlt

python脚本：`Atbash Cipher.py`

# 当铺密码

当铺密码就是一种将中文和数字进行转化的密码，算法相当简单:当前汉字有多少笔画出头，就是转化成数字几。例如：
王：该字外面有 6 个出头的位置，所以该汉字对应的数字就是 6；
口：该字外面没有出头的位置，那就是0；
人：该字外面有 3 个出头的位置，所以该汉字对应的数字就是 3；

```
密文：王夫 井工 夫口 由中人 井中 夫夫 由中大
对应：67 84 70 123 82 77 125
翻译成 ASCII码： CTF{RM}
```

也可能是数字对应中文的笔画数。

# 元素周期表

flag对应  9 57 64

| 原子序数 | 符号 | 原子序数 | 符号 |
| -------- | :--- | -------- | ---- |
| 1        | H    | 60   | Nd   |
| 2        | He   | 61   | Pm   |
| 3        | Li   | 62   | Sm   |
| 4        | Be   | 63   | Eu   |
| 5        | B    | 64   | Gd   |
| 6        | C    | 65   | Tb   |
| 7        | N    | 66   | Dy   |
| 8        | O    | 67   | Ho   |
| 9        | F    | 68   | Er   |
| 10       | Ne   | 69   | Tm   |
| 11       | Na   | 70   | Yb   | 
| 12       | Mg   | 71   | Lu   | 
| 13       | Al   | 72   | Hf   | 
| 14       | Si   | 73   | Ta   | 
| 15       | P    | 74   | W    | 
| 16       | S    | 75   | Re   | 
| 17       | Cl   | 76   | Os   | 
| 18       | Ar   | 77   | Ir   | 
| 19       | K    | 78   | Pt   | 
| 20       | Ca   | 79   | Au   | 
| 21       | Sc   | 80   | Hg   | 
| 22       | Ti   | 81   | Tl   | 
| 23       | V    | 82   | Pb   | 
| 24       | Cr   | 83   | Bi   | 
| 25       | Mn   | 84   | Po   | 
| 26       | Fe   | 85   | At   | 
| 27       | Co   | 86   | Rn   | 
| 28       | Ni   | 87   | Fr   | 
| 29       | Cu   | 88   | Ra   | 
| 30       | Zn   | 89   | Ac   | 
| 31       | Ga   | 90   | Th   | 
| 32       | Ge   | 91   | Pa   | 
| 33       | As   | 92   | U    | 
| 34       | Se   | 93   | Np   | 
| 35       | Br   | 94   | Pu   | 
| 36       | Kr   | 95   | Am   | 
| 37       | Rb   | 96   | Cm   | 
| 38       | Sr   | 97   | Bk   | 
| 39       | Y    | 98   | Cf   | 
| 40       | Zr   | 99   | Es   | 
| 41       | Nb   | 100  | Fm   | 
| 42       | Mo   | 101  | Md   | 
| 43       | Tc   | 102  | No   | 
| 44       | Ru   | 103  | Lr   | 
| 45       | Rh   | 104  | Rf   |
| 46       | Pd   | 105  | Db   |
| 47       | Ag   | 106  | Sg   |
| 48       | Cd   | 107  | Bh   |
| 49       | In   | 108  | Hs   |
| 50       | Sn   | 109  | Mt   |
| 51       | Sb   | 110  | Ds   |
| 52       | Te   | 111  | Rg   |
| 53       | I    | 112  | Cn   |
| 54       | Xe   | 113  | Nh   |
| 55       | Cs   | 114  | Fl   |
| 56       | Ba   | 115  | Mc   |
| 57       | La   | 116  | Lv   |
| 58       | Ce   | 117  | Ts   |
| 59       | Pr   | 118  | Og   |

# RSA

RSA算法的具体描述如下：

（1）任意选取两个不同的大素数p和q计算乘积

![img](writeup\MISC\攻防世界\images\f0dac18152076624d87832b62709895c.svg)

（2）任意选取一个大整数e，满足

![img](writeup\MISC\攻防世界\images\c33d8c66364a636b051d82f0ee202a36.svg)

 ，整数e用做加密钥（注意：e的选取是很容易的，例如，所有大于p和q的素数都可用）；

（3）确定的解密钥d，满足

![img](writeup\MISC\攻防世界\images\da8649c0078a0a842779394d64011776.svg)

 ，即

![img](writeup\MISC\攻防世界\images\4dee3f4df52a81983db0e3c619f96058.svg)

 是一个任意的整数；所以，若知道e和

![img](writeup\MISC\攻防世界\images\679e809a0d964785d0aa4cfcb4218742.svg)

，则很容易计算出d；

（4）公开整数n和e，秘密保存d；

（5）将明文m（m<n是一个整数）加密成密文c，加密算法为

![img](writeup\MISC\攻防世界\images\5947116555169dc6fe9e3f5cdf347706.svg)

（6）将密文c解密为明文m，解密算法为 

![img](writeup\MISC\攻防世界\images\1a8b337167e4d4b2c23855d88ec4c67f.svg)

然而只根据n和e（注意：不是p和q）要计算出d是不可能的。因此，任何人都可对明文进行加密，但只有授权用户（知道d）才可对密文解密 。

## 求d、m、c

工具：`RSA-Tool 2 by tE!`，输入p、q和e来计算d，其中e是16进制数。需要注意的是根据p、q的值来选择Number Base采用的进制数。

Python脚本：RSA.py

`long_to_bytes`来把数字的明文转为字符。

```python
m = 2077392566271985655506271571624317
print(long_to_bytes(m)) #b'flag{b4by_R5A}'
```



