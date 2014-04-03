import csv
import os
import MySQLdb
import re


stopwords = ("a", "all", "an", "any", "and", "at", "are", "as", "about", "also",
			 "be", "by", "but",
			 "can", "could",
			 "do",
             "for","from",
             "get", "go",
             "have", "his", "him", "he", "her", "how",
             "in", "into", "is","it", "if",
             "just",
             "know",
             "me",
             "not", "now", "no",
             "of","on","or", "one",
             "she", "say", "so", "some",
             "take", "that","the","this","to", "they", "their", "there", "these",
             "with", "when", "what", "would", "will","was","were", "we", "who", "which",
             "you", "your")

def array_to_csv(path, array, mode):
    csvfile = open(path, mode)
    
    csvfile.seek(0,0)
    for row in array:
        for col in row:
            csvfile.write(str(col) + ",")
        csvfile.seek(-1,1)
        csvfile.write("\n")

    csvfile.close()
    
def csv_to_array(path, mode):
    csvfile = open(path, mode)
    parsed_file = csv.reader(csvfile, delimiter=",")
    array = []
    
    for row in parsed_file:
        array.append(row)
    
    csvfile.close()

    return array

def clean(word):
	word = word.lower()
	word = word.translate(None, "?!.,:;()*\"\'")
	
	if re.sub(r'[\w]', '', word):
		return None
	if len(word) < 2:
		return None
	elif (word.isdigit()):
		return None
	elif word in stopwords:
		return None
	return word

def str_to_array(text):
	raw_words = re.split('\W+', text.lower().strip())
	clean_words = map(clean, raw_words)
	return filter(lambda word: word, clean_words)
