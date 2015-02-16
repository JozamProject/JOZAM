import sys

from PyQt4 import QtCore, QtGui
from PyQt4 import QtCore, QtGui
from PyQt4.Qt import QWidget, Qt, QGraphicsItem, QGraphicsEllipseItem, QGraphicsRectItem,\
    QPushButton, QGraphicsProxyWidget, QFrame
from PyQt4.QtGui import QWidget, QApplication, QTreeView, QListView, QTextEdit, \
                        QSplitter, QHBoxLayout, QVBoxLayout, QSizeGrip, QGraphicsView, QGraphicsScene, QLabel


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

class Ui_Form(QGraphicsView):
    def __init__(self):
        QtGui.QWidget.__init__(self)
        self.setupUi(self)
    def setupUi(self, Form):
        Form.setObjectName(_fromUtf8("Form"))
        Form.setWindowModality(QtCore.Qt.NonModal)
        Form.resize(833, 534)
        
        scene = QGraphicsScene(self)
        
        ellipse = QGraphicsEllipseItem(10, 10, 100, 100)
        ellipse.setFlag(QGraphicsItem.ItemIsMovable)
        
        
        
        
        
        
        
        
        
        #proxy = QGraphicsProxyWidget()
        #proxy.setWidget(self.project)
        #proxy.setFlag(QGraphicsItem.ItemIsMovable)
        
        
        
        self.editor = QTextEdit()
        self.editor.setWindowFlags(Qt.SubWindow)
        
        grip2 = QSizeGrip(self.editor)
        
        layout2 = QVBoxLayout()
        layout2.addWidget(grip2)
        layout2.setAlignment(grip2, Qt.AlignBottom | Qt.AlignRight)
        
        self.editor.setLayout(layout2)
        
        
        
        proxy2 = QGraphicsProxyWidget()
        proxy2.setWidget(self.editor)
        proxy2.setFlag(QGraphicsItem.ItemIsMovable)
        
       
        
        scene.addItem(ellipse)
        #scene.addItem(proxy)
        scene.addItem(proxy2)
        
        #self.setScene(scene)
        
        
        
        
       #self.movable = QGraphicsView(self)
       #self.movable.setScene(QGraphicsScene())
        
        
        
        # self.editor.setFlag(QtGui.QGraphicsItem.ItemIsMovable)
        
        
        
        
        
        
        

        
        # self.project.setFlag(QtGui.QGraphicsItem.ItemIsMovable)
        
        #self.project.setWindowFlags(Qt.SubWindow)
        
        
        
        # self.addWidget(grip)
        
        # self.scrollArea_5.setWindowFlags(Qt.SubWindow)
        # qSizeGrip = QSizeGrip(self.scrollArea_5)
        # self.verticalLayout_5.addWidget(qSizeGrip)
        
        
        self.project = QtGui.QFrame(self)
        self.project.setWindowFlags(Qt.SubWindow)
        
        self.project.setEnabled(True)
        self.project.setGeometry(QtCore.QRect(90, 50, 371, 281))
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Preferred, QtGui.QSizePolicy.Preferred)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(self.project.sizePolicy().hasHeightForWidth())
        self.project.setSizePolicy(sizePolicy)
        self.project.setMinimumSize(QtCore.QSize(200, 40))
        self.project.setContextMenuPolicy(QtCore.Qt.NoContextMenu)
        self.project.setAcceptDrops(True)
        self.project.setFrameShape(QtGui.QFrame.Box)
        self.project.setFrameShadow(QtGui.QFrame.Sunken)
        self.project.setObjectName(_fromUtf8("project"))
        
        
        self.verticalLayout_4 = QtGui.QVBoxLayout(self.project)
        self.verticalLayout_4.setSizeConstraint(QtGui.QLayout.SetNoConstraint)
        self.verticalLayout_4.setObjectName(_fromUtf8("verticalLayout_4"))
        
        
        self.project_verticalLayout = QtGui.QVBoxLayout(self.project)
        
        
        self.project_bar = QtGui.QWidget(self.project)
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Preferred, QtGui.QSizePolicy.Preferred)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(self.project_bar.sizePolicy().hasHeightForWidth())
        self.project_bar.setSizePolicy(sizePolicy)
        self.project_bar.setMinimumSize(QtCore.QSize(0, 21))
        self.project_bar.setMaximumSize(QtCore.QSize(16777215, 21))
        self.project_bar.setObjectName(_fromUtf8("project_bar"))
        self.horizontalLayout_17 = QtGui.QHBoxLayout(self.project_bar)
        self.horizontalLayout_17.setSpacing(6)
        self.horizontalLayout_17.setMargin(0)
        self.horizontalLayout_17.setObjectName(_fromUtf8("horizontalLayout_17"))
        self.collapseProject_button = QtGui.QPushButton(self.project_bar)
        self.collapseProject_button.setObjectName(_fromUtf8("collapseProject_button"))
        self.horizontalLayout_17.addWidget(self.collapseProject_button)
        self.project_bar_line = QtGui.QFrame(self.project_bar)
        self.project_bar_line.setFrameShape(QtGui.QFrame.VLine)
        self.project_bar_line.setFrameShadow(QtGui.QFrame.Sunken)
        self.project_bar_line.setObjectName(_fromUtf8("project_bar_line"))
        self.horizontalLayout_17.addWidget(self.project_bar_line)
        self.project_bar_buttons = QtGui.QFrame(self.project_bar)
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Preferred, QtGui.QSizePolicy.Preferred)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(self.project_bar_buttons.sizePolicy().hasHeightForWidth())
        self.project_bar_buttons.setSizePolicy(sizePolicy)
        self.project_bar_buttons.setMaximumSize(QtCore.QSize(100, 16777215))
        self.project_bar_buttons.setFrameShape(QtGui.QFrame.StyledPanel)
        self.project_bar_buttons.setFrameShadow(QtGui.QFrame.Raised)
        self.project_bar_buttons.setObjectName(_fromUtf8("project_bar_buttons"))
        self.horizontalLayout_18 = QtGui.QHBoxLayout(self.project_bar_buttons)
        self.horizontalLayout_18.setSpacing(0)
        self.horizontalLayout_18.setMargin(0)
        self.horizontalLayout_18.setObjectName(_fromUtf8("horizontalLayout_18"))
        self.addProject_button = QtGui.QPushButton(self.project_bar_buttons)
        self.addProject_button.setObjectName(_fromUtf8("addProject_button"))
        self.horizontalLayout_18.addWidget(self.addProject_button)
        self.addTask_button = QtGui.QPushButton(self.project_bar_buttons)
        self.addTask_button.setObjectName(_fromUtf8("addTask_button"))
        self.horizontalLayout_18.addWidget(self.addTask_button)
        self.closeProject_button = QtGui.QPushButton(self.project_bar_buttons)
        self.closeProject_button.setObjectName(_fromUtf8("closeProject_button"))
        self.horizontalLayout_18.addWidget(self.closeProject_button)
        self.horizontalLayout_17.addWidget(self.project_bar_buttons)
        self.project_verticalLayout.addWidget(self.project_bar)
        
        self.project_content = QtGui.QWidget(self.project)
        self.project_content.setObjectName(_fromUtf8("project_content"))
        
        self.verticalLayout_7 = QtGui.QVBoxLayout(self.project_content)
        self.verticalLayout_7.setMargin(0)
        self.verticalLayout_7.setObjectName(_fromUtf8("verticalLayout_7"))
        
        self.verticalLayout = QtGui.QVBoxLayout(self.project_content)
        self.verticalLayout.setContentsMargins(-1, 0, -1, -1)
        self.verticalLayout.setObjectName(_fromUtf8("verticalLayout"))
        
        self.project_tasks = QtGui.QWidget(self.project_content)
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Preferred, QtGui.QSizePolicy.Preferred)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(self.project_tasks.sizePolicy().hasHeightForWidth())
        self.project_tasks.setSizePolicy(sizePolicy)
        self.project_tasks.setObjectName(_fromUtf8("project_tasks"))
        self.verticalLayout_2 = QtGui.QVBoxLayout(self.project_tasks)
        self.verticalLayout_2.setSpacing(6)
        self.verticalLayout_2.setSizeConstraint(QtGui.QLayout.SetNoConstraint)
        self.verticalLayout_2.setMargin(0)
        self.verticalLayout_2.setObjectName(_fromUtf8("verticalLayout_2"))
        self.verticalLayout.addWidget(self.project_tasks)
        self.project_subProjects = QtGui.QWidget(self.project_content)
        sizePolicy = QtGui.QSizePolicy(QtGui.QSizePolicy.Preferred, QtGui.QSizePolicy.Preferred)
        sizePolicy.setHorizontalStretch(0)
        sizePolicy.setVerticalStretch(0)
        sizePolicy.setHeightForWidth(self.project_subProjects.sizePolicy().hasHeightForWidth())
        self.project_subProjects.setSizePolicy(sizePolicy)
        self.project_subProjects.setObjectName(_fromUtf8("project_subProjects"))
        self.verticalLayout_6 = QtGui.QVBoxLayout(self.project_subProjects)
        self.verticalLayout_6.setSpacing(6)
        self.verticalLayout_6.setSizeConstraint(QtGui.QLayout.SetNoConstraint)
        self.verticalLayout_6.setMargin(0)
        self.verticalLayout_6.setObjectName(_fromUtf8("verticalLayout_6"))
        self.verticalLayout.addWidget(self.project_subProjects)
        self.verticalLayout_7.addLayout(self.verticalLayout)
        self.project_verticalLayout.addWidget(self.project_content)
        self.verticalLayout_4.addLayout(self.project_verticalLayout)

        self.retranslateUi(Form)
        QtCore.QMetaObject.connectSlotsByName(Form)
        
        
        
        grip = QSizeGrip(self.project)
        lay = self.project_verticalLayout
        lay.addWidget(grip)
        lay.setAlignment(grip, Qt.AlignBottom | Qt.AlignRight)

    def retranslateUi(self, Form):
        Form.setWindowTitle(_translate("Form", "Form", None))
        self.collapseProject_button.setText(_translate("Form", "▼ Project 0", None))
        self.addProject_button.setText(_translate("Form", "+P", None))
        self.addTask_button.setText(_translate("Form", "+T", None))
        self.closeProject_button.setText(_translate("Form", "x", None))

if __name__ == '__main__':
    app = QtGui.QApplication(sys.argv)
    ex = Ui_Form()
    ex.show()
    sys.exit(app.exec_())
