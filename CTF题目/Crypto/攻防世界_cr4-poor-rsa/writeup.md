解压文件，得到两个文件`flag.b64`和`key.pub`

先使用RsaCtfTool得到p和q

```shell
python3 RsaCtfTool.py --publickey /smy/key.pub --private

python3 RsaCtfTool.py --key /smy/private.key --dumpkey 

n: 833810193564967701912362955539789451139872863794534923259743419423089229206473091408403560311191545764221310666338878019
e: 65537
d: 521250646663056391768764366517618655312275374668692430321064634566533568373969990465313092928455546989832961905578375473
p: 863653476616376575308866344984576466644942572246900013156919
q: 965445304326998194798282228842484732438457170595999523426901
```

python 脚本:

```python
# n = p*q
import gmpy2
from Crypto.Util.number import long_to_bytes, bytes_to_long
import libnum
import base64

# 计算d
def cal_d(p, q, e):
    phi = (p-1)*(q-1)
    return gmpy2.invert(e, phi)

# 计算明文m
def cal_m(c, d, n):
    return pow(c, d, n)

# 计算密文c
def cal_c(m, e, n):
    return pow(m, e, n)


p = 863653476616376575308866344984576466644942572246900013156919
q = 965445304326998194798282228842484732438457170595999523426901
e = 65537
#'Ni45iH4UnXSttNuf0Oy80+G5J7tm8sBJuDNN7qfTIdEKJow4siF2cpSbP/qIWDjSi+w='是flag.b64文件的内容
c = bytes_to_long(base64.b64decode('Ni45iH4UnXSttNuf0Oy80+G5J7tm8sBJuDNN7qfTIdEKJow4siF2cpSbP/qIWDjSi+w='))

d = cal_d(p, q, e)
m = cal_m(c, d, p*q)
print(long_to_bytes(m)) # ALEXCTF{SMALL_PRIMES_ARE_BAD}

```

