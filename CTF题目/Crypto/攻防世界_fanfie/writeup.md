# 1. 题目信息

附件是一个文本文件，里面是一段长34的字符串。

# 2. 分析

文本中的字符串看上去像base32编码，加填充后解码得乱码；很多时候比赛的名称是解密的谜面，对字符串BITSCTF进行base32编码得字符串IJEVIU2DKRDA====，与文本中的字符串MZYVMIWLGBL7CIJOGJQVOA3IN5BLYC3NHI进行对比，发现字符I两次对应M，猜测是移位密码或仿射密码，加密运算的有限集为{A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,2,3,4,5,6,7}，不理解的可以参见[对base编码的介绍](https://www.cnblogs.com/coming1890/p/13503574.html),尝试之后得加密方式为仿射密码，求得解密式为x=5y+12 mod 32x=5y+12 mod 32。

# 3. 解题

实现的Python脚本如下：

```Python
from base64 import b32decode

def solve():
    s='MZYVMIWLGBL7CIJOGJQVOA3IN5BLYC3NHI'
    dic='ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'
    msg=''.join([dic[(5*dic.find(x)+12)%32] for x in s])
    return b32decode(msg+'='*(8-len(msg)%8))

if __name__=='__main__':
    #python solve.py
    print(solve())
```

运行程序得如下结果：

```Bash
$ python solve.py
BITSCTF{S2VyY2tob2Zm}
```