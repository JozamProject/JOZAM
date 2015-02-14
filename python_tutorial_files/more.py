import sys

def main () :
    """Fonction principale"""
    
    file_name = sys.argv[1]
    file_handle = open (file_name, "r", encoding="utf-8")

    lines_nb = 0
    text = ""
    #Lecture du fichier ligne / ligne
    for line in file_handle :
        lines_nb += 1
        text += str(lines_nb)+" "+line
    print(text,end="")
    file_handle.close () #Fermeture du fichier

if __name__ == "__main__" :
    main()


