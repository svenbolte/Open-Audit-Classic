Dim objFSO
Dim objTextFile
Dim objFile
Dim colFiles
Dim objFolder
Dim strFolder
Dim strLogServer
Dim database
Dim sql
Dim mysql
On Error Resume Next

'Set File location
strLogServer = "f:\scans\"

'Set database server
mysql = "localhost"

'Open database connection
set database=createobject("adodb.connection")
conn="driver={mysql};server=" & mysql & ";database=open_inventory;Uid=root"
database.open conn
wscript.echo "Database Connection Established"

'Set output file location
Const FOR_READING = 1
strFolder = strLogServer

'Retreive directory listing for files to be entered      
Set objFSO = CreateObject("Scripting.FileSystemObject")
Set objFolder = objFSO.GetFolder(strFolder)
Set colFiles = objFolder.Files
For Each objFile In colFiles
  'wscript.echo "Processing LogFile: " & objFile.Path
  'Process each file
  Const ForReading = 1
  Set objFSO = CreateObject("Scripting.FileSystemObject")
  Set objTextFile = objFSO.OpenTextFile(objFile.Path, ForReading)
  Do While objTextFile.AtEndOfStream <> True
    'wscript.echo objTextFile.Readline
    sql = objTextFile.Readline
    database.execute sql
  Loop
  wscript.echo "Completed Logfile: " & objFile.Path
Next
