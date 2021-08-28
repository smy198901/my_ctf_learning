IDA静态分析，得到：

```c
int __cdecl main(int argc, const char **argv, const char **envp)
{
  char v3; // al
  int i; // [rsp+0h] [rbp-40h]
  int j; // [rsp+4h] [rbp-3Ch]
  FILE *stream; // [rsp+8h] [rbp-38h]
  char filename[24]; // [rsp+10h] [rbp-30h] BYREF
  unsigned __int64 v9; // [rsp+28h] [rbp-18h]

  v9 = __readfsqword(0x28u);
  for ( i = 0; i < strlen(s); ++i )
  {
    if ( (i & 1) != 0 )
      v3 = 1;
    else
      v3 = -1;
    *(&t + i + 10) = s[i] + v3;
  }
  strcpy(filename, "/tmp/flag.txt");
  stream = fopen(filename, "w");
  fprintf(stream, "%s\n", u);
  //遍历t,写入flag.txt文件
  for ( j = 0; j < strlen(&t); ++j )
  {
    fseek(stream, p[j], 0);
    fputc(*(&t + p[j]), stream);
    fseek(stream, 0LL, 0);
    fprintf(stream, "%s\n", u);
  }
  fclose(stream);
  remove(filename);
  return 0;
}
```

分析上述函数，可以得知，变量t保存的是flag。

python脚本：

```python
s = 'c61b68366edeb7bdce3c6820314b7498'
t = list('SharifCTF{????????????????????????????????}')
for i in range(len(s)):
    v3 = -1
    if (i & 1) != 0:
        v3 = 1
    t[i + 10] = chr(ord(str(s[i])) + v3)

print(''.join(t))  
```

得到flag：SharifCTF{b70c59275fcfa8aebf2d5911223c6589}