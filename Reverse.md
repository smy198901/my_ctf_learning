# 常用工具

## exeinfope

检查文件的信息，操作平台，有没有加壳等信息。

## IDA Pro

进行静态分析。

1. 导出数据：选择数据段的地址，编辑 -- 导出数据。
2.  

## GDA

分析apk文件。

一般而言，用户的包都放在com包下面。

## UPX

UPX是一个通用可执行文件压缩器，可用于脱壳。

```shell
upx -d file -o new_file
```

## dnSpy（.net）

用于分析.net编译的文件。

# PE文件结构



# IDA动态调试

F8：下一步     F7：步入函数

见攻防世界 -- EASYHOOK
