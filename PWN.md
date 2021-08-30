# 基本概念

## eip

存放当前指令的下一条指令的地址。

## plt

## got

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
