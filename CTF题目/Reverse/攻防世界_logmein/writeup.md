PE查看文件，没有壳，64位elf文件。

使用IDA反编译文件。

```c
void __fastcall __noreturn main(int a1, char **a2, char **a3)
{
  size_t v3; // rsi
  int i; // [rsp+3Ch] [rbp-54h]
  char s[36]; // [rsp+40h] [rbp-50h] BYREF
  int v6; // [rsp+64h] [rbp-2Ch]
  __int64 v7; // [rsp+68h] [rbp-28h]
  char v8[28]; // [rsp+70h] [rbp-20h] BYREF
  int v9; // [rsp+8Ch] [rbp-4h]

  v9 = 0;
  strcpy(v8, ":\"AL_RT^L*.?+6/46");
  v7 = 'ebmarah';                               // 转为字符串:ebmarah
                                                // 
  v6 = 7;
  printf("Welcome to the RC3 secure password guesser.\n");
  printf("To continue, you must enter the correct password.\n");
  printf("Enter your guess: ");
  __isoc99_scanf("%32s", s);
  v3 = strlen(s);
  if ( v3 < strlen(v8) )
    sub_4007C0();
  for ( i = 0; i < strlen(s); ++i )
  {
    if ( i >= strlen(v8) )
      sub_4007C0();
    if ( s[i] != (char)(*((_BYTE *)&v7 + i % v6) ^ v8[i]) )// &v7 + i % 7 表示V7[i % 7]
      sub_4007C0();
  }
  sub_4007F0();
}
```

s[i] == (char)(*((_BYTE *)&v7 + i % v6) ^ v8[i])

python脚本：

```python
v7 = list('ebmarah') 
v7.reverse() # 小端序存储，需要反转。
print(v7)
v8 = ":\"AL_RT^L*.?+6/46"
l = len(v8)
s=['' for i in range(l)]

for i in range(l):
    print(chr(ord(v7[i % 7]) ^ ord(v8[i])), end='')
```

flag: RC3-2016-XORISGUD

