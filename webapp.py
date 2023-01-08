import requests
from bs4 import BeautifulSoup
import urllib.request
import time
import mysql.connector
from datetime import datetime


mydb = mysql.connector.connect (
    host = 'host',
    port = 5432,
    user = 'user',
    password = 'pass',
    database = 'db',
    auth_plugin='mysql_native_password'
)

customurl = "https://sportske.jutarnji.hr/sn/"
customurl2 = "https://sportske.jutarnji.hr"
imgpath = "C:/xampp/htdocs/web/images/"
counter = 0
category = 1
user = 1
mycursor = mydb.cursor()

session = requests.session()
url = session.get(customurl)
soup = BeautifulSoup(url.content, "html.parser")
#print (soup)
'''
file = open('primjerhtmla.txt', 'r', encoding="UTF-8")
ispis = file.read()
soup = BeautifulSoup(ispis, "html.parser")
#print (file)
file.close()
'''

#radi petlja = soup.find_all("article")
petlja = soup.find_all("article")

for petlja in petlja:
    sql = ""
    #iz prvog <a> dobijem URL i sliku
    aelement = petlja.find("a")
    #H3
    h3element = petlja.find("h3")
    #H3 <a> sadrzi href URL
    #class card__egida je kratki komentar
    #class card__title je veliki naslov
    h3aelement = h3element.find("a")
    #URL
    
    urlclanak = str(h3aelement)
    urlclanak = urlclanak.split('href=')[1].lstrip().split(' ')[0]
    urlclanak = urlclanak.split('"')[1].lstrip().split(' ')[0]

    if urlclanak == "https://online.jutarnji.hr":
        print("KRAAAAJ")
        continue
    urlclanak = customurl2 + urlclanak
    print (urlclanak)


    #Komentar
    try:
        komentar = h3aelement.find("span", class_="card__egida")
        komentar = komentar.text.strip()
    except:
        komentar = ""
    print (komentar)
    #Naslov
#    naslov = h3aelement.find("span", class_="card__title")
#    naslov = naslov.text.strip()
#    print (naslov)
    #slikamain
    slikamain = aelement.find("img")
    slikamain = str(slikamain)
    slikamain = slikamain.split('src=')[1].lstrip().split(' ')[0]
    slikamain = slikamain.split('"')[1].lstrip().split(' ')[0]
    
#kraj prvog dijela app
    sessionclanak = requests.session()
    urlcl = sessionclanak.get(urlclanak)
    soupclanak = BeautifulSoup(urlcl.content, "html.parser")
    #H1 naslov
    naslovclanak = soupclanak.find("h1", class_="item__title").text.strip()
    #Podnaslov
    podnaslovclanak = soupclanak.find("div", class_="item__subtitle").text.strip()
    #Autor
    autorclanak = soupclanak.find("span", class_="item__author-name").text.strip()
    #Vrijeme i datum clanka
    vrijemeclanak = soupclanak.find("span", class_="item__author__date").text.strip()
    #Text clanka
    textclanak = soupclanak.find("div", class_="itemFullText")
    ptextclanak = textclanak.find_all("p", recursive=False)
    finaltextclanak = ""

    for ptextclanak in ptextclanak:
        finaltextclanak = finaltextclanak + "\n\n" + ptextclanak.text.strip()    

    
    print (naslovclanak + "\n\n")
    print (podnaslovclanak + "\n\n")
#    print (autorclanak)
#    print (vrijemeclanak + "\n\n")
#    print (finaltextclanak)
    
    #slikax768
    slikax768 = soupclanak.find("source", media="(min-width: 768px)")
    slikax768 = str(slikax768)
    slikax768 = slikax768.split('srcset=')[1].lstrip().split(' ')[0]
    slikax768 = slikax768.split('"')[1].lstrip().split(' ')[0]

    #slikax480
    slikax480 = soupclanak.find("source", media="(min-width: 480px)")
    slikax480 = str(slikax480)
    slikax480 = slikax480.split('srcset=')[1].lstrip().split(' ')[0]
    slikax480 = slikax480.split('"')[1].lstrip().split(' ')[0]

    #slikax1
    slikax1 = soupclanak.find("source", media="(min-width: 1px)")
    slikax1 = str(slikax1)
    slikax1 = slikax1.split('srcset=')[1].lstrip().split(' ')[0]
    slikax1 = slikax1.split('"')[1].lstrip().split(' ')[0]

    timestr = time.strftime("%Y%m%d-%H%M%S")
    slikamainpath = timestr + str(counter) + "-main" + ".jpg"
    slikax768path = timestr + str(counter) + "-x768" + ".jpg"
    slikax480path = timestr + str(counter) + "-x480" + ".jpg"
    slikax1path = timestr + str(counter) + "-x1" + ".jpg"
    urllib.request.urlretrieve(slikamain, imgpath + slikamainpath)
    urllib.request.urlretrieve(slikax768, imgpath + slikax768path)
    urllib.request.urlretrieve(slikax480, imgpath + slikax480path)
    urllib.request.urlretrieve(slikax1, imgpath + slikax1path)
    
    print (slikax1)
#    print (slikax768)
#    print (slikax480)
#    print (slikax1)

    now = datetime.now()
    formatted_date = now.strftime('%Y-%m-%d %H:%M:%S')

    sql = "INSERT INTO posts (post_text, title, subtitle, comment, category_id, user_id, image, imagex768, imagex480, imagex1, archive, created_at, updated_at)"
    sql += " VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
    insertval = (finaltextclanak, naslovclanak, podnaslovclanak, komentar, category, 1, slikamainpath, slikax768path, slikax480path, slikax1path, "N", now, now)

    mycursor.execute(sql, insertval)

    mydb.commit()

    
    counter += 1
    print (counter)
    print ("___________________________________________________________")




    


