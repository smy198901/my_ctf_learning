# 基本概念

## eip

存放当前指令的下一条指令的地址。

## plt和got

plt表为（Procedure Link Table），是程序链接表。而got表为（Global Offset Table），是一个存储外部库函数的表，全局偏移表。

当程序在第一次运行的时候，会进入已被转载进内存中的动态链接库中查找对应的函数和地址，并把函数的地址放到got表中，将got表的地址数据映射为plt表的表项；在程序二次运行的时候，就不用再重新查找函数地址，而是直接通过plt表找到got表中函数的地址，从而执行函数的功能了。

# pwntools

**一定要在Kali中运行python脚本，否则将无法执行shell命名。**

官网的一个简单样例

```
from pwn import * #用来导入pwntools模块
context(arch = 'i386', os = 'linux')  #设置目标机的信息

r = remote('exploitme.example.com', 31337) #用来建立一个远程连接，url或者ip作为地址，然后指明端口
# EXPLOIT CODE GOES HERE
r.send(asm(shellcraft.sh())) #asm()函数接收一个字符串作为参数，得到汇编码的机器代码。
r.interactive() #将控制权交给用户，这样就可以使用打开的shell了
```

使用本地文件：

```
r = process("./test") #test即为文件名,这使得改变远程和本地十分方便.
```

shellcraft模块是shellcode的模块，包含一些生成shellcode的函数。

其中的子模块声明架构，比如shellcraft.arm 是ARM架构的，shellcraft.amd64是AMD64架构，shellcraft.i386是Intel 80386架构的，以及有一个shellcraft.common是所有架构通用的。

而这里的shellcraft.sh()则是执行/bin/sh的shellcode了

r.send()将shellcode发送到远程连接

## Context设置

`context`是pwntools用来设置环境的功能。在很多时候，由于二进制文件的情况不同，我们可能需要进行一些环境设置才能够正常运行exp，比如有一些需要进行汇编，但是32的汇编和64的汇编不同，如果不设置context会导致一些问题。

一般来说我们设置context只需要简单的一句话:

```
context(os='linux', arch='amd64', log_level='debug')
```

``这句话的意思是：

\1. os设置系统为linux系统，在完成ctf题目的时候，大多数pwn题目的系统都是linux
\2. arch设置架构为amd64，可以简单的认为设置为64位的模式，对应的32位模式是’i386’
\3. log_level设置日志输出的等级为debug，这句话在调试的时候一般会设置，这样pwntools会将完整的io过程都打印下来，使得调试更加方便，可以避免在完成CTF题目时出现一些和IO相关的错误。

## 数据打包

数据打包,即将整数值转换为32位或者64位地址一样的表示方式,比如0x400010表示为\x10\x00\x40一样,这使得我们构造payload变得很方便

用法:
\* `p32/p64`: 打包一个整数,分别打包为32或64位
\* `u32/u64`: 解包一个字符串,得到整数

p对应pack,打包,u对应unpack,解包,简单好记

```
payload = p32(0xdeadbeef) # pack 32 bits number
```

## 数据输出

如果需要输出一些信息,最好使用pwntools自带的,因为和pwntools本来的格式吻合,看起来也比较舒服,用法:

```
some_str = "hello, world"
log.info(some_str)
```

``其中的info代表是log等级，也可以使用其他log等级。

Cyclic Pattern

Cyclic pattern是一个很强大的功能，大概意思就是，使用pwntools生成一个pattern，pattern就是指一个字符串，可以通过其中的一部分数据去定位到他在一个字符串中的位置。

在我们完成栈溢出题目的时候，使用pattern可以大大的减少计算溢出点的时间。
用法：

```
cyclic(0x100) # 生成一个0x100大小的pattern，即一个特殊的字符串
cyclic_find(0x61616161) # 找到该数据在pattern中的位置
cyclic_find('aaaa') # 查找位置也可以使用字符串去定位
```

``比如，我们在栈溢出的时候，首先构造`cyclic(0x100)`，或者更长长度的pattern，进行输入，输入后pc的值变味了0x61616161，那么我们通过`cyclic_find(0x61616161)`就可以得到从哪一个字节开始会控制PC寄存器了，避免了很多没必要的计算。

## 汇编与shellcode

有的时候我们需要在写exp的时候用到简单的shellcode，pwntools提供了对简单的shellcode的支持。
首先，常用的，也是最简单的shellcode，即调用`/bin/sh`可以通过shellcraft得到：

注意，由于各个平台，特别是32位和64位的shellcode不一样，所以最好先设置context。

```
print(shellcraft.sh()) # 打印出shellcode
```

``不过，现在我们看到的shellcode还是汇编代码，不是能用的机器码，所以还需要进行一次汇编

```
print(asm(shellcraft.sh())) # 打印出汇编后的shellcode
```

``asm可以对汇编代码进行汇编，不过pwntools目前的asm实现还有一些缺陷，比如不能支持相对跳转等等，只可以进行简单的汇编操作。如果需要更复杂一些的汇编功能，可以使用`keystone-engine`项目，这里就不再赘述了。

asm也是架构相关，所以一定要先设置context，避免一些意想不到的错误。

**在32位程序运行中，函数参数直接压入栈中，调用函数时栈的结构为：调用函数地址 --- 函数返回的地址 -- 参数n -- 参数n-1 -- ... -- 参数1**，**函数的返回地址一般为0**

## symbols

symbols就是函数的调用地址，对于在程序内部的方法，symbols地址就是方法的入口地址，对于外部通过动态链接的方法，symbols地址就是其plt地址，它真正的入口地址在got表中（第二次调用及以上）。

# pwndbg

*为可选 黑色 为gdb原生命令 绿色 为 pwndbg 或 peda 插件命令

| 命令                | 缩写 | 效果                                                      |
| :------------------ | :--- | :-------------------------------------------------------- |
| gdb <file> <*pid>   |      | 添加新程序                                                |
| gdb attach <pid>    |      | 负载运行的程序                                            |
| set args <*argv>    |      | 设置程序运行参数                                          |
| show args           |      | 查看设置好的运行参数                                      |
| quit                | q    | 退出gdb                                                   |
| symbol <file>       | sy   | 导入符号表                                                |
| info <*b>           | i    | 查看程序的状态/*查看断点                                  |
| frame               | f    | 查看栈帧                                                  |
| backtrace           | bt   | 查看堆栈情况                                              |
| list                | l    | 显示源代码 (debug模式)                                    |
| display             | disp | 跟踪查看某个变量                                          |
| start               | s    | 启动程序并中断在入口 debug模式停在main()，否则停在start() |
| run                 | r    | 直接运行程序直到断点                                      |
| continue            | c    | 暂停后继续执行程序                                        |
| next                | n    | 单步步过                                                  |
| step                | s    | 单步步入，函数跟踪                                        |
| finish              | fin  | 跳出，执行到函数返回处                                    |
| break /*<addr>      | b    | 下断点                                                    |
| watch               |      | 下内存断点并监视内存情况                                  |
| print               | p    | 打印符号信息(debug模式)                                   |
| i r a               |      | 查看所有寄存器                                            |
| i r <esp/ebp..>     |      | 查看某个寄存器                                            |
| set $esp = 0x01     |      | 修改某个寄存器的值                                        |
| heap                |      | 查看分配的chunk                                           |
| vmmap               |      | 查看内存分配情况                                          |
| bin                 |      | 查看 Bin 情况                                             |
| x /<num><n/f/u>     |      | 显示内存信息，具体用法附在下面                            |
| context             |      | 打印 pwnbdg 页面信息                                      |
| dps <addr>          |      | 优雅地显示内存信息                                        |
| disassemble <func>  |      | 打印函数信息                                              |
| vmmap               |      | 显示程序内存结构                                          |
| search <*argv>      |      | 搜索内存中的值 输入 search -h 可查询用法                  |
| checksec            |      | 查看程序保护机制                                          |
| parseheap           |      | 优雅地查看分配的chunk                                     |
| aslr <on/off>       |      | 打开/关闭 ASLR 保护                                       |
| pshow               |      | 显示各种踏板选项和其他设置                                |
| dumpargs <num>      |      | 显示在调用指令处停止时传递给函数的参数                    |
| dumprop <from> <to> |      | 显示特定内存范围内的所有ROP gadgets                       |
| elfheader           |      | 从调试的elf文件获取头信息                                 |
| elfsymbol           |      | 从ELF文件获取非调试符号信息                               |
| procinfo            |      | 显示来自/proc/pid的各种信息                               |
| readelf             |      | 从elf文件获取头信息                                       |

x指令的具体用法：n、f、u为控制打印形式的参数

'num' 表示打印的数量

'n' 代表打印格式，可为o(八进制),x(十六进制),d(十进制),u(无符号十进制),t(二进制),f(浮点类型),a(地址类型),i(解析成命令并反编译),c(字符)和s(字符串)

'f' 用来设定输出长度，`b(byte),h(halfword),w(word),giant(8bytes)`。

'u' 指定单位内存单元的字节数(默认为dword) 可用`b(byte),h(halfword),w(word),giant(8bytes)`替代

x指令也可以显示地址上的指令信息，用法：x/i

# 栈溢出

## 常见函数

