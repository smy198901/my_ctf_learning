题目给了一个python文件，其中有一个加密算法`lfsr(R,mask)`

```python
from flag import flag
assert flag.startswith("flag{")
assert flag.endswith("}")
assert len(flag)==27

def lfsr(R,mask):
    output = (R << 1) & 0xffffff
    i=(R&mask)&0xffffff
    lastbit=0
    while i!=0:
        lastbit^=(i&1)
        i=i>>1
    output^=lastbit
    return (output,lastbit)



R=int(flag[5:-1],2) #flag的中间有21位，且都是0和1
mask=0x100002

f=open("key","ab")
for i in range(12):
    tmp=0
    for j in range(8):
        (R,out)=lfsr(R,mask)
        tmp=(tmp << 1)^out
    f.write(chr(tmp))
f.close()
```

flag的中间有21位，且都是0和1，一共有2**21中可能，可以使用爆破。

```python
def lfsr(R,mask):
    output = (R << 1) &0xffffff
    i=(R&mask)&0xffffff
    lastbit=0
    while i!=0:
        lastbit^=(i&1)
        i=i>>1
    output^=lastbit
    return (output,lastbit)
#key文件的16进制数。
key=[0xB2,0xE9,0x0E,0x13,0xA0,0x6A,0x1B,0xFC,0x40,0xE6,0x7D,0x53]
mask=0x100002
 
for k in range(2**21):
    R=k
    a=''
    judge=1
    for i in range(12):
        tmp = 0
        for j in range(8):
            (k, out) = lfsr(k, mask)
            tmp = (tmp << 1) ^ out
        if(key[i]!=tmp):
           judge=0
           break
    if(judge==1):
        print('flag{'+bin(R)[2:]+'}')  #flag{110111100101001101001}	
        break
```

