题目提供了一个PDF文件，但是无法打开。

使用010editor打开。

![image-20210904215408717](images\image-20210904215408717.png)

与正常的PDF头不一致。

正常PDF头如下：

![image-20210904215524205](images\image-20210904215524205.png)

发现CQS和PDF，相差13，猜测文件中的字母被ROT13加密了。还原：

```shell
cat 9e45191069704531accd66f1ee1d5b2b.pdf | tr 'A-Za-z' 'N-ZA-Mn-za-m' > 2.pdf
```

打开新的pdf，发现flag被隐藏了。

![image-20210904215741676](images\image-20210904215741676.png)

转为图片

```shell
 pdfimages -png 2.pdf png
```

得到flag：BITSCTF{save_the_kid}

![image-20210904220412039](images\image-20210904220412039.png)