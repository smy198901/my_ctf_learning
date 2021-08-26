题目提供了一个.pyc文件，使用uncompyle6反编译为python文件。

```
uncompyle6 -o f.py f417c0d03b0344eb9969ed0e1f772091.pyc 
```

python文件：

```
import base64

def encode(message):
    s = ''
    for i in message:
        x = ord(i) ^ 32
        x = x + 16
        s += chr(x)

    return base64.b64encode(s)


correct = 'XlNkVmtUI1MgXWBZXCFeKY+AaXNt'
flag = ''
print 'Input flag:'
flag = raw_input()
if encode(flag) == correct:
    print 'correct'
else:
    print 'wrong'
```

根据python代码直接写decode函数:

```
def decode(message):
    message = base64.b64decode(message)
    print(message)
    s = ''
    for i in message:
        print(i)
        x = i - 16
        x = x ^ 32
        s = s + chr(x)
    return s
```

运行得到flag：nctf{d3c0mpil1n9_PyC}

