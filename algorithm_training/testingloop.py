from filterfunctions import *

threshold = .74

#########################################################
#
# T E S T I N G   P H A S E
#
#########################################################

# parse the dataset into an array
data_array = csv_to_array("dataset.csv", "rb")

resultfile = open("results-NEW.csv", "w")

test_array = csv_to_array("testset.csv", "rb")

totalmail = int(data_array[0][0])
totalFF = int(data_array[0][1])
totalNFF = int(data_array[0][2])

for row in test_array:

    total = 0
    FFproduct = 1
    NFFproduct = 1

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
				if keyword in datarow:
					word_found = True
					FFproduct = FFproduct * float(datarow[3])
					NFFproduct = NFFproduct * float(datarow[4])
					break
    
    FFproduct = FFproduct * (float(totalFF + 1)/totalmail)
    NFFproduct = NFFproduct * (float(totalNFF + 1)/totalmail)
    
    combined_prob = FFproduct / (FFproduct + NFFproduct)
    row.append(combined_prob)

    # if our degree of belief is over a certain threshold, it's free food
    if combined_prob > threshold:
        row.append(1)
        freefood = 1
    else:
        row.append(0)
        freefood = 0
    
    if int(row[1]) != freefood:
        row.append("WRONG")
        
array_to_csv("results-NEW.csv", test_array, "wb")
