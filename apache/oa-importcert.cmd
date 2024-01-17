@echo off
:: start elevated if not
net session >nul 2>&1 || (powershell -EP Bypass -NoP -C start "%~0" -verb runas &exit /b)  
:: here everything works elevated
powershell -EP Bypass -C "Import-Certificate -FilePath C:\Progra~2\xampplite\apache\conf\ssl.crt\openaudit.crt -CertStoreLocation Cert:\LocalMachine\Root"  

@ping -n 6 localhost> nul
rem pause