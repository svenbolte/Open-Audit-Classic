@echo off 

if "%OS%" == "Windows_NT" goto WinNT 

:Win9X 
echo Don't be stupid! Win9x don't know Services 
echo Please use mysql_start.bat instead 
goto exit 

:WinNT 
if exist %windir%\my.ini GOTO CopyINI 
if exist c:\my.cnf GOTO CopyCNF 
if not exist %windir%\my.ini GOTO MainNT 
if not exist c:\my.cnf GOTO MainNT 

:CopyINI 
echo Safe the %windir%\my.ini as %windir%\my.ini.old! 
copy %windir%\my.ini /-y %windir%\my.ini.old 
del %windir%\my.ini 
GOTO WinNT 

:CopyCNF 
echo Safe the c:\my.cnf as c:\my.cnf.old! 
copy c:\my.cnf /-y c:\my.cnf.old 
del c:\my.cnf 
GOTO WinNT 

:MainNT 
echo Installing MySQL as an Service 
rem copy "C:\Program Files (x86)\xampplite\mysql\bin\my.cnf" /-y %windir%\my.ini
"C:\Program Files (x86)\xampplite\mysql\bin\mysqld" --install mysql --defaults-file="C:\Program Files (x86)\xampplite\mysql\bin\my.ini"
echo Try to start the MySQL deamon as service ... 
net start MySQL 

:exit 
@ping -n 6 localhost> nul
rem pause
