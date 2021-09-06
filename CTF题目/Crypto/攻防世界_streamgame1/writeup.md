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
 
key=[85,56 , 247, 66  ,193, 13 , 178, 199 ,237 ,224 ,36 , 58 ]
mask=0b1010011000100011100
 
for k in range(2**19):
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
        print('flag{'+bin(R)[2:]+'}') # flag{1110101100001101011}
        break
```

