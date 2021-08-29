# GDA

GDA打开程序，在`MainActivity$1`中找到：

```java
String versionCode = pinfo.versionName;
int versionName = pinfo.versionCode;
int i = 0;
while (true) {	
    if (i < inputString.length() && i < versionCode.length()) {	
        if (inputString.charAt(i) != (versionCode.charAt(i)^versionName)) {	
            Toast.makeText(this.this$0, "再接再厉，加油~", 1).show();
            break ;	
        }else {	
            i++;
        }	
    }else if(inputString.length() == versionCode.length()){		
        Toast.makeText(this.this$0, "恭喜开启闯关之门！", 1).show();
        break ;	
    }	
}	
return;
}catch(android.content.pm.PackageManager$NameNotFoundException e5){	
}	
Toast.makeText(this.this$0, "年轻人不要耍小聪明噢", ix).show();
```

可以判断当`inputString.charAt(i) == (versionCode.charAt(i)^versionName)`和`inputString.length() == versionCode.length()`满足时，成功。

查看xml，找到versionCode和versionName。

```xml
<?xml version="1.0" encoding="utf-8"?>
<manifest xmlns:android="http://schemas.android.com/apk/res/android" android:versionCode="15" android:versionName="X<cP[?PHNB<P?aj" package="com.example.yaphetshan.tencentgreat" platformBuildVersionCode="25" platformBuildVersionName="7.1.1" >
    <uses-sdk android:minSdkVersion="19" android:targetSdkVersion="25" >
    </uses-sdk>
    <uses-permission android:name="android.permission.INTERNET" >
    </uses-permission>
    <meta-data android:name="android.support.VERSION" android:value="25.3.0" >
    </meta-data>
    <application android:theme="@7F0800A3" android:label="@7F060021" android:icon="@7F030000" android:debuggable="true" android:allowBackup="true" android:supportsRtl="true" android:roundIcon="@7F030001" >
        <activity android:name="com.example.yaphetshan.tencentgreat.MainActivity" >
            <intent-filter >
                <action android:name="android.intent.action.MAIN" >
                </action>
                <category android:name="android.intent.category.LAUNCHER" >
                </category>
            </intent-filter>
        </activity>
    </application>
</manifest>
```

需要注意的是，程序开头把两者的值交换了一下。

Python脚本：

```python
versionName = 'X<cP[?PHNB<P?aj'
versionCode = 15

flag = ['' for i in range(15)]
for i in range(15):
    flag[i] = chr(ord(versionName[i]) ^ 15)

print(''.join(flag)) # W3l_T0_GAM3_0ne
```



