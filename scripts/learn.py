from database import Database
from fetch_emails import fetch_emails
from cleanwords import text_to_list, list_to_dict

class Learn():
	def main(self):
		db = Database()
		for email in fetch_emails():
			words = text_to_list(self.email)
			word_counts = list_to_dict(words)
			db.update_counts(word_counts)
		return self.count