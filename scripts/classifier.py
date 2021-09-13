import sys
import json

from firebase.firebase import FirebaseApplication
from firebase import firebase

import pyrebase
import math

config = {
  "apiKey": "AIzaSyCuPZ_q0Jzx6n5io06HUits9eq37l5KK_0",
  "authDomain": "asalGayan.firebaseapp.com",
  "databaseURL": "https://asalgayan-e932b-default-rtdb.firebaseio.com/",
  "storageBucket": "asalGayan.appspot.com"
}

firebase = pyrebase.initialize_app(config)

# text =input('Enter Search item:')
text = sys.argv[1]
text = text.lower()
tax =text

# initializing sum dictionary
sum_dict = {"a":1, "b":2,"c":3,"d":4,"e":5,"f":6,"g":7,"h":8,"i":9,"j":10,"k":11,"l":12,"m":13,"n":14,"o":15,"p":16,"q":17,"r":18,"s":19,"t":20,"u":21,"v":22,"w":23,"x":24,"y":25,"z":26," ":0.5,"-":0.5}
  
# refering dict for sum 
# iteration using loop
res = 0
for ele in text:
    res += sum_dict[ele]
text =res

tag1=[]
min_li = []

#getting data from database
db = firebase.database()

for x in range(0, 10):
  
  tags = db.child(x).child('sub tags').get()
  tags = tags.val().lower().split(',')

  li = []
  for i in tags:
    li.append(i)
  tag1 = li

  dres = 0
  for w in range(len(tags)):
    for elem in tags[w]:
       dres += sum_dict[elem]
    
    tags[w] =dres
    dres=0

  ##KNN code using eculidean distance formulay
  # if res = [itm for itm in tag1 if(ele in tax)]:
  if ' '+tax in tag1:
    tags[:] = [math.sqrt((tags - text)**2) for tags in tags]
    min_val = min(tags)
  else:
    tags=tags[:0]+[99]*len(tags)
    min_val = min(tags)

  min_li.append(min_val)

# To display Results and i selected value of K=3
if min(min_li)<50:
  results = [];
  for m in range(0,3):
    ind = min_li.index(min(min_li))
    min_li[ind] = 250
    company_id = db.child(ind).get()
    company_name = db.child(ind).child('Company Name').get()
    company_ai_category = db.child(ind).child('AI Category type').get()
    company_business_type = db.child(ind).child('Business type').get()
    company_location = db.child(ind).child('Base Location').get()
    company_logo = db.child(ind).child('Logo Image').get()
    company_tags = db.child(ind).child('sub tags').get()

    results.append({
      'id': company_id.key(),
      'name': company_name.val(),
      'ai_category': company_ai_category.val(),
      'business_type': company_business_type.val(),
      'location': company_location.val(),
      'image': company_logo.val(),
      'tags': company_tags.val(),
    })

  jsonDump = json.dumps(results);
  print(jsonDump)

else: print('no_results')