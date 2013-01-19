######################################################################
#
# MACHINE LEARNING ALGORITHM: TRAINING PHASE
#
# - Scans over the training set of emails
#
# - Counts the number of times each word appears in
#   free food emails & non free food mails
#
# - Calculates P(W|FF), P(W|NFF), and P(FF|W) for each word
#		- W = event that word appears
#		- FF = event that the email is about free food
#		- NFF = event that the email is not free food
#
# - Writes these statistics to a .csv file (the dataset)
#
######################################################################

# Inspired by the process outlined in A Plan For Spam
# by Paul Graham (http://www.paulgraham.com/spam.html)

# Special thanks to Prof. Michael Parzen for his input
# in helping to refine the logic of this algorithm

# Other references used:
# http://en.wikipedia.org/wiki/Bayesian_spam_filtering
#
# http://cs.wellesley.edu/~anderson/writing/naive-bayes.pdf

######################################################################
# PREPARING THE TRAINING SET FOR ITERATION
######################################################################

from filterfunctions import *

trainpath = "trainingset.csv"

# convert the csv file to a 2-dimensional array
data_array = csv_to_array("dataset.csv", "rb")

# retrieve desired values
totalmail = int(data_array[0][0])
totalFF = int(data_array[0][1])
totalNFF = int(data_array[0][2])
    
# convert the training set to a 2-d array
train_array = csv_to_array(trainpath, "rU")


######################################################################
# LOOPING OVER EVERY EMAIL SEARCHING FOR EVERY KEYWORD
######################################################################

# loop over every email in the training set

for row in train_array:

    # update the total mail count
    totalmail = totalmail+1
    
    # if email is free food, update totalFF
    if int(row[1]) == 1:
        totalFF = int(totalFF)+1
   
    # if email is not free food, update totalNFF
    elif int(row[1]) == 0:
        totalNFF = int(totalNFF)+1
        
    data_array[0] = [totalmail, totalFF, totalNFF]
        
    # open the email at the given path & store its text in a variable
    email = open(row[0], "r")
    text = email.read()
    
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
		
			# variable to flag whether word is in dataset
			word_found = False
			
			# look for the keyword in the dataset
			for datarow in data_array:
				# if it's already there, update the statistics
				if keyword in datarow:
					word_found = True
					if int(row[1]): # if it's free food
						# FF count + 1
						datarow[1] = int(datarow[1]) + 1
						break
					elif int(row[1]) == 0: # if it's not free food
						# NFF count + 1
						datarow[2] = int(datarow[2]) + 1
						break
						
			# if the keyword is not already in the dataset
			if not word_found:
				# create an entry for the word in the dataset
				if int(row[1]):
					data_array.append([keyword,1,0])
				elif int(row[1]) == 0:
					data_array.append([keyword,0,1])
     
    # close the email file      
    email.close()
    
################################################################################
# 
# 										 			P(W|FF)P(FF)
# TIME FOR SOME BAYES THEOREM:	 P(FF|W) =	-----------------------------
#											P(W|FF)P(FF) + P(W|NFF)P(NFF)
#
################################################################################

pFF = float(totalFF) / totalmail    # P(FF)
pNFF = float(totalNFF) / totalmail  # P(NFF)

# loop over every keyword's entry in the dataset
for row in data_array:

    # as long as we're not on the first row (because those are just total counts)
    if row != data_array[0] and (len(row) == 3 or len(row) == 6): # WEIRD ERROR: WHERE ARE THESE RANDOM ROWS COMING FROM
    
        # calculate P(W|FF) = (FF count) / (total FF emails)
        if int(row[1]) != 0:
           pWgFF = float(row[1]) / totalFF
           
        # calculate P(W|NFF) = (NFF count) / (total NFF emails)   
        if int(row[2]) != 0:
            pWgNFF = float(row[2]) / totalNFF
        
        # since we're dealing with a relatively small dataset, we shouldn't assume that
        # P(W|FF) is zero just because we haven't seen it in a free food email before,
        # so we arbitrarily assign it a relatively small probability relative to P(W|FF)
        if int(row[1]) == 0:
            pWgFF = pWgNFF / 6
            
        # Again, we shouldn't assume P(W|NFF) is zero
        elif int(row[2]) == 0:
            pWgNFF = pWgFF / 6
        
        # calculate P(FF|W) = P(W|FF)P(FF) / P(W|FF)P(FF)+P(W|NFF)P(NFF)    
        pFFgW = float((pWgFF * pFF) / ((pWgFF * pFF) + (pWgNFF * pNFF)))
            
        # save each of these values to the word's entry in the dataset
        if len(row) == 3:
            row.append(pWgFF)
            row.append(pWgNFF)
            row.append(pFFgW)
        elif len(row) == 6: # where the eff are these random number rows coming from
            row[3] = pWgFF
            row[4] = pWgNFF
            row[5] = pFFgW

# write the contents of data_array to dataset.csv
array_to_csv(datapath, data_array, "w")
