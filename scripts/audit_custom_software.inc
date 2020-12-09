
wscript.echo "Customer specific audits (Chrome Enterprise Version etc.)"

' Example Reg-Keys: Add Chrome Version to the Software Table
 strKeyPath = "SOFTWARE\Wow6432Node\Google\Update\Clients\{8A69D345-D564-463c-AFF1-A69D9E530F96}"
 strValueName = "pv"
 display_name = "Google Chrome Business X64 (installed)"
 oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,Chrome_Version
 if xChrome_Version <> "" then
	 form_input = "software^^^" & display_name       & "^^^" _
                   & Chrome_Version       & "^^^" _
                   & ""                 & "^^^" _
                   & ""                 & "^^^" _
                   & OSInstall          & "^^^" _
                   & "Google, Inc.^^^^^^^^^" _
                   & "http://www.google.de/intl/de/chrome/business/browser/admin^^^" _
                   & "Google Chrome Eintrag = Version der installierten MSI Datei"    & "^^^" _
                   & ""                 & "^^^" _
                   & ""   & "^^^ "
	 entry form_input,comment,objTextFile,oAdd,oComment
	 form_input = ""
end if	 

' Example Reg-Keys: Add Windows >7 build to the Software Table
 strKeyPath = "SOFTWARE\Microsoft\Windows NT\CurrentVersion"
 strValueName = "BuildLabEx"
 str3ValueName = "UBR"
 str2ValueName = "ProductName"
 display_name = ""
 oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,windo_Version
 oReg.GetDWORDValue HKEY_LOCAL_MACHINE,strKeyPath,str3ValueName,win_subbuild
 oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,str2ValueName,display_name
 if windo_Version <> "" then
	 form_input = "software^^^Microsoft " & display_name       & " (version)^^^" _
                   & windo_Version & "/" & win_subbuild       & "^^^" _
                   & ""                 & "^^^" _
                   & ""                 & "^^^" _
                   & OSInstall          & "^^^" _
                   & "Microsoft^^^^^^^^^" _
                   & "http://www.microsoft.com/windows^^^" _
                   & "Lab Build/Subbuild zur installierten Windows Version"    & "^^^" _
                   & ""                 & "^^^" _
                   & ""   & "^^^ "
	 entry form_input,comment,objTextFile,oAdd,oComment
	 form_input = ""
end if	 


' Example Reg-Keys: Add McAffe Pattern-Version to the Software Register
' strKeyPath = "SOFTWARE\Network Associates\TVD\VirusScan Enterprise\CurrentVersion"
' strValueName = "szVirDefVer"
' display_name = "McAfee Virus-Definition-Version"
' oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,McAfeeVirDef_Version
' form_input = "software^^^" & display_name       & "^^^" _
'                   & McAfeeVirDef_Version       & "^^^" _
'                   & ""                 & "^^^" _
'                   & ""                 & "^^^" _
'                   & OSInstall          & "^^^" _
'                   & "McAfee^^^^^^^^^^^^^^^^^^" _
'                   & ""                 & "^^^" _
'                   & ""                 & "^^^" _
'                   & "http://www.mcafee.com"   & "^^^ "
' entry form_input,comment,objTextFile,oAdd,oComment
' form_input = ""

' Example NT-File-Versions: Add Opsi winst32.exe Version to the Software Register
' Set colFiles = objWMIService.ExecQuery("Select * from CIM_Datafile Where Name = 'C:\\opsi\\utils\\winst32.exe'",,48)
' For Each objFile in colFiles
'   form_input = "software^^^Opsi winst32.exe^^^" _
'                 & clean(objFile.Version)         & "^^^" _
'                 & ""                             & "^^^" _
'                 & ""                             & "^^^" _
'                 & OSInstall                      & "^^^" _
'                 & "Opsi.org^^^" _
'                 & ""                             & "^^^" _
'                 & ""                             & "^^^" _
'                 & "http://www.opsi.org" & "^^^ "
'   entry form_input,comment,objTextFile,oAdd,oComment
'   form_input = ""
' Next

