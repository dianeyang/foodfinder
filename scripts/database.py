import MySQLdb
from config import db_host, db_user, db_password, db_name

class Database():
	def __init__(self):
		self.conn = MySQLdb.connect(db_host, db_user, db_password, db_name)

	def update_count(self, conn, word, count, is_ff):
		sql = 'SELECT count FROM word WHERE doctype=% and word=%' % (is_ff, word)

	def update_counts(self, d):
		c = self.conn.cursor()
		try:
			for word, count in d.items():
				self.update_count(c, word, count, is_ff)
		finally:
			c.close()
			self.conn.commit()

	def get_count(self, word):
		pass

	def get_counts(self):
		pass