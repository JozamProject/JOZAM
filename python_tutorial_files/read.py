"""
    Description: Lit un nombre de lignes.
"""

import sys

def main ()  :
    if len (sys.argv) < 3 :
        print ("Warning, usage {0} filename".format (sys.argv[0]))
        sys.exit (-1)
    else :
        file_name = sys.argv[1]
        nb_lignes = sys.argv[2]
        file_handle = open (file_name, "r", encoding="utf-8")

        i=0
        for line in file_handle:
            if i%int(nb_lignes) != 0 or i==0 :
                i += 1
                print(line,end="")
            else :
                 if input("Go on ?") == "yes" :
                    i +=1
                    print(line,end="")
                 else :
                    break

        file_handle.close ()

if __name__ == "__main__" :
    main ()
