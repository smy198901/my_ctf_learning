# RSA

RSA算法的具体描述如下：

（1）任意选取两个不同的大素数p和q计算乘积

![img](C:\my_ctf_learning\writeup\MISC\攻防世界\images\f0dac18152076624d87832b62709895c.svg)

（2）任意选取一个大整数e，满足

![img](C:\my_ctf_learning\writeup\MISC\攻防世界\images\c33d8c66364a636b051d82f0ee202a36.svg)

 ，整数e用做加密钥（注意：e的选取是很容易的，例如，所有大于p和q的素数都可用）；

（3）确定的解密钥d，满足

![img](C:\my_ctf_learning\writeup\MISC\攻防世界\images\da8649c0078a0a842779394d64011776.svg)

 ，即

![img](C:\my_ctf_learning\writeup\MISC\攻防世界\images\4dee3f4df52a81983db0e3c619f96058.svg)

 是一个任意的整数；所以，若知道e和

![img](C:\my_ctf_learning\writeup\MISC\攻防世界\images\679e809a0d964785d0aa4cfcb4218742.svg)

，则很容易计算出d；

（4）公开整数n和e，秘密保存d；

（5）将明文m（m<n是一个整数）加密成密文c，加密算法为

![img](C:\my_ctf_learning\writeup\MISC\攻防世界\images\5947116555169dc6fe9e3f5cdf347706.svg)

（6）将密文c解密为明文m，解密算法为 

![img](C:\my_ctf_learning\writeup\MISC\攻防世界\images\1a8b337167e4d4b2c23855d88ec4c67f.svg)

然而只根据n和e（注意：不是p和q）要计算出d是不可能的。因此，任何人都可对明文进行加密，但只有授权用户（知道d）才可对密文解密 。

## 求d

工具：`RSA-Tool 2 by tE!`，输入p、q和e来计算d，其中e是16进制数。需要注意的是根据p、q的值来选择Number Base采用的进制数。

Python脚本求解：

```python
```

