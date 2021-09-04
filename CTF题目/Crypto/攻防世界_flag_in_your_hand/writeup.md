分析代码，发现在执行完`fg = bm(token);`后，ic被改变了。

在`script-min.js`查找ic，发现了：

```javascript
function ck(s) {
    try {
        ic
    } catch (e) {
        return;
    }
    var a = [118, 104, 102, 120, 117, 108, 119, 124, 48,123,101,120];
    if (s.length == a.length) {
        for (i = 0; i < s.length; i++) {
            if (a[i] - s.charCodeAt(i) != 3)
                return ic = false;
        }
        return ic = true;
    }
    return ic = false;
}
```

计算s得到：security-xbu，s才是真的Token，输入Token，得到flag：RenIbyd8Fgg5hawvQm7TDQ