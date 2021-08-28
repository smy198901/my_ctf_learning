IDA静态分析，得到：

```c
int __cdecl __noreturn main(int argc, const char **argv, const char **envp)
{
  int v3; // ecx
  CHAR *lpMem; // [esp+8h] [ebp-Ch]
  HANDLE hHeap; // [esp+10h] [ebp-4h]

  hHeap = HeapCreate(0x40000u, 0, 0);
  lpMem = (CHAR *)HeapAlloc(hHeap, 8u, SourceSize + 1);
  memcpy_s(lpMem, SourceSize, &unk_409B10, SourceSize);
   //!sub_40102A() && !IsDebuggerPresent()  条件永真，不执行sub_401000
  if ( !sub_40102A() && !IsDebuggerPresent() )
  {
    MessageBoxA(0, lpMem + 1, "Flag", 2u);
    HeapFree(hHeap, 0, lpMem);
    HeapDestroy(hHeap);
    ExitProcess(0);
  }
  __debugbreak();
  sub_401000(v3 + 4, lpMem);
  ExitProcess(0xFF)
```

只要先执行sub_401000()，就可以得到正确的flag。

ollydbg分析：

![image-20210827215702577](images\image-20210827215702577.png)

只要修改6010A3上的值，使其跳转到6010B9就可以得到flag：flag{reversing_is_not_that_hard!}

![image-20210827215956440](images\image-20210827215956440.png)



