# Exeinfo

32位c++程序

# IDA

IDA分析程序，得到main函数：

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  unsigned int v3; // kr00_4
  int v4; // edx
  char *v5; // esi
  char v6; // al
  unsigned int i; // edx
  int v8; // eax
  char Arglist[16]; // [esp+2h] [ebp-24h] BYREF
  __int64 v11; // [esp+12h] [ebp-14h] BYREF
  int v12; // [esp+1Ah] [ebp-Ch]
  __int16 v13; // [esp+1Eh] [ebp-8h]

  sub_401020(Format);
  v12 = 0;
  v13 = 0;
  *(_OWORD *)Arglist = 0i64;
  v11 = 0i64;
  sub_401050("%s", (char)Arglist);
  v3 = strlen(Arglist);
  if ( v3 >= 0x10 && v3 == 24 )
  {
    v4 = 0;
    v5 = (char *)&v11 + 7;
    do
    {
      v6 = *v5--; 
      byte_40336C[v4++] = v6;
    }
    while ( v4 < 24 );  //这段while循环，表示从后往前赋值byte_40336C
    for ( i = 0; i < 0x18; ++i )
      byte_40336C[i] = (byte_40336C[i] + 1) ^ 6;
     //比较byte_40336C和aXircjR2twsv3pt，aXircjR2twsv3pt =  [ 0x78, 0x49, 0x72, 0x43, 0x6A, 0x7E, 0x3C, 0x72, 0x7C, 0x32, 0x74, 0x57, 0x73, 0x76, 0x33, 0x50, 0x74, 0x49, 0x7F, 0x7A, 0x6E, 0x64, 0x6B, 0x61]
    v8 = strcmp(byte_40336C和, aXircjR2twsv3pt); 
    if ( v8 )
      v8 = v8 < 0 ? -1 : 1;
    if ( !v8 )
    {
      sub_401020("right\n");                    // 输出函数
      system("pause");
    }
  }
  return 0;
}
```

Python脚本解密：

```python
s = [ 0x78, 0x49, 0x72, 0x43, 0x6A, 0x7E, 0x3C, 0x72, 0x7C, 0x32, 0x74, 0x57, 0x73, 0x76, 0x33, 0x50, 0x74, 0x49, 0x7F, 0x7A, 0x6E, 0x64, 0x6B, 0x61]
flag = ['' for i in range(24)]
for i in range(24):
    flag[i] = (s[i] ^ 6) - 1

flag.reverse()

for i in flag:
    print(chr(i), end='') # flag{xNqU4otPq3ys9wkDsN}
```

