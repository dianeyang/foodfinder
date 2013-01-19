#########################################################
#
# downloads all emails from an inbox to a directory
#
#########################################################

# Used code by Yuji Tomita from:
# http://yuji.wordpress.com/2011/06/22/python-imaplib-imap-example-with-gmail/

# Also incorporates code by Jarret Hardie from:
# http://stackoverflow.com/questions/1463074/how-can-i-get-an-email-messages-text-content-using-python

from filterfunctions import *

# the reason i named each file emailemail was to help differentiate this
# set from the hundreds of other emails i downloaded
path = "finalset/emailemail"

# logging in to the inbox
mail = imaplib.IMAP4_SSL('imap.gmail.com')
mail.login(address, password)
mail.list()
# Out: list of "folders" aka labels in gmail.
mail.select("inbox") # connect to inbox.

# search and return uids
result, data = mail.uid('search', None, "ALL")

# data is a list
ids = data[0]

# ids is a space separated string --> split it into an array
id_list = ids.split()

csvfile = open("trainingset.csv","w")

# loop over every email in the inbox & save them into separate files
num = 1 # for numbering the output files
for id in id_list:
    download(id, num, mail, path)
    csvfile.write(path+str(num)+".txt,\n")
    print num
    num = num + 1

