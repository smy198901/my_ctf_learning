# Exeinfo

64位Elf，不带壳。

## IDA

得到函数：

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  char s[240]; // [rsp+0h] [rbp-1E0h] BYREF
  char v5[240]; // [rsp+F0h] [rbp-F0h] BYREF

  memset(s, 0, 0x1EuLL);
  printf("Please Input Key: "); 
  __isoc99_scanf("%s", v5); //输入flag
  encode(v5, (__int64)s); // encode flag得到s
  if ( strlen(v5) == key ) // key = 18
  {
    if ( !strcmp(s, enflag) ) // enflag = 'izwhroz""w"v.K".Ni'，s和enflag比较，如果相等，则正确。
      puts("You are Right");
    else
      puts("flag{This_1s_f4cker_flag}");
  }
  return 0;
}


int __fastcall encode(const char *a1, __int64 a2)
{
  char v3[104]; // [rsp+10h] [rbp-70h]
  int v4; // [rsp+78h] [rbp-8h]
  int i; // [rsp+7Ch] [rbp-4h]

  i = 0;
  v4 = 0;
  if ( strlen(a1) != key )
    return puts("Your Length is Wrong");
  for ( i = 0; i < key; i += 3 )
  {
    v3[i + 64] = key ^ (a1[i] + 6);
    v3[i + 33] = (a1[i + 1] - 6) ^ key;
    v3[i + 2] = a1[i + 2] ^ 6 ^ key;
    *(_BYTE *)(a2 + i) = v3[i + 64];
    *(_BYTE *)(a2 + i + 1LL) = v3[i + 33];
    *(_BYTE *)(a2 + i + 2LL) = v3[i + 2];
  }
  return a2;
}
```

分析函数得知，只要输入的字符串经过encode函数加密后，得到的字符串与enflag相等，则`You are Right`。

Python脚本求解输入字符串：

```python
enflag = 'izwhroz""w"v.K".Ni'
key = 18
flag = ['' for i in range(key)]
for i in range(0, key, 3):
    a = enflag[i + 2]
    b = enflag[i + 1]
    c = enflag[i]
    flag[i] = (ord(c) ^ key) - 6
    flag[i + 1] = (ord(b) ^ key) + 6
    flag[i + 2] = (ord(a) ^ key) ^ 6

for i in flag:
    print(chr(i), end='') // unctf{b66_6b6_66b}
```

得到flag：unctf{b66_6b6_66b}