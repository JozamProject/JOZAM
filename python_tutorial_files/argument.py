import maths

def test(fct,expected,*args) :

    """
    fct -> fonction à tester
    expected -> résultat attendu pour le cas
    *args -> cas à tester dans fct
    """

    if fct(*args) == expected :
        return print("The function ",str(fct.__name__)," works in case ", str(args),".")
    return print("The function ",str(fct.__name__)," does not work in case ", str(args),". Expected ",str(expected)," but got",str(fct(*args)),".")

test(maths.addition,18,1,234,5321)
test(maths.addition,10,1,2,3,4)