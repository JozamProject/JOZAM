"""
    Description : Affiche par bloc de n lignes
"""

import sys

def main () :
    """Fonction principale"""
    if len (sys.argv) < 3 :
        print ("Warning, usage : {0} filename number_of_lines".format (sys.argv[0]))
        sys.exit (-1)
    else :
        file_name = sys.argv[1]
        try :
            file_handle = None
            file_handle = open (file_name,  "r", encoding="utf-8")
            nb_lines = int (sys.argv[2])
            if nb_lines <= 0 :
                print ("Sorry the number of lines must be greater than 0")
                sys.exit (-1) #Sortie du programme

            line_count = 0
            while True :
                line = file_handle.readline ()
                if line :
                    print (line, end="")
                    line_count += 1
                    if line_count % nb_lines == 0 :
                        if input ("Continue (y)").lower() == "y" :
                            continue
                        else :
                            break
                else :
                    break


        except IOError :
            if not file_handle :
                print ("Sorry, couldn't open the file")
            else :
                print ("Sorry, error while reading the file")
        except ValueError :
            print ("Sorry, you must enter an integer")
        finally :
            if file_handle :
                print ("ok")
                file_handle.close ()
            else :
                print ("ko")


if __name__ == "__main__" :
    main()