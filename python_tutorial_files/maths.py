"""
    Description : Quelques fonctions mathématiques utiles
"""

def addition(*args) :
    """Fait l'addition"""
    result = 0
    for i in args :
        result += i #equivalent à result = result + i
    return result

def tan (arg) :
    """Fonction tangente"""
    pass #A faire



def main () :
    """Fonction principale"""
    print(addition(1,2,3,4,5))


if __name__ == "__main__" :
    main ()


