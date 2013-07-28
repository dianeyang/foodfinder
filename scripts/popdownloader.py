#################################################################
#
# popdownloader.py
#
#  * downloads all unread emails from an inbox
#    (any email can only be downloaded once through POP)
#
#  * saves them onto the server
#
#  * stores their paths in a database
#
#################################################################

# Borrowed code from http://stackoverflow.com/questions/1225586/checking-email-with-python

from filterfunctions import *

# Open database connection
db = MySQLdb.connect("mysql.hcs.harvard.edu","cs50-foodfinder","OpUwJlywmvty","cs50-foodfinder")

# prepare a cursor object using cursor() method
cursor = db.cursor()

# login info
pop_conn = poplib.POP3_SSL('pop.gmail.com')
pop_conn.user('harvardfoodfinder@gmail.com')
pop_conn.pass_('canadayf34')

# Get messages from server:
messages = [pop_conn.retr(i) for i in range(1, len(pop_conn.list()[1]) + 1)]

# Concatenate message pieces:
messages = ["\n".join(mssg[1]) for mssg in messages]

# Parse message into an email object:
messages = [parser.Parser().parsestr(mssg) for mssg in messages]

for message in messages:
    
    # retrieve next auto incremented id
    cursor.execute("INSERT INTO emails(id) VALUES(NULL)")
    cursor.execute("SELECT MAX(id) from emails")
    row = cursor.fetchone()
    num = row[0]
    
    # trim off Fwd:/Re: (or variants thereof) and line breaks from subject of email
    pattern = re.compile(r'(f|F)(w|W)(d|D)?: ')
    subject = re.sub(pattern,"", message['subject'])
    pattern = re. compile(r'(r|R)(e|E): ')
    subject = re.sub(pattern,"", subject)
    
    # remove newlines & escape special characters (they screw up database entries)
    pattern = re.compile(r'\n')
    subject = re.sub(pattern, "", subject)
    subject = re.escape(subject)
    
    # prepare the file paths
    plainpath = "/nfs/home/groups/cs50-foodfinder/web/plaintext_emails/plaintext_email"+str(num)+".txt"
    htmlpath = "/nfs/home/groups/cs50-foodfinder/web/html_emails/html_email"+str(num)+".html"
    
    img_count = 1
    plaintext_found = False
    
    # each part is a either non-multipart, or another multipart message
    # that contains further parts... Message is organized like a tree
    for part in message.walk():
        
        if not plaintext_found:
			# write the plaintext subject & body to the file
			if part.get_content_type() == 'text/plain':
				plain_file = open(plainpath,"w")
				plain_file.write(message['subject']+"\n")
				plain_file.write(part.get_payload())
				plain_file.close()
				plaintext_found = True
				continue
        
        # write the html subject & body to the file
        elif part.get_content_type() == 'text/html':            
            
            html_file = open(htmlpath,"w")
            html_file.write("<div id=\"email\">")
            messagetext = part.get_payload(decode=True)
            
            # remove Fwd: headers from top of email
            headerpattern = [re.compile(r'-----.*?To:.*?<br>(Cc:.*?<br>)?(Bcc:.*?<br>)?', re.DOTALL), re.compile(r'Sent from my iPhone', re.DOTALL), re.compile(r'-----.*?From:.*?<br>Date:.*?<br>To:.*?<br>(Cc:.*?<br>)?(Bcc:.*?<br>)?Subject:.*?<br>', re.DOTALL)]
            for header in headerpattern:
            	messagetext = re.sub(header, "", messagetext)
            newtext = "".join(messagetext.split("\n"))
            newpattern = re.compile(r'(.*?)(\-\-<\/div>.*br><\/div>)', re.DOTALL)
            match = re.match(newpattern, newtext)
            if match:
            	match2 = match.group(1)
            	newtext2 = re.sub(newpattern, match2, newtext)
            else:
            	newtext2 = messagetext
            html_file.write(newtext2)
            html_file.write("</div>")
            html_file.close()
            continue
        
        # download an inline jpg image, if any
        elif part.get_content_type() == 'image/jpeg':
         	save_image(db, part, ".jpg", htmlpath, num, img_count)
         	img_count = img_count + 1
         	continue
        
        # download an inline png image, if any
        elif part.get_content_type() == 'image/png':
         	save_image(db, part, ".png", htmlpath, num, img_count)
         	img_count = img_count + 1
         	continue
        
        # download an inline gif image, if any
        elif part.get_content_type() == 'image/gif':
         	save_image(db, part, ".gif", htmlpath, num, img_count)
         	img_count = img_count + 1
         	continue

	# prints info about what was downloaded to be displayed in cronjob email
	print num
	print plainpath
	print htmlpath
	print subject
    
    # Prepare SQL query to INSERT a record into the database.
    sql = "UPDATE emails SET plaintext_path='%s', html_path='%s', subject='%s' WHERE id='%d'" % (plainpath, htmlpath, subject, num)
    
    # add entry to database
    try:
        # Execute the SQL command
        cursor.execute(sql)
        # Commit your changes in the database
        db.commit()
        print "ADDED TO DATABASE"
    except Exception, e:
        print "FAILED TO ADD: " + str(e)
        # Rollback in case there is any error
        db.rollback()

    print "- - - - - - - - - - - - - - - - - - - - - - - - -"
    
    # update the counter
    num = num+1

# close the connection
pop_conn.quit()

# disconnect from server
db.close()