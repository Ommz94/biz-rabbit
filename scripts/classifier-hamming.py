import sys
import json

from firebase.firebase import FirebaseApplication
from firebase import firebase

import pyrebase
import math
import textdistance

from collections import OrderedDict

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

text = ''
try:
  text = sys.argv[1].lower()
except Exception:
  text = ''

tax = text

min_similarity = 10

# --------------------------------------------
# Utils
# --------------------------------------------
def sortBySimilarity(el):
  return el[1]

# --------------------------------------------
# Filters
# --------------------------------------------

try:
  filters = json.loads(sys.argv[2])
except Exception as e:
  filters = []

# Get filters
try:
  AICategory = filters['ai_category'].strip()
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

matchedCompanies = {}

db = firebase.database()
allCompanies = db.get().val().items()

tagSimilarity = []

try:
  # Find similarity
  for id, company in allCompanies:

    if AICategory != False and company['AI Category type'].strip() != AICategory:
      continue
    
    if areaServed != False and company['Area Served'].strip() != areaServed:
      continue

    if businessType != False and company['Business type'].strip() != businessType:
      continue
    
    if len(text) > 0:
      tags = company['sub tags'].lower().split(',');

      single_similarity = 0;
      for tag in tags:
        single_similarity += textdistance.hamming.similarity(text, tag.strip())
        
      if single_similarity >= min_similarity:
        tagSimilarity.append((id, single_similarity))

    company['id'] = id
    matchedCompanies[id] = company


  # Rank
  results = [];
  if len(text) == 0:

    for i, company in matchedCompanies.items():

      results.append({
        'id': company['id'],
        'name': company['Company Name'],
        'ai_category': company['AI Category type'],
        'business_type': company['Business type'],
        'location': company['Base Location'],
        'image': company['Logo Image'],
        'tags': company['sub tags'],
      })

  elif len(tagSimilarity) > 0:

    tagSimilarity.sort(key=sortBySimilarity, reverse=True)

    for tagged in tagSimilarity:
      company = matchedCompanies[tagged[0]]

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

except Exception:
  print('no_results')

