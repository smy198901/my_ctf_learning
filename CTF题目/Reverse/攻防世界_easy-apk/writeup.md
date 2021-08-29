# GDA

GDA打开程序，得到：

```java
class MainActivity$1 extends Object implements View$OnClickListener	// class@000840
{
    final MainActivity this$0;

    void MainActivity$1(MainActivity this$0){	
       this.this$0 = this$0;
       super();
    }
    public void onClick(View view){	
       String strIn = this.this$0.findViewById(2131427445).getText();
       Base64New nb = new Base64New();
       String enStr = nb.Base64Encode(strIn.getBytes());
       if (enStr.equals("5rFf7E2K6rqN7Hpiyush7E6S5fJg6rsi5NBf6NGT5rs=")) {	
          Toast.makeText(this.this$0, "验证通过!", 1).show();
       }else {	
          Toast.makeText(this.this$0, "验证失败!", 1).show();
       }	
       return;
    }
}
```

可以知道输入的字符串经过Base64New加密得到的值与`5rFf7E2K6rqN7Hpiyush7E6S5fJg6rsi5NBf6NGT5rs=`相同，即可成功。

但是`5rFf7E2K6rqN7Hpiyush7E6S5fJg6rsi5NBf6NGT5rs=`不是有效的base64字符串。

查看`Base64New`：

```java
public class Base64New extends Object	// class@00083e
{
    private static final char[] Base64ByteToStr;
    private static final int RANGE;
    private static byte[] StrToBase64Byte;

    static {	
       Base64New.Base64ByteToStr = new char[64]{0x76,0x77,0x78,0x72,0x73,0x74,0x75,0x6f,0x70,0x71,0x33,0x34,0x35,0x36,0x37,0x41,0x42,0x43,0x44,0x45,0x46,0x47,0x48,0x49,0x4a,0x79,0x7a,0x30,0x31,0x32,0x50,0x51,0x52,0x53,0x54,0x4b,0x4c,0x4d,0x4e,0x4f,0x5a,0x61,0x62,0x63,0x64,0x55,0x56,0x57,0x58,0x59,0x65,0x66,0x67,0x68,0x69,0x6a,0x6b,0x6c,0x6d,0x6e,0x38,0x39,0x2b,0x2f};
       byte[] obyteArray = new byte[128];
       Base64New.StrToBase64Byte = obyteArray;
    }
    public void Base64New(){	
       super();
    }
    public String Base64Encode(byte[] bytes){	
       byte[] enBytes;
       StringBuilder res = new StringBuilder();
       int i = 0;
       while (i <= (bytes.length-1)) {	
          enBytes = new byte[4];
          byte tmp = 0;
          int k = 0;
          while (k <= 2) {	
             if ((i+k) <= (bytes.length-1)) {	
                enBytes[k] = (byte)(((bytes[(i+k)]&0x00ff)>>((k*2)+2))|tmp);
                tmp = (byte)((((bytes[(i+k)]&0x00ff)<<(((k-2)*2)+2))&0x00ff)>>2);
             }else {	
                enBytes[k] = tmp;
                tmp = 64;
             }	
             k++;
          }	
          enBytes[3] = tmp;
          k = 0;
          while (k <= 3) {	
             res = (enBytes[k] <= 63)? res.append(Base64New.Base64ByteToStr[enBytes[k]]): res.append('=');	
             k++;
          }	
          i = i+3;
       }	
       return res.toString();
    }
}
```

打印出`Base64ByteToStr`为字符串：vwxrstuopq34567ABCDEFGHIJyz012PQRSTKLMNOZabcdUVWXYefghijklmn89+/，非标准的base64字符串，可以把加密串转为标准的base64加密串，再解密。

python脚本：

```python
import base64

base_old = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
base_str = [0x76,0x77,0x78,0x72,0x73,0x74,0x75,0x6f,0x70,0x71,0x33,0x34,0x35,0x36,0x37,0x41,0x42,0x43,0x44,0x45,0x46,0x47,0x48,0x49,0x4a,0x79,0x7a,0x30,0x31,0x32,0x50,0x51,0x52,0x53,0x54,0x4b,0x4c,0x4d,0x4e,0x4f,0x5a,0x61,0x62,0x63,0x64,0x55,0x56,0x57,0x58,0x59,0x65,0x66,0x67,0x68,0x69,0x6a,0x6b,0x6c,0x6d,0x6e,0x38,0x39,0x2b,0x2f]
base_new = ''
for i in base_str:
    base_new = base_new + chr(i)

encode_flag = '5rFf7E2K6rqN7Hpiyush7E6S5fJg6rsi5NBf6NGT5rs='
real_encode_flag = ''
for i in encode_flag:
    if i != '=':
        temp = base_old[base_new.index(i)]
        real_encode_flag = real_encode_flag + temp
    else:
        real_encode_flag = real_encode_flag + i
print(real_encode_flag)
print('flag:{}'.format(base64.b64decode(real_encode_flag))) # 05397c42f9b6da593a3644162d36eb01
```

flag：flag{05397c42f9b6da593a3644162d36eb01}