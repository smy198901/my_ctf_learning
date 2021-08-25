https://adworld.xctf.org.cn/task/answer?type=misc&number=1&grade=1&id=4906&page=2

# 分析

下载附件，使用binwalk查看：

```
DECIMAL       HEXADECIMAL     DESCRIPTION
--------------------------------------------------------------------------------
0             0x0             Linux EXT filesystem, blocks count: 2048, image size: 2097152, rev 1.0, ext3 filesystem data, UUID=bc6c2b24-106a-4570-bc4f-ae09abbdabbd
```

是Linux文件，结合题目提示“恢复磁盘并且找到FLAG”，使用extundelete恢复数据。

```
extundelete disk-image
```

得到flag文件。

binwalk查看flag文件，发现是可执行文件。

```
DECIMAL       HEXADECIMAL     DESCRIPTION
--------------------------------------------------------------------------------
0             0x0             ELF, 64-bit LSB executable, AMD x86-64, version 1 (SYSV)
```

执行文件：

```
chmod 777 flag
./flag
```

得到flag：

```
de6838252f95d3b9e803b28df33b4baa
```

