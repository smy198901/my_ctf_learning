# GDA

GDA打开文件，发现：

```java
class MainActivity$1 extends Object implements View$OnClickListener	// class@000411
{
    final Context a;
    final MainActivity b;

    void MainActivity$1(MainActivity p0,Context p1){	
       this.b = p0;
       this.a = p1;
       super();
    }
    public void onClick(View p0){	
       int i = 1;
       if (MainActivity.a(this.a.findViewById(2131427445).getText()).booleanValue()) {	
          Toast.makeText(this.a, "You are right!", i).show();
       }else {	
          Toast.makeText(this.a, "You are wrong! Bye~", i).show();
          new Timer().schedule(new MainActivity$1$1(this), 2000);
       }	
       return;
    }
}
```

满足"You are right!"条件的方法：

```java
 private static Boolean b(String p0){	
       Boolean bOf;
       boolean b = false;
       if (!p0.startsWith("flag{")) {	
          bOf = Boolean.valueOf(b);
       }else if(!p0.endsWith("}")){		
          bOf = Boolean.valueOf(b);
       }else {	
          String ssubstring = p0.substring(5, (p0.length()-1));
          b ob = new b(Integer.valueOf(2));
          a oa = new a(Integer.valueOf(3));
          StringBuilder stringBuilde = new StringBuilder();
          boolean b1 = b;
          while (b < ssubstring.length()) {	
             stringBuilde = stringBuilde.append(MainActivity.a(new StringBuilder().append(ssubstring.charAt(b)).append("").toString(), ob, oa));
             Integer iOf = Integer.valueOf((ob.b().intValue()/25));
             if (iOf.intValue() > b1 && iOf.intValue() >= 1) {	
                b1 = b1+1;
             }	
             b = b+1;
          }	
          bOf = Boolean.valueOf(stringBuilde.toString().equals("wigwrkaugala"));
       }	
       return bOf;
    }

```

可以得知，需要满足方法while部分加密后的结果需要与"wigwrkaugala"相等。

加密函数如下：

```java
 private static char a(String p0,b p1,a p2){	
       return p2.a(p1.a(p0));
 }
```

```java
public class b extends Object	// class@000414
{
    Integer[] c;
    public static ArrayList a;
    static String b;
    static Integer d;

    static {	
       b.a = new ArrayList();
       b.b = "abcdefghijklmnopqrstuvwxyz";
       b.d = Integer.valueOf(0);
    }
    public void b(Integer p0){	
       Integer[] integerArray;
       int i = 0;
       super();
       integerArray = new Integer[26];
       integerArray[i] = Integer.valueOf(8);
       integerArray[1] = Integer.valueOf(25);
       integerArray[2] = Integer.valueOf(17);
       integerArray[3] = Integer.valueOf(23);
       integerArray[4] = Integer.valueOf(7);
       integerArray[5] = Integer.valueOf(22);
       integerArray[6] = Integer.valueOf(1);
       integerArray[7] = Integer.valueOf(16);
       integerArray[8] = Integer.valueOf(6);
       integerArray[9] = Integer.valueOf(9);
       integerArray[10] = Integer.valueOf(21);
       integerArray[11] = Integer.valueOf(i);
       integerArray[12] = Integer.valueOf(15);
       integerArray[13] = Integer.valueOf(5);
       integerArray[14] = Integer.valueOf(10);
       integerArray[15] = Integer.valueOf(18);
       integerArray[16] = Integer.valueOf(2);
       integerArray[17] = Integer.valueOf(24);
       integerArray[18] = Integer.valueOf(4);
       integerArray[19] = Integer.valueOf(11);
       integerArray[20] = Integer.valueOf(3);
       integerArray[21] = Integer.valueOf(14);
       integerArray[22] = Integer.valueOf(19);
       integerArray[23] = Integer.valueOf(12);
       integerArray[24] = Integer.valueOf(20);
       integerArray[25] = Integer.valueOf(13);
       this.c = integerArray;
       for (int iValue = p0.intValue(); iValue < this.c.length; iValue = iValue+1) {	
          b.a.add(this.c[iValue]);
       }	
       for (iValue = i; iValue < p0.intValue(); iValue = iValue+1) {	
          b.a.add(this.c[iValue]);
       }	
    }
    public static void a(){	
       b.a.remove(0);
       b.a.add(Integer.valueOf(b.a.get(0).intValue()));
       b.b = new StringBuilder()+b.b+""+b.b.charAt(0);
       b.b = b.b.substring(1, 27);
       b.d = Integer.valueOf((b.d.intValue()+1));
    }
    public Integer a(String p0){	
       int i = 0;
       Integer iOf = Integer.valueOf(i);
       if (b.b.contains(p0.toLowerCase())) {	
          Integer iOf1 = Integer.valueOf(b.b.indexOf(p0));
          while (i < (b.a.size()-1)) {	
             if (b.a.get(i) == iOf1) {	
                iOf = Integer.valueOf(i);
             }	
             i = i+1;
          }	
       }else if(p0.contains(" ")){		
          iOf = Integer.valueOf(-10);
       }else {	
          iOf = Integer.valueOf(-1);
       }	
       b.a();
       return iOf;
    }
    public Integer b(){	
       return b.d;
    }
}
```

```java
public class a extends Object	// class@000413
{
    Integer[] c;
    public static ArrayList a;
    static String b;
    static Integer d;

    static {	
       a.a = new ArrayList();
       a.b = "abcdefghijklmnopqrstuvwxyz";
       a.d = Integer.valueOf(0);
    }
    public void a(Integer p0){	
       Integer[] integerArray;
       int i = 0;
       super();
       integerArray = new Integer[26];
       integerArray[i] = Integer.valueOf(7);
       integerArray[1] = Integer.valueOf(14);
       integerArray[2] = Integer.valueOf(16);
       integerArray[3] = Integer.valueOf(21);
       integerArray[4] = Integer.valueOf(4);
       integerArray[5] = Integer.valueOf(24);
       integerArray[6] = Integer.valueOf(25);
       integerArray[7] = Integer.valueOf(20);
       integerArray[8] = Integer.valueOf(5);
       integerArray[9] = Integer.valueOf(15);
       integerArray[10] = Integer.valueOf(9);
       integerArray[11] = Integer.valueOf(17);
       integerArray[12] = Integer.valueOf(6);
       integerArray[13] = Integer.valueOf(13);
       integerArray[14] = Integer.valueOf(3);
       integerArray[15] = Integer.valueOf(18);
       integerArray[16] = Integer.valueOf(12);
       integerArray[17] = Integer.valueOf(10);
       integerArray[18] = Integer.valueOf(19);
       integerArray[19] = Integer.valueOf(i);
       integerArray[20] = Integer.valueOf(22);
       integerArray[21] = Integer.valueOf(2);
       integerArray[22] = Integer.valueOf(11);
       integerArray[23] = Integer.valueOf(23);
       integerArray[24] = Integer.valueOf(1);
       integerArray[25] = Integer.valueOf(8);
       this.c = integerArray;
       for (int iValue = p0.intValue(); iValue < this.c.length; iValue = iValue+1) {	
          a.a.add(this.c[iValue]);
       }	
       for (iValue = i; iValue < p0.intValue(); iValue = iValue+1) {	
          a.a.add(this.c[iValue]);
       }	
    }
    public static void a(){	
       a.d = Integer.valueOf((a.d.intValue()+1));
       if (a.d.intValue() == 25) {	
          a.a.remove(0);
          a.a.add(Integer.valueOf(a.a.get(0).intValue()));
          a.d = Integer.valueOf(0);
       }	
       return;
    }
    public char a(Integer p0){	
       char cAt;
       int i = 0;
       Integer iOf = Integer.valueOf(i);
       if (p0.intValue() == -10) {	
          a.a();
          cAt = " ".charAt(i);
       }else {	
          while (i < (a.a.size()-1)) {	
             if (a.a.get(i) == p0) {	
                iOf = Integer.valueOf(i);
             }	
             i = i+1;
          }	
          a.a();
          cAt = a.b.charAt(iOf.intValue());
       }	
       return cAt;
    }
}
```

分析得知，class b中的a(),会每加密一个char就会执行一次，把类b中的ArrayList a和String b，首字符添加到末尾，再删除首字符。

python脚本：

```python
def decode(msg):
    b = [17, 23, 7, 22, 1, 16, 6, 9, 21, 0, 15, 5, 10, 18, 2, 24, 4, 11, 3, 14, 19, 12, 20, 13, 8, 25]
    bString = "abcdefghijklmnopqrstuvwxyz"
    a = [21, 4, 24, 25, 20, 5, 15, 9, 17, 6, 13, 3, 18, 12, 10, 19, 0, 22, 2, 11, 23, 1, 8, 7, 14, 16]
    aString = "abcdefghijklmnopqrstuvwxyz"
    msgLength = len(msg)
    flag = ''
    for i in range(msgLength):
        idx1 = aString.index(msg[i])
        p0 = a[idx1]
        idx2 = b[p0]
        flag = flag + bString[idx2]
        temp = [0 for i in range(26)]
        for i in range(26 - 1):
            temp[i] = b[i + 1]
        temp[25] = b[0]
        b = temp
        bString = bString + bString[0]
        bString = bString[1:]
    return flag



encode_flag = 'wigwrkaugala'
print(decode(encode_flag)) # venividivkcr
```

得到flag：flag{venividivkcr}