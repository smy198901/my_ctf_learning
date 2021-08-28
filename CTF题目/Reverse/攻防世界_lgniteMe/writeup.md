# Exeinfo

32位程序，不带壳

# IDA

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  void *v3; // eax
  void *v4; // eax
  int result; // eax
  void *v6; // eax
  void *v7; // eax
  size_t i; // [esp+4Ch] [ebp-8Ch]
  char v9[8]; // [esp+50h] [ebp-88h] BYREF
  char Str[128]; // [esp+58h] [ebp-80h] BYREF

  v3 = (void *)sub_402B30((int)&unk_446360, "Give me your flag:");
  sub_4013F0(v3, (int (__cdecl *)(void *))sub_403670);
  sub_401440(Str, 127);
  if ( strlen(Str) < 0x1E && strlen(Str) > 4 )
  {
    strcpy(v9, "EIS{");
    for ( i = 0; i < strlen(v9); ++i )
    {
      if ( Str[i] != v9[i] )
        goto LABEL_7;
    }
    if ( Str[28] != '}' )
    {
LABEL_7:
      v6 = (void *)sub_402B30((int)&unk_446360, "Sorry, keep trying! ");
      sub_4013F0(v6, (int (__cdecl *)(void *))sub_403670);
      return 0;
    }
    if ( (unsigned __int8)sub_4011C0(Str) )
      v7 = (void *)sub_402B30((int)&unk_446360, "Congratulations! ");
    else
      v7 = (void *)sub_402B30((int)&unk_446360, "Sorry, keep trying! ");
    sub_4013F0(v7, (int (__cdecl *)(void *))sub_403670);
    result = 0;
  }
  else
  {
    v4 = (void *)sub_402B30((int)&unk_446360, "Sorry, keep trying!");
    sub_4013F0(v4, (int (__cdecl *)(void *))sub_403670);
    result = 0;
  }
  return result;
}
```

看到`Congratulations!`，查看sub_4011C0：

```c
bool __cdecl sub_4011C0(char *Str)
{
  size_t v2; // eax
  int v3; // [esp+50h] [ebp-B0h]
  char Str2[32]; // [esp+54h] [ebp-ACh] BYREF
  int v5; // [esp+74h] [ebp-8Ch]
  int v6; // [esp+78h] [ebp-88h]
  size_t i; // [esp+7Ch] [ebp-84h]
  char v8[128]; // [esp+80h] [ebp-80h] BYREF

  if ( strlen(Str) <= 4 )
    return 0;
  i = 4;
  v6 = 0;
  while ( i < strlen(Str) - 1 )
    v8[v6++] = Str[i++];
  v8[v6] = 0;
  v5 = 0;
  v3 = 0;
  memset(Str2, 0, sizeof(Str2));
  for ( i = 0; ; ++i )
  {
    v2 = strlen(v8);
    if ( i >= v2 )
      break;
    if ( v8[i] >= 97 && v8[i] <= 122 )
    {
      v8[i] -= 32;
      v3 = 1;
    }
    if ( !v3 && v8[i] >= 65 && v8[i] <= 90 )
      v8[i] += 32;
    //byte_4420B0 = [0x0D, 0x13, 0x17, 0x11, 0x02, 0x01, 0x20, 0x1D, 0x0C, 0x02, 0x19, 0x2F, 0x17, 0x2B, 0x24, 0x1F, 0x1E, 0x16, 0x09, 0x0F, 0x15, 0x27, 0x13, 0x26, 0x0A, 0x2F, 0x1E,0x1A, 0x2D, 0x0C, 0x22, 0x04]
    Str2[i] = byte_4420B0[i] ^ sub_4013C0(v8[i]); 
    v3 = 0;
  }
  return strcmp("GONDPHyGjPEKruv{{pj]X@rF", Str2) == 0; //两者相同，返回true
}


int __cdecl sub_4013C0(int a1)
{
  return (a1 ^ 0x55) + 72;
}
```

可以得知，flag的前四个字符和最后一个字符EIS{****}，中间段传入sub_4011C0函数，经过一系列转化，与"GONDPHyGjPEKruv{{pj]X@rF"比较。

python脚本求解传入前的值：

```python
str2 = 'GONDPHyGjPEKruv{{pj]X@rF'
byte_4420B0 = [0x0D, 0x13, 0x17, 0x11, 0x02, 0x01, 0x20, 0x1D, 0x0C, 0x02, 
  0x19, 0x2F, 0x17, 0x2B, 0x24, 0x1F, 0x1E, 0x16, 0x09, 0x0F, 
  0x15, 0x27, 0x13, 0x26, 0x0A, 0x2F, 0x1E, 0x1A, 0x2D, 0x0C, 
  0x22, 0x04]
v8 = []
for i in range(len(str2)):
    temp = ((ord(str2[i]) ^ byte_4420B0[i]) - 72) ^ 0x55
    v8.append(temp)

print(v8)

for i in v8:
    if i >= 97 and i <= 122:
        i = i - 32
    if i >= 65 and i <= 90:
         i = i + 32
    
    print(chr(i), end='') # wadx_tdgk_aihc_ihkn_pjlm
```

得到flag：EIS{wadx_tdgk_aihc_ihkn_pjlm}