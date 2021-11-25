import sys
import json

from firebase.firebase import FirebaseApplication
from firebase import firebase

import pyrebase
import math

# --------------------------------------------
# Init
# --------------------------------------------

config = {
  "apiKey": "AIzaSyCuPZ_q0Jzx6n5io06HUits9eq37l5KK_0",
  "authDomain": "asalGayan.firebaseapp.com",
  "databaseURL": "https://asalgayan-e932b-default-rtdb.firebaseio.com/",
  "storageBucket": "asalGayan.appspot.com"
}

firebase = pyrebase.initialize_app(config)

text = sys.argv[1].lower()
tax = text

# --------------------------------------------
# Filters
# --------------------------------------------

try:
  filters = json.loads(sys.argv[2])
except Exception:
  filters = []

# Get filters
try:
  AICategory = filters['ai_category'].strip().lower()
except Exception:
  AICategory = False

try:
  areaServed = filters['area_served'].strip()
except Exception:
  areaServed = False

try:
  businessType = filters['business_type'].strip()
except Exception:
  businessType = False

try:
  fundingAmount = filters['funding_amount'].strip()
except Exception:
  fundingAmount = False

try:
  employeeCount = filters['employee_count'].strip()
except Exception:
  employeeCount = False

# --------------------------------------------
# Start
# --------------------------------------------

# initializing sum dictionary
sum_dict = {"a":1, "b":2,"c":3,"d":4,"e":5,"f":6,"g":7,"h":8,"i":9,"j":10,"k":11,"l":12,"m":13,"n":14,"o":15,"p":16,"q":17,"r":18,"s":19,"t":20,"u":21,"v":22,"w":23,"x":24,"y":25,"z":26," ":0.5,"-":0.5,"(":0.5,")":0.5,";":0.5}
  
# refering dict for sum 
# iteration using loop
res = 0
for ele in text:
    res += sum_dict[ele]
text =res

tag1=[]
min_li = []
finalCompanies = []

db = firebase.database()
allCompanies = db.get().val().items()

for id, com in allCompanies:

  if AICategory != False and com['AI Category type'].strip().lower() != AICategory.lower():
    continue
  
  if areaServed != False and com['Area Served'].strip() != areaServed:
    continue

  if businessType != False and com['Business type'].strip() != businessType:
    continue
  
  tags = com['sub tags'].lower().split(',')

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

  ##Euclidean code using eculidean distance formulay
  # if res = [itm for itm in tag1 if(ele in tax)]:
  if ' '+tax in tag1:
    tags[:] = [math.sqrt((tags - text)**2) for tags in tags]
    min_val = min(tags)
  else:
    tags=tags[:0]+[99]*len(tags)
    min_val = min(tags)
  
  # Hot fix to remove non-related companies
  if min_val < 99:
    min_li.append(min_val)
    com['id'] = id
    finalCompanies.append(com)


# To display Results and i selected value of K=3
try:
  if min(min_li)<50:
    results = [];
    for m in range(0,len(finalCompanies)):
      ind = min_li.index(min(min_li))
      min_li[ind] = 250

      company = finalCompanies[ind]

      results.append({
        'id': company['id'],
        'name': company['Company Name'],
        'ai_category': company['AI Category type'],
        'business_type': company['Business type'],
        'location': company['Base Location'],
        'image': company['Logo Image'],
        'tags': company['sub tags'],
      })

    if len(results) > 0:
      jsonDump = json.dumps(results);
      print(jsonDump)
    else:
      print('no_results')

  else: 
    print('no_results')

except Exception:
  print('no_results')