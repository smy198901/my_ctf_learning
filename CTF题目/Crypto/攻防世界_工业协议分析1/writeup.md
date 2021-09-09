# 1. 题目信息

工业网络中存在异常，尝试通过分析PCAP流量包，分析出流量数据中的异常点，并拿到FLAG。flag形式为 flag{}，流量包在附件。

# 2. 分析

将流量包的长度排序(由大到小)，第一个流量包的长度为10120，比第二长的流量包超过太多，猜测有问题；一段乱码之后，data="data:image/png;base64,后面是一段很长的base64编码。

# 3. 解题

先将流量包中的base64编码复制到文本文件data.txt中，再进行解码，最后将解码的数据写入png文件，实现的Python脚本如下：

```Python
from base64 import b64decode

with open('data.txt','r') as f:
    da=f.read()

data=b64decode(da)

with open('flag.png','w') as g:
    g.write(data)
```

flag：flag{ICS-mms104}