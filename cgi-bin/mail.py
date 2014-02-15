#! /usr/bin/env python
import cgi,cgitb
cgitb.enable()
# Required header that tells the browser how to render the text.
print "Content-Type: text/plain\n\n"



# Print a simple message to the display window. from email.MIMEText import MIMEText import smtplib,sys


The_Form = cgi.FieldStorage()


body='''blank'''


msg = MIMEText(body)


mfrom = "abhijit.manpur@gmail.com" 
to = "abhijit.manpur@gmail.com"
msg['From'] = mfrom 
msg['To'] = to 
msg['Subject'] = "TEST"


server = smtplib.SMTP("smtp.gmail.com",587) 
server.ehlo() 
server.starttls() 
server.ehlo() 
server.login("abhijit.manpur","dipannita") 
server.sendmail(mfrom,[to],msg.as_string()) 
server.quit 
print 'sent' 
