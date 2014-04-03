import re
from collections import defaultdict

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

def clean(word):
	'''
	Filter out words that don't contribute to meaning
	'''
	word = word.lower()
	word = word.translate(None, "?!.,:;()*\"\'") # strip chars that could appear in usable word
	
	if re.sub(r'[\w]', '', word): # word contains undesirable characters
		return None
	if len(word) < 2:
		return None
	elif (word.isdigit()):
		return None
	elif word in stopwords:
		return None
	return word

def text_to_list(text):
	raw_words = re.split('\W+', text.strip())
	clean_words = map(clean, raw_words)
	return filter(lambda word: word, clean_words)

def list_to_dict(lst):
	d = defaultdict(int)
	for word in lst:
		d[word] += 1
	return d