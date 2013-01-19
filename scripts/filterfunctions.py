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

delete_chars =  [",","?","!",";","\"","(",")","[","]","{","}","<",">","*",
                 "0","1","2","3","4","5","6","7","8","9","#","%","^","&","~",
                 "`","\n", "/", "|","-","_","+","=","@","~",".",":"]

stopwords = [   "a","an","and",
             "for","from",
             "in","is","it",
             "not",
             "of","on","or",
             "that","the","this","to",
             "with","was","were"]

stopwords2 = ["sent","fas","email","html","www","hcs", "edu", "mailing", "list", "lists", "harvard", "mailman","listinfo","https","http"]

# list of regular expressions representing words that likely indicate free food or not free food
keywords = [r'\$', r'applications?', r'apply',
			r'buys?',
			r'concerts?', r'costs?',
			r'deliver', r'donat(es?|ions?)',
			r'elections?',
			r'fundrais(ers?|ing)',
			r'money',
			r'\border(s?|ed)',
			r'\bpaid', r'pay(s?|ments?)', r'performances?', r'presales?', r'prices?', r'proceeds', r'profits?', r'purchases?',
			r'\bre:?\b', r'sales?', r'sell(s?|ing)', r'surveys?', r'tickets?',
			
			r'alcohol(ic)?', r'appetizers?',
			r'bagels?', r'bake(d?|s?)', r'baking', r'barbe[cq]ue', r'bbq', r'beans?', r'beers?', r'berryline',
			r'bertucci', r'beverages?', r'blueberry', r'\bboba', r'\bboloco', r'\bbonchon', r'breads?$',
			r'breakfasts?', r'brunch(es)?', r'buffets?', r'burdick', r'burgers?', r'burritos?',
			r'\bcafes?\b', r'cakes?', r'cand(y|ies)', r'cater(ed?|ing?)', r'\bchai\b', r'cheeses?', r'cheeseburgers?',
			r'chicken', r'chipotle', r'chips?', r'chocolate', r'cider', r'cocktails?', r'colloquium',
			r'cook(s?|ing?)', r'cookies?', r'\bcreamy?', r'cuisines?', r'cupcakes?', r'\bcurry',
			r'delicious', r'desserts?', r'dine(s?|r?)', r'dinners?', r'donuts?', r'doughnuts?', r'drinks?',
			r'\beats?\b',
			r'falafel', r'felipe', r'finale', r'foods?', r'free', r'frie(s|d)',
			r'froyo', r'gourmet', r'guacamole', r'hackathons?', r'homemade', r'b\hors\b', r'hungry',
			r'\bice\b', r'insomnia',
			r'\bjp\b',
			r'licks', r'loaf', r'lunch',
			r'macaro+n', r'marshmallow', r'\bmeal', r'\bmeat', r'milk', r'mixer', r'muffin', r'munch', r'mushroom',
			r'noch', r'noodle',
			r'oeuvre', r'\boggi',
			r'panera', r'\bpart(y|ies)', r'pasta', r'pastr(y|ies)', r'\bpho\b', r'\bpies?\b', r'pinkberry', r'pinocchio',
			r'pizza', r'pocky', r'popcorn', r'provided', r'pumpkin',
			r'qdoba',
			r'ramen', r'reception', r'\brice\b',
			r'salad', r'sample', r'sandwich', r'serv(e|ing)', r'snack', r'\bsoda', r'soup', r'strawberry', r'study break'
			r'sushi', r'sweets', r'syrup',
			r'\btaco', r'taffy', r'takeout', r'taquito', r'tast(e|y)', r'\bteas?\b', r'\bticknor', r'toast', r'topping',
			r'vanilla', r'vegetable', r'vegetarian', r'veggies?',
			r'waffle', r'wine',
			r'yogurt', r'yummy',
			r'zinneken']
			

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
    email_file = open(path+str(num)+".txt","w")
    
    # write the subject to the file
    email_file.write(email_message['Subject']+"\n")
    
    # each part is a either non-multipart, or another multipart message
    # that contains further parts... Message is organized like a tree
    for part in email_message.walk():
        
        # write the plaintext body to the file
        if part.get_content_type() == 'text/plain':
            email_file.write(part.get_payload())
 
# downloads an inline image in an email & inserts it into corresponding html document           
def save_image(db, part, ext, htmlpath, email_num, img_num):

	img_name = "email_image" + str(email_num) + "_" + str(img_num) + ext

	# writes image file
	image_file = open("/nfs/home/groups/cs50-foodfinder/web/email_images/" + img_name,"w")
	image_file.write(part.get_payload(decode=True))
	image_file.close()
	print "DOWNLOADED IMG " + str(img_num)
	
	# edit the html email to insert the image
	html_file = open(htmlpath,"r")
	messagetext = html_file.read()
	html_file.close()
	
	html_file = open(htmlpath,"w")
	imgpattern = re.compile(r'<img.*?cid:.*?>', re.DOTALL)
	#messagetext = re.subn(imgpattern,"<a href='http://www.google.com'>CLICK HERE " + str(img_num) + "</a>", messagetext, 1)
	#messagetext = re.subn(imgpattern,"<img src='/cs50-foodfinder/email_images/" + img_name + "'>", messagetext, 1)
	#messagetext = re.subn(imgpattern,'<img src="/cs50-foodfinder/email_images/' + img_name + '">', messagetext, 1)
	if messagetext[1] > 0:
		print str(messagetext[1]) + " REPLACED IMAGE " + str(img_num)
	html_file.write(messagetext[0])
	html_file.close()
	
# split a string into an array of lowercase words
def str_to_array(string):
    
    # strip the email of punctuation & other unwanted characters
    for char in delete_chars:
        nopunct = string.replace(char," ")
    
    # make everything lowercase
    nopunct = nopunct.lower()
    
    # split the email into an array of separate words
    words = nopunct.split( );
    
    return words
