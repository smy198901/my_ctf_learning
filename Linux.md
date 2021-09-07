# 命令

## tr

Linux tr 命令用于转换或删除文件中的字符。

tr 指令从标准输入设备读取数据，经过字符串转译后，将结果输出到标准输出设备。

```
//把文件内的字符'A-Za-z'转为'N-ZA-Mn-za-m'
cat 9e45191069704531accd66f1ee1d5b2b.pdf | tr 'A-Za-z' 'N-ZA-Mn-za-m' > 2.pdf
```

## xxd

xxd 命令用于用二进制或十六进制显示文件的内容

```shell
 xxd ecb.bmp | head -n 2   
```

## hexdump

hexdump是Linux下的一个二进制文件查看工具，它可以将二进制文件转换为ASCII、八进制、十进制、十六进制格式进行查看。

```shell
hexdump: [-bcCdovx] [-e fmt] [-f fmt_file] [-n length] [-s skip] [file ...]
```

| 参数 | 描叙                                                         |
| ---- | ------------------------------------------------------------ |
| -b   | 每个字节显示为8进制。一行共16个字节，一行开始以十六进制显示偏移值 |
| -c   | 每个字节显示为ASCII字符                                      |
| -C   | 每个字节显示为16进制和相应的ASCII字符                        |
| -d   | 两个字节显示为10进制                                         |
| -e   | 格式化输出                                                   |
| -f   | Specify a file that contains one or more newline separated format strings. Empty lines and lines whose first non-blank character is a hash mark (#) are ignored. |
| -n   | 只格式前n个长度的字符                                        |
| -o   | 两个字节显示为8进制                                          |
| -s   | 从偏移量开始输出                                             |
| -v   | The -v option causes hexdump to display all input data. Without the -v option, any number of groups of output lines, which would be identical to the immediately preceding group of output lines |
| -x   | 双字节十六进制显示                                           |

