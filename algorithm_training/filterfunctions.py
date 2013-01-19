#########################################################
#
# M O D U L E S
#
#########################################################

import csv
import email
import imaplib
import math
import os
import MySQLdb
import poplib
import string
import re
from email import parser

#########################################################
#
# C O N S T A N T S
#
#########################################################

address = "harvardfoodfinder@gmail.com"
password = "canadayf34"
emailsavepath = "../trainingset/" # where to save the downloaded files
trainpath = "../data/trainingset-perm.csv"
datapath = "../data/dataset-FINAL.csv"
resultpath = "../data/results.csv"

stopwords = [   "a","an","and",
             "for","from",
             "in","is","it",
             "not",
             "of","on","or",
             "that","the","this","to",
             "with","was","were"]

stopwords2 = ["sent","fas","email","html","www","hcs", "edu", "mailing", "list", "lists", "harvard", "mailman","listinfo","https","http"]

# list of regular expressions representing words that likely indicate free food or not free food
keywords = [r'$', r'applications?', r'apply',
			r'buys?',
			r'concerts?', r'costs?',
			r'^deliver', r'^donat(es?|ions?)',
			r'elections?',
			r'fundrais(ers?|ing)',
			r'money',
			r'order(s?|ed)',
			r'paid', r'pay(s?|ments?)', r'performances?', r'presales?', r'prices?',]

#########################################################
#
# F U N C T I O N S
#
#########################################################

# writes a 2-dimensional array into a csv file
def array_to_csv(path, array, mode):
    
    csvfile = open(path, mode)
    
    # write the contents of data_array to dataset.csv
    csvfile.seek(0,0)
    for row in array:
        for col in row:
            csvfile.write(str(col) + ",")
        csvfile.seek(-1,1)
        csvfile.write("\n")

    # close the dataset file
    csvfile.close()
    
# parses a csv file into a 2-dimensional array
def csv_to_array(path, mode):
    
    # open the file in the given mode
    csvfile = open(path, mode)
    
    # parse the csv file into a reader object
    parsed_file = csv.reader(csvfile, delimiter=",")
    
    array = []
    
    # convert the reader object into a 2-dimensional array
    for row in parsed_file:
        array.append(row)
    
    csvfile.close()

    return array

# downloads an email with a given ID & saves it to a .txt file with a given number
def download(uid,num,mail,path):
    
    # fetch the email body (RFC822) for the given ID
    result, data = mail.uid('fetch', uid, '(RFC822)')
    
    # here's the body, which is raw text of the whole email
    # including headers and alternate payloads
    raw_email = data[0][1]
    
    # translate raw text into message object
    email_message = email.message_from_string(raw_email)
    
    # create a file in which to save the email
    email_file = open(path+"email"+str(num)+".txt","w")
    
    # write the subject to the file
    email_file.write(email_message['Subject']+"\n")
    
    # each part is a either non-multipart, or another multipart message
    # that contains further parts... Message is organized like a tree
    for part in email_message.walk():
        
        # write the plaintext body to the file
        if part.get_content_type() == 'text/plain':
            email_file.write(part.get_payload())
 
# downloads an inline image in an email & inserts it into corresponding html document           
def save_image(db, part, imagepath, ext, htmlpath, num):
	# writes image file
	image_file = open(imagepath+ext,"w")
	image_file.write(part.get_payload(decode=True))
	image_file.close()
	
	# edit the html email to insert the image
	html_file = open(htmlpath,"r")
	messagetext = html_file.read()
	html_file.close()
	html_file = open(htmlpath,"w")
	imgpattern = re.compile(r'<img.*?cid:.*?>', re.DOTALL)
	messagetext = re.sub(imgpattern,"<img src=\"/cs50-foodfinder/email_images/email_image"+str(num)+ext+"\">", messagetext)
	html_file.write(messagetext)
	html_file.close()
	
# split a string into an array of lowercase words
def str_to_array(string):
    
    delete_chars =  [",","?","!",";","\"","(",")","[","]","{","}","<",">","*",
                     "0","1","2","3","4","5","6","7","8","9","#","%","^","&","~",
                     "`","\n", "/", "|","-","_","+","=","@","~",".",":"]
    
    # strip the email of punctuation & other unwanted characters
    for char in delete_chars:
        nopunct = string.replace(char," ")
    
    # make everything lowercase
    nopunct = nopunct.lower()
    
    # split the email into an array of separate words
    words = nopunct.split( );
    
    return words
