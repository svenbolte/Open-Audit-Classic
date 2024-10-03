'WMIFileTypeSearch.vbs
'v1.1  April 2004, feb 2009 PBA
'Jeffery Hicks
'jhicks@jdhitsolutions.com    http://www.jdhitsolutions.com
'USAGE: cscript|wscript wmifiletypesearch.vbs
'DESCRIPTION:  Search for all instances of a specified file type using WMI
'and save output to a CSV file.  
'NOTES:  Script captures FileName, Size (in bytes), Created Date, Last Modified
' zusätzlich version und hersteller aus der PE-exe
'Date and Last Accessed Date.  Of course you can query for other properties as well.
'You could easily rewrite the script to take variables as parameters. You 
'could then use this as a computer startup script to do a little inventory.

'This script works on local drives as well as any network drives that have been mapped to a drive
'letter.

'***********************************************************************************
'* THIS PROGRAM IS OFFERED AS IS AND MAY BE FREELY MODIFIED OR ALTERED AS          *
'* NECESSARY TO MEET YOUR NEEDS.  THE AUTHOR MAKES NO GUARANTEES OR WARRANTIES,    *
'* EXPRESSED, IMPLIED OR OF ANY OTHER KIND TO THIS CODE OR ANY USER MODIFICATIONS. *
'* DO NOT USE IN A PRODUCTION ENVIRONMENT UNTIL YOU HAVE TESTED IN A SECURED LAB   *
'* ENVIRONMENT. USE AT YOUR OWN RISK.                                              *
'***********************************************************************************

On error Resume Next

Dim oWmi
Dim oRef
Dim fso,f

If Wscript.Arguments.Count > 0 Then
strComputer = wscript.arguments(0)
end if
If Wscript.Arguments.Count = 0 Then
strcomputer = "patnt1"
end if

strTitle="File Type Search"
strtype="exe"
'strType=InputBox("What type of file do you want to look for? Do NOT use a period.",strTitle,"vbs")
' If strType="" Then 
'  wscript.echo "Nothing entered or you cancelled"
'  wscript.quit
' End If 

strdrive= "c:"
'strDrive=InputBox("What local drive do you want to search?  Do NOT use a trailing ",strTitle,"c:")
' If strDrive="" Then
'  wscript.echo "Nothing entered or you cancelled"
'  wscript.quit
' End If 

'trim strDrive just in case the user added a 
strDrive=Left(strDrive,2)
stroutput= "scan-"&strcomputer&"-"& strType & "-query.csv"

'strOutput=InputBox("Enter full path and filename for the CSV file.  Existing files will " & _
'"be overwritten.",strTitle,"c:" & strType & "-query.csv")
' If strOutput="" Then
' wscript.echo "Nothing entered or you cancelled"
'  wscript.quit
' End If 

strQuery="Select Name,CreationDate,LastAccessed,LastModified," & _
"FileSize,Extension,Drive,Version, " & _
"Manufacturer FROM CIM_DATAFILE WHERE Extension='" & strType & _
 "' AND Drive='" & strDrive & "'"

Set fso=CreateObject("Scripting.FileSystemObject")
 If fso.FileExists(strOutput) Then fso.DeleteFile(strOutput)
Set f=fso.CreateTextFile(strOutput)
 If Err.Number Then
  wscript.echo "Could not create output file " & strOutput
  wscript.quit
 End If
 
Set oWmi=GetObject("winmgmts:\\" & strComputer & "\root\cimv2")
If Err.Number Then
  strErrMsg= "Error connecting to WINMGMTS" & vbCrlf
  strErrMsg= strErrMsg & "Error #" & err.number & " [0x" & CStr(Hex(Err.Number)) &"]" & vbCrlf
        If Err.Description = "" Then
            strErrMsg = strErrMsg & "Error description: " & Err.Description & "." & vbCrlf
        End If
  Err.Clear
  wscript.echo strErrMsg
  wscript.quit
End If

Set oRef=oWmi.ExecQuery(strQuery) 
If Err.Number Then
  strErrMsg= "Error connecting executing query!" & vbCrlf
  strErrMsg= strErrMsg & "Error #" & err.number & " [0x" & CStr(Hex(Err.Number)) &"]" & vbCrlf
        If Err.Description = "" Then
            strErrMsg = strErrMsg & "Error description: " & Err.Description & "." & vbCrlf
        End If
  Err.Clear
  wscript.echo strErrMsg
  wscript.quit
End If

wscript.echo "Working ...."
f.Writeline "FilePath,Size(bytes),Created,LastAccessed,LastModified, version, manufger"

For Each file In oRef
 f.Writeline file.Name & "," & file.FileSize & "," & ConvWMITime(file.CreationDate) & _
  "," & ConvWMITime(file.LastAccessed) & "," & ConvWMITime(file.LastModified) &_
  "," & "," & file.Version & "," & file.Manufacturer
Next

f.Close

wscript.echo "Finished.  See " & strOutput & " for results"

Set oWmi=Nothing
Set oRef=Nothing
Set fso=Nothing
Set f=Nothing

wscript.quit

'************************************************************************************
' Convert WMI Time Function
'************************************************************************************
Function ConvWMITime(wmiTime)
On Error Resume Next

yr = left(wmiTime,4)
mo = mid(wmiTime,5,2)
dy = mid(wmiTime,7,2)
tm = mid(wmiTime,9,6)

ConvWMITime = mo & "/" & dy & "/" & yr & " " & FormatDateTime(left(tm,2) & _
":" & Mid(tm,3,2) & ":" & Right(tm,2),3)

End Function

'EOF

