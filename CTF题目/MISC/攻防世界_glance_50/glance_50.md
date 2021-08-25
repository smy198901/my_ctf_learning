https://adworld.xctf.org.cn/task/answer?type=misc&number=1&grade=1&id=5047&page=1

# 分析

附件是一个gif文件。使用Stegsolve查看，一共有细长的201帧。需要分离和合并这201帧。

使用convert：

```powershell
//gif图片分离出来
convert +adjoin glance.gif piece-%03d.gif

//把分离出来的图片从左到右合成一张图片
convert +append piece*.gif final.png
```

![image-20210731160622918](images\image-20210731160622918.png)

得到flag:

```
TWCTF{Bliss by Charles O'Rear}
```

也可以使用Python来分离合并图片：

```python
import os 
from PIL import Image

im = Image.new('RGB', (2*201,600))#new(mode,size) size is long and width
PATH = 'E:/ctf/glance.gif'

FILE_NAME = [i for i in os.listdir(PATH)]

width = 0
for i in FILE_NAME:
    im.paste(Image.open(PATH+i),(width,0,width+2,600))#box is 左，上，右,下
    width += 2
im.show()
```

