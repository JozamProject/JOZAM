import os
from subprocess import call
from subprocess import check_output
import subprocess
import sys


#print("C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.ui")
#os.system("pyuic4 -o C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.py\" C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.ui")
#pid = subprocess.Popen(["pyuic4", "-o", "C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.py", "C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.ui"])
cmd = 'pyuic4 -o C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.py \
C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.ui'

#print(cmd)

#os.system(cmd)

#os.system('pyuic4 -o C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.py C:\\Users\\Jeff\\Documents\\workspace\\JOZAM\\qtDesigner_ui_concept\\jozam_ui_test.ui')
subprocess.check_output(["echo", "Hello World!"])
os.system('echo Are you ?')

pyuic4 -o C:\Users\Jeff\Documents\workspace\JOZAM\qtDesigner_ui_concept\project_ui_concept.py C:\Users\Jeff\Documents\workspace\JOZAM\qtDesigner_ui_concept\project_ui_concept.ui

import sys

class Ui_Form(QtGui.QWidget):
    def __init__(self):
        QtGui.QWidget.__init__(self)
        self.setupUi(self)
        
if __name__ == '__main__':
    app = QtGui.QApplication(sys.argv)
    ex = Ui_Form()
    ex.show()
    sys.exit(app.exec_())
    
