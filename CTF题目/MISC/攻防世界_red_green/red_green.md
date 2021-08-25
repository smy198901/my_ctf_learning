https://adworld.xctf.org.cn/task/answer?type=misc&number=1&grade=1&id=5426&page=2

# 分析

打开图片：

![在这里插入图片描述](images\watermark,type_ZmFuZ3poZW5naGVpdGk,shadow_10,text_aHR0cHM6Ly9ibG9nLmNzZG4ubmV0L3dlaXhpbl80NTU1NjQ0MQ==,size_16,color_FFFFFF,t_70)

发现是IHDR，尝试修改图片高度，未发现任何信息。

百度后：Stegsolve--Data Extract   Red通道设置为0，预览后可以看到是一张图片。

![image-20210801110005378](images\image-20210801110005378.png)

导出信息为图片，就可得到flag。

![image-20210801105816042](images\image-20210801105816042.png)

```
flag{134699ac9d6ac98b}
```

