#!/usr/bin/python
#-*- encoding: utf-8 -*-

import sys

def main ()  :
    if len (sys.argv) < 2 :
        print ("Warning, usage {0} filename".format (sys.argv[0]))
        sys.exit (-1)
    else :
        file_name = sys.argv[1]
        file_handle = open (file_name, "r", encoding="utf-8")

        for line in file_handle :
            list = line.split (":")
            print(list)
            print("Nom : {1}, prénom : {0}, adresse mail : {2}".format(list[0],list[1],list[2]))
        file_handle.close ()

if __name__ == "__main__" :
    main ()