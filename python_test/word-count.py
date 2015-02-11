"""
    Description: Compte le nombre de mots dans un fichier texte
"""

import sys

def main ()  :
    if len (sys.argv) < 2 :
        print ("Warning, usage {0} filename".format (sys.argv[0]))
        sys.exit (-1)
    else :
        file_name = sys.argv[1]
        file_handle = open (file_name, "r", encoding="utf-8")
        word_count = 0
        for line in file_handle :
            line = line.replace ("'", " ")
            word_count += len (line.split (" "))
        file_handle.close ()
        print ("Your", "text", "has", "{0}".format (word_count), "words", sep=" ", end="") 
        print(".")

if __name__ == "__main__" :
    main ()
