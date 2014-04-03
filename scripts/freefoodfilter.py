from filterfunctions import *
import MySQLdb
import os
import math
from config import threshold
from database import Database

class Classify():
    def __init__():
        self.data_array = csv_to_array("/nfs/home/groups/cs50-foodfinder/web/scripts/dataset.csv", "rb")
        self.totalmail = int(self.data_array[0][0])
        self.totalFF = int(self.data_array[0][1])
        self.totalNFF = int(self.data_array[0][2])

    def get_words(self):
        email = open(path, "r")
        text = email.read()
        return str_to_array(text)

    def get_prob(self, FFproduct, NFFproduct):
        FFproduct = FFproduct * (float(self.totalFF + .0001)/self.totalmail)
        NFFproduct = NFFproduct * (float(self.totalNFF + .0001)/self.totalmail)
        return FFproduct / (FFproduct + NFFproduct)

    def classify_email(self, path, conn): 
        total = 0
        FFproduct = 1
        NFFproduct = 1
        
        words = self.get_words(path)

        for word in words:
            for row in data_array:
                if word in row:
                    word_found = True
                    FFproduct = FFproduct * float(datarow[3])
                    NFFproduct = NFFproduct * float(datarow[4])
            
        combined_prob = self.get_prob(FFproduct, NFFproduct)
        
        if combined_prob >= threshold: # yayyy
            conn.mark_free_food(emailrow[0])

    def classify_emails(self):
        db = Database()
        emails = db.get_unprocessed_emails()
        for email in emails:
            path = email[0]
            self.classify_email(path, db)

