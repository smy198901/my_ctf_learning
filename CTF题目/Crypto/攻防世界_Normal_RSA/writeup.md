生成private key

```shell
python3 RsaCtfTool.py --publickey pubkey.pem --private


Private key :
-----BEGIN RSA PRIVATE KEY-----
MIGqAgEAAiEAwmNq5cPY5D/7l6sJAo8arGwL9s09cOvKKBv/6X++MN0CAwEAAQIg
GAZ5m9RM5kkSK3i0MGDHhvi3f7FZPghC2gY7oNhyi/ECEQDO+7LPfhipjr7cNuPn
w7ArAhEA8Gwo6RyJIrnCNuI1YMCXFwIRAJulRkclqWIHx5pNZIAp9VUCEGjeJLIZ
ek+lSut5m+LJ3p0CEDRBEd7C622/wt1+58xOIfE=
-----END RSA PRIVATE KEY-----
```

解析私钥：

```shell
python3 RsaCtfTool.py --key test.key --dumpkey
 
n: 87924348264132406875276140514499937145050893665602592992418171647042491658461
e: 65537
d: 10866948760844599168252082612378495977388271279679231539839049698621994994673
p: 275127860351348928173285174381581152299
q: 319576316814478949870590164193048041239

```

读取flag.enc文件

```python
from Crypto.Util.number import bytes_to_long
c = open("flag.enc", "rb").read()
lc = bytes_to_long(c)

#49412914049026066227292604633959399022586841904231599586841156187258952420473
```

求解明文：

```python
# n = p*q
import gmpy2
from Crypto.Util.number import long_to_bytes
import libnum

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


p = 275127860351348928173285174381581152299
q = 319576316814478949870590164193048041239
e = 65537
c = 49412914049026066227292604633959399022586841904231599586841156187258952420473

d = cal_d(p, q, e)
m = cal_m(c, d, p*q)
print(long_to_bytes(m)) # b'\x02\xc0\xfe\x04\xe3&\x0e[\x87\x00PCTF{256b_i5_m3dium}\n'
```

使用openssl求解明文：

```shell
openssl rsautl -decrypt -in flag.enc -inkey private.key

PCTF{256b_i5_m3dium}
```





得到flag：PCTF{256b_i5_m3dium}