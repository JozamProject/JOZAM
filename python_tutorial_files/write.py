import sys

def main () :
    """Fonction principale"""

    source_file_name = sys.argv[1]
    destination_file_name = sys.argv[2]

    source_file = open (source_file_name, "r", encoding="utf-8")
    destination_file = open (destination_file_name, "w", encoding="utf-8")

    for line in source_file :
        destination_file.write (line)

    destination_file.close () #Fermeture du fichier
    source_file.close () #Fermeture du fichier

if __name__ == "__main__" :
    main()