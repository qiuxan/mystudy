#-*—coding:utf8-*-
from lxml import etree
import requests
import re
#下面三行是编码转换的功能，大家现在不用关心。
import sys
reload(sys)
sys.setdefaultencoding("utf-8")










product_info=[]
url='https://www.spotlightstores.com/bed/bedding/c/bedding'
head = {'User-Agent':'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36'}
# html = requests.get('http://jp.tingroom.com/yuedu/yd300p/')
html = requests.get(url,headers = head)
info = {}
selector = etree.HTML(html.text)

#info['img_url']=selector.xpath('//*[@id="productdetailimg"]/@src')

info['item_name']=selector.xpath('//*[@class="gtm-click"]/text()')

for each in info['item_name']:
    #each='https://www.spotlightstores.com'+each
    print each
