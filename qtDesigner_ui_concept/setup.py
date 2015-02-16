import sys
from cx_Freeze import setup, Executable

setup(
    name = "Jozam",
    version = "0",
    description = "Task Manager",
    executables = [Executable("qSplitter.py")])