import MySQLdb
import gmail
from config import email_address, email_password, plain_path, html_path, link_path


class EmailDownloader():
    def __init__():
        self.account = gmail.login(email_address, email_password)

    def fetch_emails():
        inbox =  self.account.inbox().mail(unread=True)
        for email in inbox:
            email.read()
            email.fetch()
            yield email

    def save_email(self, email, num):
        html_file = open(htmlpath,"w")
        html_file.write(email.subject+"\n")
        html_file.write(email.html)
        html_file.close()

        plain_file = open(plainpath,"w")
        plain_file.write(email.subject+"\n")
        plain_file.write(email.html)
        plain_file.close()   

    def download_emails(self):
        db = Database()
        cursor = db.cursor()

        for message in fetch_emails():
            num = db.get_next_email_num()
            self.save_email(email, num)
            db.insert_email()
            num += 1

        db.close()
