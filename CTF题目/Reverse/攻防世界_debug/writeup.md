# Exeinfo

32位 .net程序

# dnSpy

.net程序，用IDA打开无法进行反编译，使用dnSpy打开程序。

发现主要代码都在`02000003`中：

```c#
private static void ᜀ(string[] A_0)
	{
		string b = null;
		string value = string.Format("{0}", DateTime.Now.Hour + 1);
		string a_ = "CreateByTenshine";
		ᜅ.ᜀ(a_, Convert.ToInt32(value), ref b);
		string a = Console.ReadLine();
		if (a == b)
		{
			Console.WriteLine("u got it!");
			Console.ReadKey(true);
		}
		else
		{
			Console.Write("wrong");
		}
		Console.ReadKey(true);
	}
```

当a == b时，成功。

在if (a == b)处设置断点，直接运行程序，就可得到flag。

![image-20210828211924935](images\image-20210828211924935.png)

