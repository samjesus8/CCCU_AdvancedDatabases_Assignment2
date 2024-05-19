import pandas as pd
from pymongo import MongoClient
import json

# Connect to MongoDB
client = MongoClient('mongodb://localhost:27017/')
db = client['Movies']

collectionMovie = db['Movie']
collectionCountry = db.Movie['Country']
collectionArtist = db.Movie['Artist']
collectionRole = db.Movie['Role']
collectionInternetUser = db['InternetUser']
collectionScoreMovie = db['Score_movie'] 

# Specify the path to your Excel file
excel_file = 'Movies Data.xlsx'

# Specify the names of the sheets you want to read
sheets_to_read = ['Movie Table', 'Country Table', 'Artist Table', 'Role Table', 'Internet_user Table', 'Score_movie Table']

# Create a dictionary to store DataFrames for each sheet
dfs = {}

# Read the specified sheets into DataFrames
for sheet_name in sheets_to_read:
    dfs[sheet_name] = pd.read_excel(excel_file, sheet_name=sheet_name)

# Now, dfs dictionary contains DataFrames for each selected sheet
# You can access the DataFrames using their sheet names as keys
# For example:
print(dfs['Movie Table'])  # Print DataFrame

# Convert DataFrame to JSON
movieJson = json.loads(dfs['Movie Table'].to_json(orient='records'))
countryJson = json.loads(dfs['Country Table'].to_json(orient='records'))
artistJson = json.loads(dfs['Artist Table'].to_json(orient='records'))
roleJson = json.loads(dfs['Role Table'].to_json(orient='records'))
internetUserJson = json.loads(dfs['Internet_user Table'].to_json(orient='records'))
scoreMovieJson = json.loads(dfs['Score_movie Table'].to_json(orient='records'))

# Insert data into MongoDB
collectionMovie.insert_many(movieJson)
collectionCountry.insert_many(countryJson)
collectionArtist.insert_many(artistJson)
collectionRole.insert_many(roleJson)
collectionInternetUser.insert_many(internetUserJson)
collectionScoreMovie.insert_many(scoreMovieJson)

print("Data inserted successfully.")














