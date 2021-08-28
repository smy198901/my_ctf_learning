IDA分析，得到

```c
__int64 __fastcall main(int a1, char **a2, char **a3)
{
  __int64 v3; // rbx
  int v4; // eax
  bool v5; // bp
  bool v6; // al
  const char *v7; // rdi
  unsigned int v9; // [rsp+0h] [rbp-28h] BYREF
  int v10[9]; // [rsp+4h] [rbp-24h] BYREF

  v10[0] = 0;
  v9 = 0;
  puts("Input flag:");
  scanf("%s", &s1);
  if ( strlen(&s1) != 24 || strncmp(&s1, "nctf{", 5uLL) || *(&byte_6010BF + 24) != '}' )
  {
LABEL_22:
    puts("Wrong flag!");
    exit(-1);
  }
  v3 = 5LL;
  if ( strlen(&s1) - 1 > 5 )
  {
    while ( 1 )
    {
      v4 = *(&s1 + v3);//v4==flag
      v5 = 0;
      if ( v4 > 78 )
      {
        if ( (unsigned __int8)v4 == 'O' )
        {
          v6 = sub_400650(v10); //v10--
          goto LABEL_14;
        }
        if ( (unsigned __int8)v4 == 'o' )
        {
          v6 = sub_400660(v10); //v10++
          goto LABEL_14;
        }
      }
      else
      {
        if ( (unsigned __int8)v4 == '.' )
        {
          v6 = sub_400670(&v9); //v9--
          goto LABEL_14;
        }
        if ( (unsigned __int8)v4 == '0' )
        {
          v6 = sub_400680((int *)&v9); //v9++
LABEL_14:
          v5 = v6;
          goto LABEL_15;
        }
      }
LABEL_15:
      if ( !(unsigned __int8)sub_400690((__int64)asc_601060, v10[0], v9) )
        goto LABEL_22;
      if ( ++v3 >= strlen(&s1) - 1 )
      {
        if ( v5 )
          break;
LABEL_20:
        v7 = "Wrong flag!";
        goto LABEL_21;
      }
    }
  }
  if ( asc_601060[8 * v9 + v10[0]] != '#' )
    goto LABEL_20;
  v7 = "Congratulations!";
LABEL_21:
  puts(v7);
  return 0LL;
}
```

```c
asc_601060 = '  *******   *  **** * ****  * ***  *#  *** *** ***     *********'
```

根据题目提示，是一个迷宫。当`asc_601060[8 * v9 + v10[0]] == '#'`时走出迷宫。由`8 * v9 + v10`可知，这是一个8*8的矩阵迷宫。

```
  ******
*   *  *
*** * **
**  * **
*  *#  *
** *** *
**     *
********
```

v9表示横坐标，v10表示竖坐标。'O'表示向左，'o'表示向右，'.'表示向上，'0'表示向下。

因此flag为：nctf{o0oo00O000oooo..OO}





