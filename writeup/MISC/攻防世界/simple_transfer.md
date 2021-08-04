https://adworld.xctf.org.cn/task/answer?type=misc&number=1&grade=1&id=4741&page=3

# 分析

1. binwalk查看文件

   ```
   DECIMAL       HEXADECIMAL     DESCRIPTION
   --------------------------------------------------------------------------------
   0             0x0             Libpcap capture file, little-endian, version 2.4, Ethernet, snaplen: 262144
   339380        0x52DB4         PDF document, version: "1.5"
   339454        0x52DFE         Zlib compressed data, default compression
   340171        0x530CB         Zlib compressed data, default compression
   6380104       0x615A48        Zlib compressed data, default compression
   6385002       0x616D6A        Zlib compressed data, default compression
   ```

   发现文件中存在pdf文件。

2. pdftotext把文件输出成text文件，得到flag：HITB{b3d0e380e9c39352c667307d010775ca}

   ```
   └─# cat f9809647382a42e5bfb64d7d447b4099.pcap.txt
   HITB{b3d0e380e9c39352c667307d010775ca}
   ```

   