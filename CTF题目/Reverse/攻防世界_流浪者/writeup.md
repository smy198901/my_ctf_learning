# Exeinfo

查看程序，发现程序并未带壳。

# IDA

打开文件，静态分析：

常规查看字符串，发现字符串：

![image-20210828124147391](images\image-20210828124147391.png)

交叉引用找到函数：

```c
int __thiscall sub_401890(CWnd *this)
{
  CWnd *v1; // eax
  int v2; // eax
  struct CString *v4; // [esp-4h] [ebp-C4h]
  int v5[26]; // [esp+4Ch] [ebp-74h] BYREF
  int i; // [esp+B4h] [ebp-Ch]
  char *Str; // [esp+B8h] [ebp-8h]
  CWnd *v8; // [esp+BCh] [ebp-4h]

  v8 = this;
  v4 = (CWnd *)((char *)this + 100);
  v1 = CWnd::GetDlgItem(this, 1002);
  CWnd::GetWindowTextA(v1, v4);
  v2 = sub_401A30((char *)v8 + 100);
  Str = CString::GetBuffer((CWnd *)((char *)v8 + 100), v2);
  if ( !strlen(Str) )
    return CWnd::MessageBoxA(v8, "请输入pass!", 0, 0);
  for ( i = 0; Str[i]; ++i )
  {
    if ( Str[i] > 57 || Str[i] < 48 )
    {
      if ( Str[i] > 122 || Str[i] < 97 )
      {
        if ( Str[i] > 90 || Str[i] < 65 )
          sub_4017B0(); //失败
        else
          v5[i] = Str[i] - 29;
      }
      else
      {
        v5[i] = Str[i] - 87;
      }
    }
    else
    {
      v5[i] = Str[i] - 48;
    }
  }
  return sub_4017F0((int)v5);
}

int __cdecl sub_4017F0(int a1)
{
  int result; // eax
  char Str1[28]; // [esp+D8h] [ebp-24h] BYREF
  int v3; // [esp+F4h] [ebp-8h]
  int v4; // [esp+F8h] [ebp-4h]

  v4 = 0;
  v3 = 0;
  while ( *(int *)(a1 + v4) < 62 && *(int *)(a1 + v4) >= 0 )
  {
    Str1[v4] = s[*(_DWORD *)(a1 + v4)]; // s='abcdefghiABCDEFGHIJKLMNjklmn0123456789opqrstuvwxyzOPQRSTUVWXYZ'
    ++v4;
  }
  Str1[v4] = 0;
  if ( !strcmp(Str1, "KanXueCTF2019JustForhappy") )
    result = sub_401770(); //成功
  else
    result = sub_4017B0(); //失败
  return result;
}
```

分析函数得知，需要输入pass，经过一系列转化得到`KanXueCTF2019JustForhappy`即成功。

Python脚本：

```python
s1 = 'abcdefghiABCDEFGHIJKLMNjklmn0123456789opqrstuvwxyzOPQRSTUVWXYZ'
s2 = 'KanXueCTF2019JustForhappy'

a1 = []
s2_len = len(s2)
for i in range(s2_len):
    a1.append(s1.index(s2[i]))

flag = []
for i in a1:
    if 0 <= i and  i <= 9:
        flag.append( i + 48)
    elif 10 <= i and i <= 35:
        flag.append( i + 87)
    else:
        flag.append( i + 29)

for i in flag:
    print(chr(i), end='') //j0rXI4bTeustBiIGHeCF70DDM
```

得到flag：flag{j0rXI4bTeustBiIGHeCF70DDM}

