IDA静态分析文件:

```c
void authenticate()
{
  wchar_t ws[8192]; // [esp+1Ch] [ebp-800Ch] BYREF
  wchar_t *s2; // [esp+801Ch] [ebp-Ch]

  s2 = decrypt((wchar_t *)&s, (wchar_t *)&dword_8048A90);
  if ( fgetws(ws, 0x2000, stdin) )
  {
    ws[wcslen(ws) - 1] = 0;
    if ( !wcscmp(ws, s2) )
      wprintf(&unk_8048B44); //输出success，s2的值就是flag
    else
      wprintf(&unk_8048BA4); //输出access denied
  }
  free(s2);
}


wchar_t *__cdecl decrypt(wchar_t *s, wchar_t *a2)
{
  size_t v2; // eax
  signed int v4; // [esp+1Ch] [ebp-1Ch]
  signed int i; // [esp+20h] [ebp-18h]
  signed int v6; // [esp+24h] [ebp-14h]
  signed int v7; // [esp+28h] [ebp-10h]
  wchar_t *dest; // [esp+2Ch] [ebp-Ch]

  v6 = wcslen(s);
  v7 = wcslen(a2);
  v2 = wcslen(s);
  dest = (wchar_t *)malloc(v2 + 1);
  wcscpy(dest, s);
  while ( v4 < v6 )
  {
    for ( i = 0; i < v7 && v4 < v6; ++i )
      dest[v4++] -= a2[i];
  }
  return dest;
}
```

直接编写脚本实现decrypt函数十分困难，根据题目提示“菜鸡听说有的程序运行就能拿Flag？”，尝试动态调试程序。

![image-20210826224313316](images\a.id1)

查看decrypt函数的汇编语句，可以看到把dest的值放到了寄存器eax中，也就是运行完decrypt函数，查看eax中的值就是flag。

pwndbg动态调试。

```shell
gdb a -q

#设置断点
b decrypt
# 运行到断点处
r
# 结束运行函数，到返回处。
fin
# 查看寄存器信息
i r
# 查看寄存器eax的值。得到flag：9447{you_are_an_international_mystery}
x/5sw $eax
```

执行过程如下：

```
 gdb a -q
pwndbg: loaded 195 commands. Type pwndbg [filter] for a list.
pwndbg: created $rebase, $ida gdb functions (can be used with print/break)
Reading symbols from a...
(No debugging symbols found in a)
pwndbg> b decrypt
Breakpoint 1 at 0x804865c
pwndbg> r
Starting program: /smy/a 
Welcome to cyber malware control software.
Currently tracking 531218249 bots worldwide

Breakpoint 1, 0x0804865c in decrypt ()
LEGEND: STACK | HEAP | CODE | DATA | RWX | RODATA
───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ REGISTERS ]────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
 EAX  0x25
 EBX  0x0
 ECX  0xf7faf4e0 (_IO_wide_data_1) —▸ 0x804c000 ◂— 0x50 /* 'P' */
 EDX  0x0
 EDI  0xf7faf000 (_GLOBAL_OFFSET_TABLE_) ◂— 0x1e4d6c
 ESI  0xf7faf000 (_GLOBAL_OFFSET_TABLE_) ◂— 0x1e4d6c
 EBP  0xffff54f8 —▸ 0xffffd528 —▸ 0xffffd548 ◂— 0x0
 ESP  0xffff54f4 ◂— 0x0
 EIP  0x804865c (decrypt+4) ◂— sub    esp, 0x34
─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ DISASM ]─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
 ► 0x804865c <decrypt+4>     sub    esp, 0x34
   0x804865f <decrypt+7>     mov    eax, dword ptr [ebp + 8]
   0x8048662 <decrypt+10>    mov    dword ptr [esp], eax
   0x8048665 <decrypt+13>    call   wcslen@plt                     <wcslen@plt>
 
   0x804866a <decrypt+18>    mov    dword ptr [ebp - 0x14], eax
   0x804866d <decrypt+21>    mov    eax, dword ptr [ebp + 0xc]
   0x8048670 <decrypt+24>    mov    dword ptr [esp], eax
   0x8048673 <decrypt+27>    call   wcslen@plt                     <wcslen@plt>
 
   0x8048678 <decrypt+32>    mov    dword ptr [ebp - 0x10], eax
   0x804867b <decrypt+35>    mov    ebx, dword ptr [ebp + 8]
   0x804867e <decrypt+38>    mov    eax, dword ptr [ebp + 8]
─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ STACK ]──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
00:0000│ esp 0xffff54f4 ◂— 0x0
01:0004│ ebp 0xffff54f8 —▸ 0xffffd528 —▸ 0xffffd548 ◂— 0x0
02:0008│     0xffff54fc —▸ 0x8048725 (authenticate+29) ◂— mov    dword ptr [ebp - 0xc], eax
03:000c│     0xffff5500 —▸ 0x8048aa8 ◂— cmp    dl, byte ptr [eax + eax]
04:0010│     0xffff5504 —▸ 0x8048a90 ◂— add    dword ptr [eax + eax], edx
05:0014│     0xffff5508 ◂— 0x0
... ↓        2 skipped
───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ BACKTRACE ]────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
 ► f 0 0x804865c decrypt+4
   f 1 0x8048725 authenticate+29
   f 2 0x80487d5 main+44
   f 3 0xf7de8e46 __libc_start_main+262
────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
pwndbg> fin
Run till exit from #0  0x0804865c in decrypt ()
0x08048725 in authenticate ()
LEGEND: STACK | HEAP | CODE | DATA | RWX | RODATA
───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ REGISTERS ]────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
*EAX  0x804d010 ◂— 0x39 /* '9' */
 EBX  0x0
*ECX  0x1480
*EDX  0x7d
 EDI  0xf7faf000 (_GLOBAL_OFFSET_TABLE_) ◂— 0x1e4d6c
 ESI  0xf7faf000 (_GLOBAL_OFFSET_TABLE_) ◂— 0x1e4d6c
*EBP  0xffffd528 —▸ 0xffffd548 ◂— 0x0
*ESP  0xffff5500 —▸ 0x8048aa8 ◂— cmp    dl, byte ptr [eax + eax]
*EIP  0x8048725 (authenticate+29) ◂— mov    dword ptr [ebp - 0xc], eax
─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ DISASM ]─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
 ► 0x8048725 <authenticate+29>    mov    dword ptr [ebp - 0xc], eax
   0x8048728 <authenticate+32>    mov    eax, dword ptr [stdin@@GLIBC_2.0] <0x804a03c>
   0x804872d <authenticate+37>    mov    dword ptr [esp + 8], eax
   0x8048731 <authenticate+41>    mov    dword ptr [esp + 4], 0x2000
   0x8048739 <authenticate+49>    lea    eax, [ebp - 0x800c]
   0x804873f <authenticate+55>    mov    dword ptr [esp], eax
   0x8048742 <authenticate+58>    call   fgetws@plt                     <fgetws@plt>
 
   0x8048747 <authenticate+63>    test   eax, eax
   0x8048749 <authenticate+65>    je     authenticate+148                     <authenticate+148>
 
   0x804874b <authenticate+67>    lea    eax, [ebp - 0x800c]
   0x8048751 <authenticate+73>    mov    dword ptr [esp], eax
─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ STACK ]──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
00:0000│ esp 0xffff5500 —▸ 0x8048aa8 ◂— cmp    dl, byte ptr [eax + eax]
01:0004│     0xffff5504 —▸ 0x8048a90 ◂— add    dword ptr [eax + eax], edx
02:0008│     0xffff5508 ◂— 0x0
... ↓        5 skipped
───────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────[ BACKTRACE ]────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
 ► f 0 0x8048725 authenticate+29
   f 1 0x80487d5 main+44
   f 2 0xf7de8e46 __libc_start_main+262
────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
pwndbg> i r
eax            0x804d010           134533136
ecx            0x1480              5248
edx            0x7d                125
ebx            0x0                 0
esp            0xffff5500          0xffff5500
ebp            0xffffd528          0xffffd528
esi            0xf7faf000          -134549504
edi            0xf7faf000          -134549504
eip            0x8048725           0x8048725 <authenticate+29>
eflags         0x282               [ SF IF ]
cs             0x23                35
ss             0x2b                43
ds             0x2b                43
es             0x2b                43
fs             0x0                 0
gs             0x63                99
pwndbg> x/6sw $ax
0xffffd010:     U""
0xffffd014:     U"\xf7f50900\xf7fafd20"
0xffffd020:     U"$\x1fa9bf49"
0xffffd02c:     U"\xffffffff\001"
0xffffd038:     U"\xffffd4a8"
0xffffd040:     U" \xfffffff8"
pwndbg> x/6sw $eax
0x804d010:      U"9447{you_are_an_international_mystery}"
0x804d0ac:      U""
0x804d0b0:      U""
0x804d0b4:      U""
0x804d0b8:      U""
0x804d0bc:      U""
```

