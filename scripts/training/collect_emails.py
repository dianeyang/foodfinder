# Borrowed code by Yuji Tomita from:
# http://yuji.wordpress.com/2011/06/22/python-imaplib-imap-example-with-gmail/

# Also incorporates code by Jarret Hardie from:
# http://stackoverflow.com/questions/1463074/how-can-i-get-an-email-messages-text-content-using-python

from filterfunctions import *

path = "finalset/email"

# logging in to the inbox
mail = imaplib.IMAP4_SSL('imap.gmail.com')
mail.login(address, password)
mail.list()
mail.select("inbox")

# search and return uids
result, data = mail.uid('search', None, "ALL")

ids = data[0]
id_list = ids.split()

csvfile = open("trainingset.csv","w")

num = 1
for id in id_list:
    download(id, num, mail, path)
    csvfile.write(path+str(num)+".txt,\n")
    print num
    num = num + 1

