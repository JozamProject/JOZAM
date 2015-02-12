"""
    Description : utilitaire de copie de fichiers (mode binaire)
"""
import sys
from datetime import datetime
def main():
    if len (sys.argv) < 3 :
        print ("Warning, usage : {0} source_file destination_file".format (sys.argv[0]))
        exit (-1)
    src_name = sys.argv[1] #Nom du fichier source
    dest_name = sys.argv[2] #Nom du fichier destination
    start_time = datetime.now ()
    src_file = open (src_name, "rb")
    dest_file = open (dest_name, "wb")
    #Lecture du fichier source et copie dans le fichier de destination
    while True :
        data  = src_file.read (1024*1024)
        if not data :
            break
        else :
            dest_file.write (data)
    #Fermeture des fichiers
    src_file.close ()
    dest_file.close ()
    end_time = datetime.now ()
    delta = end_time - start_time
    print ("Your file has been copied in {0} seconds".format (delta.total_seconds()))



if __name__ == '__main__':
    main()
