# Exeinfo

32位c++程序

# IDA

IDA静态分析：

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  int result; // eax
  HANDLE v4; // eax
  DWORD NumberOfBytesWritten; // [esp+4h] [ebp-24h] BYREF
  char Buffer[32]; // [esp+8h] [ebp-20h] BYREF

  sub_401370((int)aPleaseInputFla);
  scanf("%31s", Buffer);
  if ( strlen(Buffer) == 19 ) //输入字符串长度等于19
  {
    sub_401220(); //不知道什么函数
    v4 = CreateFileA(FileName, 0x40000000u, 0, 0, 2u, 0x80u, 0);
    WriteFile(v4, Buffer, 0x13u, &NumberOfBytesWritten, 0);
    sub_401240(Buffer, &NumberOfBytesWritten); // 无法理解其中的v4[a1 - v4 + result] == v4[result] ，不像是加密函数。
    if ( NumberOfBytesWritten == 1 )
      sub_401370((int)aRightFlagIsYou); //输出函数，输出正确
    else
      sub_401370((int)aWrong); //输出函数，输出错误
    system(Command);
    result = 0;
  }
  else
  {
    sub_401370((int)aWrong);
    system(Command);
    result = 0;
  }
  return result;
}
```

动态调试：

设置断点：

![image-20210828224202535](images\image-20210828224202535.png)

一步步F8，关注`NumberOfBytesWritten`何时发生变化，发现执行到`00401328`后其值发生了改变。

![image-20210828224553049](images\image-20210828224553049.png)

重新debug，在call WriteFile处，F7步入函数调试，发现函数sub_401000是加密函数。

![image-20210828225053536](images\image-20210828225053536.png)

```c
int __cdecl sub_401000(int a1, int a2)
{
  char i; // al
  char v3; // bl
  char v4; // cl
  int v5; // eax

  for ( i = 0; i < a2; ++i )
  {
    if ( i == 18 )
    {
      *(_BYTE *)(a1 + 18) ^= '\x13';
    }
    else
    {
      if ( i % 2 )
        v3 = *(_BYTE *)(i + a1) - i;
      else
        v3 = *(_BYTE *)(i + a1 + 2);
      *(_BYTE *)(i + a1) = i ^ v3;
    }
  }
  v4 = 0;
  if ( a2 <= 0 )
    return 1;
  v5 = 0;
     // word_40A030 = [0x61, 0x6A, 0x79, 0x67, 0x6B, 0x46, 0x6D, 0x2E, 0x7F, 0x5F, 0x7E, 0x2D, 0x53, 0x56, 0x7B, 0x38, 0x6D, 0x4C, 0x6E]
  while ( word_40A030[v5] == *(_BYTE *)(v5 + a1) )
  {
    v5 = ++v4;
    if ( v4 >= a2 )
      return 1;
  }
  return 0;
}
```

Python编写解密脚本：

```python
a = [0x61, 0x6A, 0x79, 0x67, 0x6B, 0x46, 0x6D, 0x2E, 0x7F, 0x5F, 
  0x7E, 0x2D, 0x53, 0x56, 0x7B, 0x38, 0x6D, 0x4C, 0x6E]

flag = [0 for i in range(19)]

for i in range(19):
    if i == 18:
        flag[i] = a[i] ^ 19
    else:
        v3 = a[i] ^ i
        if i % 2:
            flag[i] = v3 + i
        else:
            flag[i + 2] = v3

for i in flag:
    print(chr(i), end='') #  lag{Ho0k_w1th_Fun}
```

前面少了一个f，补全得到flag：flag{Ho0k_w1th_Fun}

