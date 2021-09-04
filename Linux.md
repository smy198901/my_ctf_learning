# 命令

## tr

Linux tr 命令用于转换或删除文件中的字符。

tr 指令从标准输入设备读取数据，经过字符串转译后，将结果输出到标准输出设备。

```
//把文件内的字符'A-Za-z'转为'N-ZA-Mn-za-m'
cat 9e45191069704531accd66f1ee1d5b2b.pdf | tr 'A-Za-z' 'N-ZA-Mn-za-m' > 2.pdf
```

