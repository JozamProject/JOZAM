import sys

from PyQt4 import QtCore, QtGui

from jozam_ui_backup import Ui_Form




try:
    _fromUtf8 = QtCore.QString.fromUtf8
except AttributeError:
    def _fromUtf8(s):
        return s

try:
    _encoding = QtGui.QApplication.UnicodeUTF8
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig, _encoding)
except AttributeError:
    def _translate(context, text, disambig):
        return QtGui.QApplication.translate(context, text, disambig)


class Main(QtGui.QMainWindow):
    def __init__(self):
        super(Main, self).__init__()

        # build ui
        self.ui = Ui_Form()
        self.ui.setupUi(self.ui)

        # self.ui.resize(self.ui.frame_3.width(), self.ui.frame_3.height())
        # self.ui.resize(self.ui.width(), self.ui.height())
        
        # self.resize(self.ui.frameSize())
        
        
        # self.setCentralWidget(self.ui)
        # self.ui.setFixedSize(500,500)
        # self.adjustSize()

        # connect signals
        #self.hide_and_show(self.ui.pushButton_31, self.ui.frame)
        #self.hide_and_show(self.ui.pushButton_81, self.ui.plainTextEdit_12)
        #self.hide_and_show(self.ui.pushButton_85, self.ui.plainTextEdit_11)
        
    def hide_and_show(self, pushButton, receiver):
        # lambda: receiver.hide() if receiver.isVisible() else receiver.show()
        
        def lambda_hide_and_show():
            if receiver.isVisible():
                receiver.hide()
                pushButton.setText("►" + pushButton.text()[1:])
            else:
                receiver.show()
                pushButton.setText("▼" + pushButton.text()[1:])
            
            parent = receiver.parentWidget()
            while(parent):
                print(parent.objectName() + " : " + parent.layout().objectName())
                # parent.adjustSize()
                parent = parent.parentWidget()
                
        pushButton.clicked.connect(lambda_hide_and_show)
        # text = "►" + pushButton.text()[1:] if receiver.isVisible() else "▼" + pushButton.text()[1:]
        # print(text)
        # pushButton.setText(text)

if __name__ == '__main__':
    app = QtGui.QApplication(sys.argv)
    main = Main()
    main.show()
    sys.exit(app.exec_())
