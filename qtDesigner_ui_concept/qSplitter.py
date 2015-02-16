


from PyQt4.Qt import QWidget, Qt
from PyQt4.QtGui import QWidget, QApplication, QTreeView, QListView, QTextEdit, \
                        QSplitter, QHBoxLayout, QVBoxLayout, QSizeGrip
import sys
class MainWindow(QWidget):
    def __init__(self):
        QWidget.__init__(self)
        
        editor1 = QTextEdit()
        editor2 = QTextEdit()
        editor3 = QTextEdit()
        
        split1 = QSplitter()
        split2 = QSplitter()
        
        layout = QVBoxLayout()
        
        container = QWidget()
        container_layout = QVBoxLayout()
        
        split1.addWidget(editor1)
        split1.addWidget(editor2)
        
        container_layout.addWidget(split1)
        container.setLayout(container_layout)
        
        split2.setOrientation(Qt.Vertical)
        split2.addWidget(container)
        split2.addWidget(editor3)
        
        layout.addWidget(split2)
        
        self.setLayout(layout)
        
        """treeView2 = QTreeView()
        listView2 = QListView()
        textEdit2 = QTextEdit()
        splitter2 = QSplitter(self)
        qSizeGrip2 = QSizeGrip(splitter2)

        splitter2.addWidget(treeView2)
        splitter2.addWidget(listView2)
        splitter2.addWidget(textEdit2)
        splitter2.addWidget(qSizeGrip2)

        layout = QVBoxLayout()
        layout.addWidget(splitter2)
        #self.setLayout(layout)

        
        listView = QListView()
        textEdit = QTextEdit()
        splitter = QSplitter(self)
        qSizeGrip = QSizeGrip(splitter)

        qWidget = QWidget()
        qWidget.setLayout(layout)
        
        splitter.addWidget(qWidget)
        splitter.addWidget(listView)
        splitter.addWidget(textEdit)
        splitter.addWidget(qSizeGrip)

        layout = QHBoxLayout()
        layout.addWidget(splitter)
        self.setLayout(layout)"""

if __name__ == '__main__':
    app = QApplication(sys.argv)
    mainWindow = MainWindow()
    mainWindow.show()
    sys.exit(app.exec_())