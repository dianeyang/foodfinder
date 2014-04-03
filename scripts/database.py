import MySQLdb
from config import db_host, db_user, db_password, db_name


class Database():
	def __init__(self):
		self.conn = MySQLdb.connect(db_host, db_user, db_password, db_name)

	def get_next_email_num(self, conn):
		conn.execute("INSERT INTO emails(id) VALUES(NULL)")
	    conn.execute("SELECT MAX(id) from emails")
	    row = cursor.fetchone()
	    return row[0]

	def insert_email(self, email, num):
		c = self.conn.cursor()
		try:
			htmlpath = html_path + str(num) + ".html"
			link = link_path + str(num) + ".html"
			plainpath = plain_path + str(num) + ".html"
	    	sql = "UPDATE emails SET plaintext_path='%s', html_path='%s', link='%s', subject='%s' WHERE id='%d'" % (plainpath, htmlpath, link, message['subject'], num)
	        c.execute(sql)
	    finally:
		    c.close()
		    self.conn.commit()
	
	def get_unprocessed_emails(self):
		c = self.conn.cursor()
		c.execute("SELECT plaintext_path, html_path FROM emails WHERE parsed = '0'")
		return c.fetchall() 

	def mark_free_food(self, path):
		c = self.conn.cursor()
        sql = """UPDATE emails SET freefood = '1' WHERE plaintext_path = '%s'""" % path
        try:
            c.execute(sql)
        finally:
            c.close()
            self.conn.commit()

