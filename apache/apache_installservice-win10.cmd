@echo off

if "%OS%" == "Windows_NT" goto WinNT

:Win9X
echo Don't be stupid! Win9x don't know Services
echo Please use apache_start.bat instead
goto exit

:WinNT
echo Installing Apache2.4 as an Service
"C:\Program Files (x86)\xampplite\apache\bin\httpd" -k install
echo Now we Start Apache2.4 :)
net start Apache2.4

:exit
@ping -n 6 localhost> nul
rem pause
