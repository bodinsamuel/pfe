import urllib
import urllib2
from bs4 import BeautifulSoup
import lxml

url = 'http://ws.seloger.com/search.xml'
values = {
    'tri' : 'd_px',
    'idtt' : '1',
    'idtypebien' : '1',
    'ci': '750115',
    'pxmin': '800',
    'pxmax': '2500'
}

data = urllib.urlencode(values)
response = urllib2.urlopen(url + '?' + data)
xml = response.read()

root = BeautifulSoup(xml, 'xml')
print root.recherche.find('nbTrouvees')
