题目描述：sha1 得到了一个神秘的二进制文件。寻找文件中的flag，解锁宇宙的秘密。注意：将得到的flag变为flag{XXX}形式提交。

```c
__int64 __fastcall not_the_flag(int a1)
{
  if ( a1 == 42 )
    puts("Cipher from Bill \nSubmit without any tags\n#kdudpeh");
  else
    puts("YOUSUCK");
  return 0LL;
}
```

#kdudpeh可能就是flag了，提交了发现不对。再看下输出，发现提示了Submit without any tags ，再提交kdudpeh 上去，发现还是不对。。。
根据题目描述，尝试用sha1去加密得到80ee2a3fe31da904c596d993f7f1de4827c1450a 。

因此最后得到flag为：flag{80ee2a3fe31da904c596d993f7f1de4827c1450a} 