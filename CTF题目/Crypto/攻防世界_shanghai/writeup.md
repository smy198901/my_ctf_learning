opk gvtyiz kd opk
可见前一个opk和后面的一个opk相隔11位，推测密码长度为11位

又因为看到
opk 16xu gvrbwht. xyi 19bj szvzyec
推测出 the 16th century the 19th century
在平时推测时有一些高频单词
a,is,in,of,and,the这些高频短词推算出部分密钥

最后推测密钥：
密文： opk 16xu gvrbwht.

密钥： vig en ereicqv

明文：the 16th century

密文： xyi 19bj szvzyec

密钥： ere ic qvigene

明文：the 19th century

最后得出 vig en ereicqv
ere ic qvigene
由于维吉尼亚的密钥是凯撒的循环 最后得出

密钥为vigenereicq 或者 icqvigenere



flag对应的密文为： jtcw, '{' vvj 'zvkvrmtudabiecveaaxpp' grq '}'

尝试循环使用密钥解密（eicqvigener）.

```python
"""
实现维吉尼亚的加密解密
"""

def VigenereEncode(message,key):
    pLen=len(message)
    kLen=len(key)
    message=message.upper()
    key=key.upper()
    raw = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"  # 明文空间
    out=""
    for i in range(0,pLen):
        j=i%kLen
        if message[i] not in raw:
            out+=message[i]
            continue
        encodechr=chr((ord(message[i])-ord('A')+ord(key[j])-ord('A'))%26+ord('A'))
        out+=encodechr
    return out

def VigenereDecode(message,key):
    """
    解密
    :param message: 密文
    :param key: 密钥
    :return: 明文
    """
    CLen=len(message)
    kLen=len(key)
    key=key.upper()
    message = message.upper()
    raw = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"  # 密文空间
    plaintext=""
    idx = 0
    for i in range(0,CLen):
        j=idx%kLen
        if message[i] not in raw:
            plaintext+=message[i]
        else:
            idx = idx + 1
            decodechr=chr((ord(message[i])-ord('A')-ord(key[j])-ord('A'))%26+ord('A'))
            plaintext+=decodechr
    return plaintext

if __name__ == "__main__":
    P="jtcw, '{' vvj 'zvkvrmtudabiecveaaxpp' grq '}'"
    key="vigenereicq"
    for i in range(11):
        newKey = key[i:] + key[0:i]
        print("key:" + newKey)
        out=VigenereDecode(P,newKey)
        print(out.lower())  
```



key:eicqvigener
flag, '{' and 'vigenereisveryeasyhuh' and '}'

