strComputer = "10.10.10.1" 
Set objWMIService = GetObject("winmgmts:" _
    & "{impersonationLevel=impersonate}!\\" _
    & strComputer & "\root\cimv2")
Set colProcesses = objWMIService.ExecQuery( _
    "select * from win32_process" )
wscript.echo "Prozessid;Prozessname;Domain;User;loginID;logintime"
For Each objProcess in colProcesses
    ProcessId = objProcess.ProcessId

    If objProcess.GetOwner ( User, Domain ) = 0 Then
          ausgabe = ProcessId & ";" &_
              objProcess.Caption & _
              ";" & Domain & ";" &_
              User & ";"
    End If

    Set colLogonSessions = objWMIService.ExecQuery _
       ("Associators of {Win32_Process='" _
          & ProcessId & "'} Where" _
          & " Resultclass = Win32_LogonSession" _
          & " Assocclass = Win32_SessionProcess", "WQL", 48)
    For Each LogonSession in colLogonSessions    
      ' LogonType= 10 nur Terminalserver
	  if logonSession.logonType = 10 then
		ausgabe = ausgabe & LogonSession.LogonId & ";"&LogonSession.LogonType & ";"&left(LogonSession.StartTime,14)
		Wscript.Echo ausgabe
	  end if	
	Next

Next

