import gmail
from config import email_address, email_password

def fetch_emails():
    account = gmail.login(email_address, email_password)
    emails =  account.inbox().mail(unread=True)
    for email in emails:
        email.read()
        email.fetch()
        yield email.html