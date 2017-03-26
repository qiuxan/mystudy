
import sys
import socket
import urllib2
import re
import MySQLdb
from BeautifulSoup import BeautifulSoup
type = sys.getfilesystemencoding()
reload(sys)
sys.setdefaultencoding('utf-8')
socket.setdefaulttimeout(600000000)
conn = MySQLdb.connect(db="ebay",user="root", passwd="root",use_unicode=1, charset='utf8')
sql="select id,pid,link,auction_page from category where pid=2"
cursor=conn.cursor()
cursor.execute(sql)
alldata=cursor.fetchall()
if alldata:
    for rec in alldata:
        pNo=int(rec[3])+1
        for pageNo in range(1,pNo):
                address="http://coins.shop.ebay.com"+rec[2]+"&rt=nc&LH_Auction=1&_ipg=50&_pgn="+str(pageNo)
                print address
                page=urllib2.urlopen(address)
                soup = BeautifulSoup(page)
                ss= soup.findAll("table", {"class":"li"})
                i=0
                for table in ss:
                   tc= table.contents[0]
                   xx=BeautifulSoup(''.join(str(tc)))
                   j=0
                   td_list=xx.findAll("td")
                   for td in td_list:
                       j=j+1
                       if j==1:
                           img=""
                           if td.img==None:
                                img=""
                           else:
                                img= MySQLdb.escape_string(td.img['src'])
                       elif j==2:
                           href= MySQLdb.escape_string(td.a['href'])
                           title= MySQLdb.escape_string(td.a.string)
                       elif j==3:
                           trs=""

                       elif j==4:
                           bids= MySQLdb.escape_string(td.contents[0])
                       elif j==5:
                           price=""
                           if len(td.contents)>1:
                               price= MySQLdb.escape_string(td.contents[0])
                       elif j==6:
                           time_left=""
                           if len(td.span.contents)>1:
                               time_left=MySQLdb.escape_string(td.span.contents[0])
		           insert_sql="insert into items(name,ebay_link,pic,trs,bids,price,time_left,cid,pid,type)values('"+title+"','"+href+"','"+img+"','"+trs+"','"+bids+"','"+price+"','"+time_left+"','"+str(rec[0])+"','"+str(rec[1])+"','1')"
                           print insert_sql
                           cursor.execute(insert_sql)
                           conn.commit()
                print i
cursor.close()
conn.close()
