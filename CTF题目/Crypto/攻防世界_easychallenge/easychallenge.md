https://adworld.xctf.org.cn/task/answer?type=crypto&number=5&grade=0&id=5119&page=1

# 分析

题目是一个.pyc文件，转为python，得到python代码：

```
# Embedded file name: ans.py
import base64

def encode1(ans):
    s = ''
    for i in ans:
        x = ord(i) ^ 36
        x = x + 25
        s += chr(x)

    return s


def encode2(ans):
    s = ''
    for i in ans:
        x = ord(i) + 36
        x = x ^ 36
        s += chr(x)

    return s


def encode3(ans):
    return base64.b32encode(ans)


flag = ' '
print 'Please Input your flag:'
flag = raw_input()
final = 'UC7KOWVXWVNKNIC2XCXKHKK2W5NLBKNOUOSK3LNNVWW3E==='
if encode3(encode2(encode1(flag))) == final:
    print 'correct'
else:
    print 'wrong'
```

根据代码编写decode程序：

```python

import base64

final = 'UC7KOWVXWVNKNIC2XCXKHKK2W5NLBKNOUOSK3LNNVWW3E==='

def decode3(ans):
    return base64.b32decode(ans)

def decode2(ans):
    s = ''
    for i in ans:
        x = int(i) ^ 36
        x = chr(x - 36)
        s = s + x

    return s

def decode1(ans):
    s = ''
    for i in ans:
        x = ord(i) - 25
        x = chr(x ^ 36)
        s = s + x
    return s

print( decode1(decode2(decode3( final))))
```

得到flag:cyberpeace{interestinghhhhh}

