from filterfunctions import *
import MySQLdb
import os
import math

# Open database connection
db = MySQLdb.connect("mysql.hcs.harvard.edu","cs50-foodfinder","OpUwJlywmvty","cs50-foodfinder")

# prepare a cursor object using cursor() method
cursor = db.cursor()

threshold = .70

#########################################################
#
# Scans over emails to calculate the probability
# that they mention free food
#
#########################################################

# Inspired by the process outlined in A Plan For Spam
# by Paul Graham (http://www.paulgraham.com/spam.html)

# Special thanks to Prof. Michael Parzen for his input
# in helping to refine the logic of this algorithm

# Other references used:
# http://en.wikipedia.org/wiki/Bayesian_spam_filtering
#
# http://cs.wellesley.edu/~anderson/writing/naive-bayes.pdf

# parse the dataset into an array
data_array = csv_to_array("/nfs/home/groups/cs50-foodfinder/web/scripts/dataset.csv", "rb")

# retrieve an array of all the unscanned emails
cursor.execute("SELECT plaintext_path, html_path FROM emails WHERE parsed = '0'")
emaillist = cursor.fetchall()

print "EMAILLIST: " + str(emaillist)
 
totalmail = int(data_array[0][0])
totalFF = int(data_array[0][1])
totalNFF = int(data_array[0][2])

# iterate over every unscanned email
for emailrow in emaillist:

    print "EMAILROW: " + str(emailrow)
    
    total = 0
    FFproduct = 1
    NFFproduct = 1
    
    # open the email and read in the text
    if emailrow[0] != '':
		email = open(emailrow[0], "r")
		text = email.read()
		print text

		# delete punctuation and make the text lowercase so it is easier to scan
		for char in delete_chars:
			text = text.replace(char," ")
		text = text.lower()
    
	 # iterate over every keyword pattern
		for keyword in keywords:
		
			# search for the pattern in the text of the email
			pattern = re.compile(keyword)
			found_pattern = re.search(pattern, text)
			if found_pattern:
				print keyword
				# look for the keyword in the dataset
				for datarow in data_array: 
					if keyword in datarow:
						word_found = True
						FFproduct = FFproduct * float(datarow[3])
						NFFproduct = NFFproduct * float(datarow[4])
						break
    
    FFproduct = FFproduct * (float(totalFF + .0001)/totalmail)
    NFFproduct = NFFproduct * (float(totalNFF + .0001)/totalmail)
    
    combined_prob = FFproduct / (FFproduct + NFFproduct)
    
    print "PROBABILITY: " + str(combined_prob)

    # if our degree of belief is over a certain threshold, it's free food
    if combined_prob >= threshold:
        print "FREE FOOD :D"
        # Prepare SQL query to INSERT a record into the database.
        sql = """UPDATE emails SET freefood = '1' WHERE plaintext_path = '%s'""" % (emailrow[0])
        try:
            # Execute the SQL command
            cursor.execute(sql)
            # Commit your changes in the database
            db.commit()
            print "SUCCESSFULLY UPDATED\n\n"
        except:
            # Rollback in case there is any error
            db.rollback()
            print "FAILED TO UPDATE\n\n"

    else:
        print "NOT FREE FOOD :(\n\n"
        
	print "- - - - - - - - - - - - - - - - - - - - - - - - - -"

# disconnect from server
db.close()