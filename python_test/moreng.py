import sys

def main () :
    """Fonction principale"""
    if len (sys.argv) < 2 :
        print ("Warning : usage {0} filename".format (sys.argv[0]))
        exit (-1) #Sortie avec message d'erreur

    file_name = sys.argv[1]
    file_handle = open (file_name, "r", encoding="utf-8")
    lines_nb = 0
    for line in file_handle :
        lines_nb += 1
    print ("nombres de lignes dans le fichier {1} : {0}".format (lines_nb, file_name))
    file_handle.close () #Fermeture du fichier



if __name__ == "__main__" :
    main()


