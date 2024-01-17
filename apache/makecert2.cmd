@echo off
REM IN YOUR SSL FOLDER, SAVE THIS FILE AS: makeCERT.bat
REM AT COMMAND LINE IN YOUR SSL FOLDER, RUN: makecert
REM IT WILL CREATE THESE FILES: example.cnf, example.crt, example.key
REM IMPORT THE .crt FILE INTO CHROME Trusted Root Certification Authorities
REM REMEMBER TO RESTART APACHE OR NGINX AFTER YOU CONFIGURE FOR THESE FILES
REM PLEASE UPDATE THE FOLLOWING VARIABLES FOR YOUR NEEDS.
rem set OPENSSL_CONF=./conf/openssl.cnf
set RANDFILE="C:/Program Files (x86)/xampplite/apache/bin/.rnd"
SET HOSTNAME=%COMPUTERNAME%
SET DOT=
SET COUNTRY=DE
SET STATE=Germany
SET CITY=Bergkamen
SET ORGANIZATION=IT
SET ORGANIZATION_UNIT=IT Department
SET EMAIL=webmaster@%HOSTNAME%.%DOT%

if not exist .\conf\ssl.crt mkdir .\conf\ssl.crt
if not exist .\conf\ssl.key mkdir .\conf\ssl.key


(
echo [req]
echo default_bits = 2048
echo prompt = no
echo default_md = sha256
echo x509_extensions = v3_req
echo distinguished_name = dn
echo:
echo [dn]
echo C = %COUNTRY%
echo ST = %STATE%
echo L = %CITY%
echo O = %ORGANIZATION%
echo OU = %ORGANIZATION_UNIT%
echo emailAddress = %EMAIL%
echo CN = %HOSTNAME%.%DOT%
echo:
echo [v3_req]
echo subjectAltName = @alt_names
echo:
echo [alt_names]
echo DNS.1 = *.%HOSTNAME%.%DOT%
echo DNS.2 = %HOSTNAME%.%DOT%
echo DNS.3 = localhost
echo DNS.4 = openaudit
echo DNS.4 = %HOSTNAME%
)>%HOSTNAME%.cnf

"C:/Program Files (x86)/xampplite/apache/bin/openssl.exe" req -new -x509 -newkey rsa:2048 -sha256 -nodes -keyout openaudit.key -days 730 -out openaudit.crt -config %HOSTNAME%.cnf

move /y openaudit.crt .\conf\ssl.crt
move /y openaudit.key .\conf\ssl.key

echo.
echo -----
echo Das Zertifikat wurde erstellt.
echo The certificate was provided.
echo.

@ping -n 6 localhost> nul
rem pause