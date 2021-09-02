​    

```
1:  < ZWAXJGDLUBVIQHKYPNTCRMOSFE <
2:  < KPBELNACZDTRXMJQOYHGVSFUWI <
3:  < BDMAIZVRNSJUWFHTEQGYXPLOCK <
4:  < RPLNDVHGFCUKTEBSXQYIZMJWAO <
5:  < IHFRLABEUOTSGJVDKCPMNZQWXY <
6:  < AMKGHIWPNYCJBFZDRUSLOQXVET <
7:  < GWTHSPYBXIZULVKMRAFDCEONJQ <
8:  < NOZUTWDCVRJLXKISEFAPMYGHBQ <
9:  < XPLTDSRFHENYVUBMCQWAOIKZGJ <
10: < UDNAJFBOWTGVRSCZQKELMXYIHP <
11： < MNBVCXZQWERTPOIUYALSKDJFHG <
12： < LVNCMXZPQOWEIURYTASBKJDFHG <
13： < JZQAWSXCDERFVBGTYHNUMKILOP <

密钥为：2,3,7,5,13,12,9,1,8,10,4,11,6
密文为：NFQKSEVOQOFNP
```

密钥数字就是行数的排列：

```
2:  < KPBELNACZDTRXMJQOYHGVSFUWI <
3:  < BDMAIZVRNSJUWFHTEQGYXPLOCK <
7:  < GWTHSPYBXIZULVKMRAFDCEONJQ <
5:  < IHFRLABEUOTSGJVDKCPMNZQWXY <
13： < JZQAWSXCDERFVBGTYHNUMKILOP <
12： < LVNCMXZPQOWEIURYTASBKJDFHG <
9:  < XPLTDSRFHENYVUBMCQWAOIKZGJ <
1:  < ZWAXJGDLUBVIQHKYPNTCRMOSFE <
8:  < NOZUTWDCVRJLXKISEFAPMYGHBQ <
10: < UDNAJFBOWTGVRSCZQKELMXYIHP <
4:  < RPLNDVHGFCUKTEBSXQYIZMJWAO <
11： < MNBVCXZQWERTPOIUYALSKDJFHG <
6:  < AMKGHIWPNYCJBFZDRUSLOQXVET <
```

再把密文转到第一列。明文就是其他列中的一列。

python脚本:

```python
import re

table=[2,3,7,5,13,12,9,1,8,10,4,11,6]
Ciphertext='NFQKSEVOQOFNP'
with open(r'test.txt','r') as f:
    data=f.read()

#转轮机根据table重新排列
def wheel_decode(data,table):
    resultList=[]
    pattern = re.compile('[A-Z]{26}')
    result = pattern.findall(data)

    for i in table:
        resultList.append(result[i-1])
    return resultList

resultList = wheel_decode(data,table)



#根据密文重新排列
def rearrange(List,Ciphertext):
    resultList=[]
    for i in range(0,13):
        resultList.append(List[i][List[i].find(Ciphertext[i]):]+List[i][:List[i].find(Ciphertext[i])])
    return resultList
resultList= rearrange(resultList,Ciphertext)

#选取每一列，列出结果
def rearrange2(List):
    resultList=[]
    s=''
    for i in range(0,26):
        for j in List:
            s += j[i]

        resultList.append(s)
        s=''
    return resultList

resultList = rearrange2(resultList)
for i in resultList:
    print(i.lower())
```

```
nfqksevoqofnp
ahgcxiusnwcbn
ctwpcubfotuvy
zetmdrmezgkcc
dqhneyczuvtxj
tgszrtqwtrezb
rypqfawawsbqf
xxywvsaxdcswz
mpbxbbojczxed
jlxygkigvqqrr
qoiitjkdrkytu
oczhydzljeips
ykufhfgullzol
hblrnhjbxmmio
gdvlugxvkxjuq
vmkamlpiiywyx
sambkvlqsiaav
fireinthehole #flag
uzaulcdkfprst
wvfoomsyaupka
irdtpxrppdldm
kncsjzfnmnnjk
psegzphtyadfg
bjojqqecgjvhh
eunvaonrhfhgi
lwjdwwymbbgmw
```

