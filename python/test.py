import urllib2  
response = urllib2.urlopen('http://www.jikexueyuan.com/course')
html = response.read()  
print html  
