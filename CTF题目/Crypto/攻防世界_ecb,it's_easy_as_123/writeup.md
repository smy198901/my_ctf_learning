# 1. 题目信息

附件提供了背景介绍(Somebody leaked a still from the upcoming Happy Feet Three movie, which will be released in 4K, but Warner Bros. was smart enough to encrypt it. But those idiots used a black and white bmp format, and that wasn't their biggest mistake. Show 'em who's boss and get the flag.)，与一个bmp文件。

# 2. 分析

根据背景介绍，原图片应该是bmp格式，经ecb工作模式加密得ecb.bmp；由于ecb工作模式不会掩盖明文的统计规律，因此只要能够修复ecb.bmp就可以见到明文，要修复ecb.bmp，则要求文件的前128字节为bmp文件格式特有的字节。

# 3. 解题

将ecb.bmp的前128字节替换为bmp文件格式特有的字节，则可以见到明文信息，实现的Python脚本如下：

```python
from Crypto.Util.number import long_to_bytes

with open('Desktop/ecb.bmp','rb') as f:
    data=f.read()
# 3840×2160 16色的BMP文件的前128位
pre=0x424d76483f00000000007600000028000000000f000070080000010004000000000000483f00000000000000000000000000000000000000000000008000008000000080800080000000800080008080000080808000c0c0c0000000ff0000ff000000ffff00ff000000ff00ff00ffff0000ffffff00ffffffffffffffffffff
out=long_to_bytes(pre)+data[128:]

with open('out.bmp','wb') as g:
    g.write(out)
```

flag：flag{no_penguin_hereno_penguin_here}