'========================================================================================
'= Process Name    : "Open-Audit" Software and Hardware Inventory VB script componant   =
'= FileName        : audit.vbs                                                      .   =
'= Version         : 0.9.00 pre release number 2                                        =
'= Created on      : September 10, 2003 (???)                                           =
'= Original Author : (c) Mark Unwin 2003                                                =
'=                                                                                      =
'=--(Contributors)----------------------------------------------------------------------=
'= Last Update by  : Kenneth C. Mazie (kcmjr@quasatron.com)                             =
'=                                                                                      =
'=--(Description)-----------------------------------------------------------------------=
'= VBscrip for use with OpenAudit web site.                                             =
'=                                                                                      =
'=--(CHANGES TO CODE)-------------------------------------------------------------------=
'=                                                                                      =
'=                                                                                      =
'========================================================================================

'========================================================================================
'================================    ENVIRONMENT PREP    ================================
'========================================================================================
Dim verbose
Public online
Dim strComputer
Dim mysql
Dim input_file
Dim email_to
Dim email_from
Dim email_failed
Dim email_server
Dim audit_local_domain
Dim local_domain
Dim sql
Dim comment
Dim net_mac_uuid

form_total = ""

'----------------------------------------------------------------------------------------
'-------------------------   Constants - DO NOT CHANGE THESE   --------------------------
'----------------------------------------------------------------------------------------
Const HKEY_CLASSES_ROOT  = &H80000000
Const HKEY_CURRENT_USER  = &H80000001
Const HKEY_LOCAL_MACHINE = &H80000002
Const HKEY_USERS         = &H80000003
Const ForAppending = 8

'----------------------------------------------------------------------------------------
'---------------   Load external configuration file "audit_include.vbs"   ---------------
'------------------------   to set user configurable options   --------------------------
'----------------------------------------------------------------------------------------
ExecuteGlobal CreateObject("Scripting.FileSystemObject").OpenTextFile("audit.config").ReadAll 

'--> Contents of "audit.config" external config file.  Must reside in same folder as AUDIT.VBS.
'--> Any lines below that are uncommented will over-write settings read in from "audit_config".
'--> Settings below may be uncommented for degugging and testing but should normally be commented out.
'audit_location = "l"                   '--> if set to "l" will also audit the locally mapped drives 
'verbose = "y"                          '--> setting to "y" causes additional VB pop-up dialog boxes during the run
'online = "yesxml"                      '--> Use "yesxml" to send data via XML(default setting). use "ie" to send using an IE instance, "p" to print to a local IE instance, "n" for text dump files 
'strComputer = "."                      '--> may be set to a single PC to scan
'ie_visible = "n"                       '--> setting to "y" causes IE instance to be visible
'ie_auto_submit = "y"                   '--> setting to "y" will cause IE to submit each scan automatically
'ie_submit_verbose = "y"                '--> adds extra info to dump
'ie_form_page = "http://localhost/openaudit/admin_pc_add_1.php"    '--> set this to the IE form submit page 
'non_ie_page = "http://localhost/openaudit/admin_pc_add_2.php"     '--> set this to the XML form submit page
'input_file = ""                        '--> may be set to a flat text file for manual t scans
'email_to = "munwin@qpcu.org.au"        '--> who to email for failure notification 
'email_from = "OpenAudit Report"        '--> who the email should appear to come from
'email_server = "192.168.1.1"           '--> email server address
'audit_local_domain = "n"               '--> setting to "y" will cause a full local domain audit
'local_domain = "LDAP://DC=ho,DC=qpcu,DC=org,DC=au"   '--> your local domain
'hfnet = "n"                            '--> include hfnetchk section of script (see below)
'Count = 0                              '--> presets variable to zero
'number_of_audits = 20                  '--> number of simultaneous processes to run
'script_name = "audit.vbs"              '--> this script
'monitor_detect = "y"                   '--> include monitor information
'printer_detect = "y"                   '--> include printer information
'software_audit = "y"                   '--> include software information
'uuid_type = "mac"                      '--> sets the index for each system record, "mac" is default, also "uuid" or "name"

'========================================================================================
'===========================    START OF MAIN PROCESS CODE    ===========================
'========================================================================================

'----------------------------------------------------------------------------------------
'-------------------   Detect and process any command line arguements   -----------------
'----------------------------------------------------------------------------------------
If WScript.Arguments.Count > 0 Then
strComputer = WScript.arguments(0) '--> use the first one to denote a single computer
End If
If WScript.Arguments.Count > 1 Then
strUser = WScript.arguments(1) '--> use the second one for the user account
End If
If WScript.Arguments.Count > 2 Then
strPass = WScript.arguments(2) '--> use the third one as for the password
End If


'----------------------------------------------------------------------------------------
'-----------------------------   Prepare IE Output Display   ----------------------------
'----------------------------------------------------------------------------------------
If online = "p" Then 
  Dim oIE
  Dim bWaitforChoice
  Dim ItemChosen
  Set oIE = CreateObject("InternetExplorer.Application")
  oIE.Visible = True
  oIE.Fullscreen = False
  oIE.Toolbar = True
  oIE.Statusbar = False
  oIE.Navigate("about:blank")
  oIE.document.ParentWindow.resizeto 800,600
  oIE.document.WriteLn "<html>"
  oIE.document.WriteLn "<head>"
  oIE.document.WriteLn "<title>Open Audit - Audit Result</title>"
  oIE.document.WriteLn "<style type=""text/css"">"
  oIE.document.WriteLn "body {"
  oIE.document.WriteLn " font-family: verdana;"
  oIE.document.WriteLn " font-size: 9pt;"
  oIE.document.WriteLn "}"
  oIE.document.WriteLn "h1,h2 {"
  oIE.document.WriteLn " font-family: Trebuchet MS;"
  oIE.document.WriteLn "}"
  oIE.document.WriteLn ".content {"
  oIE.document.WriteLn " position: relative;"
  oIE.document.WriteLn " width: 600px;"
  oIE.document.WriteLn " min-width: 700px;"
  oIE.document.WriteLn " margin: 0 0px 10px 0px;"
  oIE.document.WriteLn " border: 1px solid black;"
  oIE.document.WriteLn " background-color: white;"
  oIE.document.WriteLn " padding: 10px;"
  oIE.document.WriteLn " z-index: 3;"
  oIE.document.WriteLn " font-family: verdana;"
  oIE.document.WriteLn " font-size: 9pt;"
  oIE.document.WriteLn "}"
  oIE.document.WriteLn "</style>"
  oIE.document.WriteLn "</head>"
  oIE.document.WriteLn "<body>"
End If 

'----------------------------------------------------------------------------------------
'--------   Uncomment the following lines to force promting for manual PC input   -------
'----------------------------------------------------------------------------------------
'strAnswer = InputBox("PC to run audit on:", "Audit Script")
'Wscript.Echo "Input PC Name: " & strAnswer
'strComputer = strAnswer

'----------------------------------------------------------------------------------------
'---------------------------   Detect & process manual input   --------------------------
'----------------------------------------------------------------------------------------
If strComputer <> "" Then
  If (IsConnectible(strComputer, "", "") Or (strComputer = ".")) Then
    If strUser <> "" And strPass <> "" Then
    ' Username & Password provided - assume not a domain local PC.
      If verbose = "y" Then
        WScript.echo "Username and password provided - therefore assuming NOT a local domain PC."
      End If
      Set wmiLocator = CreateObject("WbemScripting.SWbemLocator")
      Set wmiNameSpace = wmiLocator.ConnectServer( strComputer, "root\default", strUser, strPass)
      Set oReg = wmiNameSpace.Get("StdRegProv")
      Set objWMIService = wmiLocator.ConnectServer(strComputer, "root\cimv2",strUser,strPass)
      objWMIService.Security_.ImpersonationLevel = 3
	End If
    If strUser = "" And strPass = "" Then
    ' No Username & Password provided - assume a domain local PC
      If verbose = "y" Then
        WScript.echo "No username and password provided - therefore assuming local domain PC."
      End If
      Set oReg=GetObject("winmgmts:{impersonationLevel=impersonate}!\\" & strComputer & "\root\default:StdRegProv")
      Set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")
    End If
    
    Audit (strComputer) '-->>>>>>> RUN THE AUDIT - SINGLE PC MODE
    
  Else
    If verbose = "y" Then
      WScript.echo strComputer & " not available."
    End If
	Set objFSO = CreateObject("Scripting.FileSystemObject")
    Set objFile = objFSO.OpenTextFile("failed_audits.txt", 8)
    objFile.WriteLine strComputer
    objFile.Close
  End If
  If verbose = "y" Then
    WScript.echo "Processing Completed....."
  End If
  WScript.quit '--> Exit the script
End If

'----------------------------------------------------------------------------------------
'------------------   Scan and audit the local domain, if requested   -------------------
'----------------------------------------------------------------------------------------
If audit_local_domain = "y" Then
  Const ADS_SCOPE_SUBTREE = 2
  Set objConnection = CreateObject("ADODB.Connection")
  Set objCommand =   CreateObject("ADODB.Command")
  objConnection.Provider = "ADsDSOObject"
  objConnection.Open "Active Directory Provider"
  Set objCOmmand.ActiveConnection = objConnection
  objCommand.CommandText = "Select Name, Location from '" & local_domain & "' Where objectClass='computer'"  
  objCommand.Properties("Page Size") = 1000
  objCommand.Properties("Searchscope") = ADS_SCOPE_SUBTREE 
  objCommand.Properties("Sort On") = "name"
  Set objRecordSet = objCommand.Execute
  objRecordSet.MoveFirst

  totcomp = objRecordset.recordcount -1
  ReDim comparray(totcomp) ' set array to computer count

  ' Check if failed_audits.txt exists, and create it if need be.
  ' If file exists - remove contents
  Set objFSO = CreateObject("Scripting.FileSystemObject")
  If objFSO.FileExists("failed_audits.txt") Then
    Set objFile = objFSO.OpenTextFile("failed_audits.txt", 2)
    objFile.WriteLine
    objFile.Close
  Else
    Set objFile = objFSO.CreateTextFile("failed_audits.txt", 2)
    objFile.WriteLine
    objFile.Close
  End If

  Do Until objRecordSet.EOF
    On Error Resume Next
    strComputer = objRecordSet.Fields("Name").Value
    comparray(count) = strComputer ' Feed computers into array
    count = count + 1
    If verbose = "y" Then
      WScript.echo "Computer Name from ldap: " & strComputer
    End If
    objRecordSet.MoveNext
  Loop

  num_running = HowMany
  If verbose = "y" Then
    WScript.echo "Number of systems retrieved from ldap: " & UBound(comparray)
    WScript.echo "--------------"
  End If

  For i = 0 To UBound(comparray)
'  For i = 118 To 128
    while num_running > number_of_audits
      If verbose = "y" Then 
        wscript.echo "Processes running (" & num_running & ") greater than number wanted (" & number_of_audits & ")"
        wscript.echo "Therefore - sleeping for 4 seconds."
      End If
      wscript.Sleep 4000
      num_running = HowMany
    Wend
    If comparray(i) <> "" Then
      If verbose = "y" Then
        wscript.echo i & " of " & Ubound(comparray)
        wscript.echo "Processes running: " & num_running
        wscript.echo "Next System: " & comparray(i)
        wscript.echo "--------------"
      End If
      command1 = "cscript " & script_name & " " & comparray(i)
      Set sh1=WScript.CreateObject("WScript.Shell")
      sh1.Run command1, 6, False
      Set sh1 = Nothing
      num_running = HowMany
    End If
  Next
End If

'----------------------------------------------------------------------------------------
'------------   Scan and audit PCs specified in a text file, if requested   -------------
'----------------------------------------------------------------------------------------
On Error Resume Next
If input_file <> "" Then
  Set objFSO = CreateObject("Scripting.FileSystemObject")
  Set objTextFileReading = objFSO.OpenTextFile(input_file, 1)
  objTextFileReading.ReadAll
  dimarray = objTextFileReading.Line - 1
  ReDim comparray(dimarray)
  ReDim userarray(dimarray)
  ReDim passarray(dimarray)
  objTextFileReading.close
  Set objTextFileReading = objFSO.OpenTextFile(input_file, 1)
  Do Until objTextFileReading.AtEndOfStream
    strString = objTextFileReading.ReadLine
    strSplit = Split(strString, ",")
      comparray(count) = strSplit(0)
      userarray(count) = strSplit(1)
      passarray(count) = strSplit(2)
      count = count + 1
  Loop
  num_running = HowMany
  If verbose = "y" Then
    WScript.echo "File " & input_file & " read into array."
    WScript.echo "Number of systems retrieved from file: " & UBound(comparray)
    WScript.echo "--------------"
  End If
  For i = 0 To Ubound(comparray)
    While num_running > number_of_audits
      If verbose = "y" Then
        wscript.echo "Processes running (" & num_running & ") greater than number wanted (" & number_of_audits & ")"
        wscript.echo "Therefore - sleeping for 4 seconds."
      End If
      wscript.Sleep 4000
      num_running = HowMany
    Wend
    If comparray(i) <> "" Then
      If verbose = "y" Then
        wscript.echo i & " of " & Ubound(comparray)
        wscript.echo "Processes running: " & num_running
        wscript.echo "Next System: " & comparray(i)
        wscript.echo "--------------"
      End If
      command1 = "cscript " & script_name & " " & comparray(i) & " " & userarray(i) & " " & passarray(i)
      Set sh1=WScript.CreateObject("WScript.Shell")
      sh1.Run command1, 6, False
      Set sh1 = Nothing
      num_running = HowMany
    End If
  Next
End If

WScript.Sleep 6000  '--> Give the spawned scripts time to fail before emailing

' Open the file failed_audits.txt, read the contents and store in failed_audits variable
Set objFile = objFSO.OpenTextFile("failed_audits.txt", 1)
email_failed = objFile.ReadAll
objFile.Close

'----------------------------------------------------------------------------------------
'---------------------------   Email report of failed audits   --------------------------
'----------------------------------------------------------------------------------------
If email_failed <> "" Then
  Set objEmail = CreateObject("CDO.Message")
  objEmail.From = email_from
  objEmail.To   = email_to
  objEmail.Subject = "Failed Open Audits." 
  objEmail.Textbody = "The following systems failed to audit: " & vbCRLF & email_failed
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpserver") = email_server 
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = 25
  objEmail.Configuration.Fields.Update
  objEmail.Send
End If

If verbose = "y" Then
  WScript.echo "Processing Completed....."
End If

WScript.Quit '--> Exit the script,
'========================================================================================
'==========================     END OF MAIN PROCESS CODE     ============================
'========================================================================================




'========================================================================================
'=======================   START OF PRIMARY AUDIT SCAN FUNCTION   =======================
'========================================================================================
Function Audit(strComputer)
start_time = Timer
Dim dt : dt = Now()
timestamp = Year(dt) & Right("0" & Month(dt),2) & Right("0" & Day(dt),2) & Right("0" & Hour(dt),2) & Right("0" & Minute(dt),2) & Right("0" & Second(dt),2)

'----------------------------------------------------------------------------------------
'---------------------------   Current System Being Audited   ---------------------------
'----------------------------------------------------------------------------------------
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_ComputerSystem",,48)
For Each objItem In colItems
   system_name = objItem.Name
   domain = objItem.Domain
Next
Set colItems = objWMIService.ExecQuery("Select IPAddress from Win32_networkadapterconfiguration WHERE IPEnabled='TRUE'",,48)
For Each objItem In colItems
   system_ip = objItem.IPAddress(0)
Next 
Set wshNetwork = WScript.CreateObject( "WScript.Network" )
user_name = wshNetwork.userName
Set colItems = objWMIService.ExecQuery("Select * from Win32_ComputerSystemProduct",,48)
For Each objItem In colItems
   system_id_number = clean(objItem.IdentifyingNumber)
   system_vendor = clean(objItem.Vendor)
   system_uuid = objItem.UUID
Next

If verbose = "y" Then
   wscript.echo "PC name supplied: " & strComputer
   wscript.echo "PC name from WMI: " & system_name
   full_system_name = LCase(system_name) & "." & LCase(domain)
   wscript.echo "User executing this script: " & user_name
  wscript.echo "System UUID: " & system_uuid
End If
ns_ip = NSlookup(system_name)
If verbose = "y" Then
  wscript.echo "IP: " & ns_ip
End If
If online = "p" Then
  oIE.document.WriteLn "<h1>Open Audit</h1><br />"
End If

'----------------------------------------------------------------------------------------
'----------------------------   Double check WMI is working   ---------------------------
'----------------------------------------------------------------------------------------
If ((UCase(strComputer) <> system_name) And (strComputer <> ".") And (strComputer <> full_system_name) And (strComputer <> ns_ip) And (strComputer <> system_ip)) Then
  email_failed = email_failed & strComputer & ", " & VbCrLf
  ie = Nothing
  Exit Function
End If

'----------------------------------------------------------------------------------------
'--------------------------   Setup for Offline file creation   -------------------------
'----------------------------------------------------------------------------------------

If online = "n" Then
   Set objFSO = CreateObject("Scripting.FileSystemObject")
   Set objTextFile = objFSO.OpenTextFile (system_name & ".txt", ForAppending, True)
End If

'----------------------------------------------------------------------------------------
'--------------------------------   Network Information   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Network Info"
If verbose = "y" Then
  WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("select * from win32_networkadapterconfiguration WHERE IPEnabled='TRUE' " _
   & "AND ServiceName<>'AsyncMac' AND ServiceName<>'VMnetx' " _
   & "AND ServiceName<>'VMnetadapter' AND ServiceName<>'Rasl2tp' " _
   & "AND ServiceName<>'msloop' " _ 
   & "AND ServiceName<>'PptpMiniport' AND ServiceName<>'Raspti' " _
   & "AND ServiceName<>'NDISWan' AND ServiceName<>'NdisWan4' AND ServiceName<>'RasPppoe' " _
   & "AND ServiceName<>'NdisIP' AND ServiceName<>'' AND Description<>'PPP Adapter.'",,48)
For Each objItem In colItems
   net_ip = objItem.IPAddress(0)
   net_mac = objItem.MACAddress
   net_description = objItem.Description
   net_dhcp_enabled = objItem.DHCPEnabled
   net_dhcp_server = objItem.DHCPServer
   net_dns_host_name = objItem.DNSHostName
   If isarray(objItem.DNSServerSearchOrder) Then
     net_dns_server = objItem.DNSServerSearchOrder(0)
     net_dns_server_2 = objItem.DNSServerSearchOrder(1)
   end if
   net_ip_subnet = objItem.IPSubnet(0)
   net_wins_primary = objItem.WINSPrimaryServer
   net_wins_secondary = objItem.WINSSecondaryServer
   Set colItems2 = objWMIService.ExecQuery("Select * from Win32_NetworkAdapter WHERE MACAddress='" & objItem.MACAddress & "'",,48)
   For Each objItem2 in colItems2
       net_adapter_type = objItem2.AdapterType
       net_manufacturer = objItem2.Manufacturer
   Next
  ' Below is to account for a NULL in various items
   If net_ip = "" Then net_ip = "0.0.0.0"
   If IsNull(net_dns_server_2) Then net_dns_server_2 = "none"
   If IsNull(net_dhcp_server) Then net_dhcp_server = "none"
   If net_dhcp_server = "" Then net_dhcp_server = "none"
   If IsNull(net_dns_server) Then net_dns_server = "none"
   If IsNull(net_ip_subnet) Then net_ip_subnet = "none"
   net_description = clean(net_description)
   ' IP Address padded with zeros so it sorts properly
   MyIP = Split(net_ip, ".", -1, 1)
   If MyIP(0) <> "169" AND MyIP(1) <> "254" Then
     MyIP(0) = Right("000" & MyIP(0),3)
     MyIP(1) = Right("000" & MyIP(1),3)
     MyIP(2) = Right("000" & MyIP(2),3)
     MyIP(3) = Right("000" & MyIP(3),3)
     net_ip = MyIP(0) & "." & MyIP(1) & "." & MyIP(2) & "." & MyIP(3)
     If net_ip <> "000.000.000.000" Then net_ip_address = net_ip End If
   End If
   If net_dhcp_server <> "255.255.255.255" Then
     form_input = "network^^^" & net_mac            & "^^^" & net_description   & "^^^" & net_dhcp_enabled _
                       & "^^^" & net_dhcp_server    & "^^^" & net_dns_host_name & "^^^" & net_dns_server _
                       & "^^^" & net_ip             & "^^^" & net_ip_subnet     & "^^^" & net_wins_primary _
                       & "^^^" & net_wins_secondary & "^^^" & net_adapter_type  & "^^^" & net_manufacturer & "^^^"
     entry form_input,comment,objTextFile,oAdd,oComment
     form_input = ""
     If net_mac_uuid = "" Then net_mac_uuid = net_mac End If
   End If
Next

On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_ComputerSystem",,48)
For Each objItem in colItems
   net_domain = objItem.Domain
   net_user_name = objItem.UserName
Next
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_NTDomain",,48)
For Each objItem in colItems
   net_client_site_name = objItem.ClientSiteName
   net_domain_controller_address = objItem.DomainControllerAddress
   net_domain_controller_name = objItem.DomainControllerName
Next

If IsNull(net_ip_address) Then net_ip_address = "" End If
If isnull(net_domain) Then
  oReg.GetStringValue HKEY_LOCAL_MACHINE, "SOFTWARE\Microsoft\Windows NT\CurrentVersion\Winlogon", "DefaultDomainName", net_domain
  If IsNull(net_domain) Then net_domain = "" End If
End If
If isnull(net_user_name) Then
  oReg.GetStringValue HKEY_LOCAL_MACHINE, "SOFTWARE\Microsoft\Windows NT\CurrentVersion\Winlogon", "DefaultUserName", net_user_name
If IsNull(net_user_name) Then net_user_name = "" End If
End If 
If IsNull(net_client_site_name) Then net_client_site_name = "" End If
If IsNull(net_domain_controller_address) Then net_domain_controller_address = "" End If
If IsNull(net_domain_controller_name) Then net_domain_controller_name = "" End If

form_input = "system01^^^" & clean(net_ip_address) & "^^^" & clean(net_domain) _
                   & "^^^" & clean(net_user_name) & "^^^" & clean(net_client_site_name) _
                   & "^^^" & clean(Replace(net_domain_controller_address, "\\", "")) & "^^^" & clean(Replace(net_domain_controller_name, "\\", "")) & "^^^"
entry form_input,comment,objTextFile,oAdd,oComment
form_input = ""

'----------------------------------------------------------------------------------------
'-----------------------------------   Make the UUID   ----------------------------------
'----------------------------------------------------------------------------------------
If uuid_type = "uuid" Then
  ' Do nothing - system_uuid is the uuid already
End If

If uuid_type = "mac" Then
  If net_mac_uuid <> "" Then system_uuid = net_mac_uuid End If
End If

If uuid_type = "name" Then
  If (system_name + "." + net_domain) <> "." Then system_uuid = system_name + "." + net_domain End If
End If

' Defaults below here account for oddities
If ((IsNull(system_uuid) Or system_uuid = "") And (system_model <> "") And (system_id_number <> "")) Then system_uuid = system_model + "." + system_id_number End If
If  (IsNull(system_uuid) Or system_uuid = "" Or system_uuid = ".") Then system_uuid = system_name + "." + net_domain End If
If system_uuid = "00000000-0000-0000-0000-000000000000" Then system_uuid = system_name + "." + domain End If
If system_uuid = "FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF" Then system_uuid = system_name + "." + domain End If

form_input = ""
form_input = "audit^^^" & system_name & "^^^" & timestamp & "^^^" & system_uuid & "^^^" & user_name & "^^^" & ie_submit_verbose & "^^^" & software_audit & "^^^"
entry form_input,comment,objTextFile,oAdd,oComment

'----------------------------------------------------------------------------------------
'---------------------------   System Information & Timezone   --------------------------
'----------------------------------------------------------------------------------------
comment = "System Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_LogicalMemoryConfiguration",,48)
mem_count = 0
For Each objItem In colItems
   mem_count = mem_count + objItem.Capacity
Next
If mem_count > 0 Then
   mem_size = Int(mem_count /1024 /1024)
Else
   Set colItems = objWMIService.ExecQuery("Select * from Win32_LogicalMemoryConfiguration",,48)
   For Each objItem in colItems
      mem_size = objItem.TotalPhysicalMemory
   Next
   mem_size = Int(mem_size /1024)
End If
Set colItems = objWMIService.ExecQuery("Select * from Win32_ComputerSystem",,48)
For Each objItem In colItems
   system_model = clean(objItem.Model)
   system_name = clean(objItem.Name)
   system_num_processors = clean(objItem.NumberOfProcessors)
   system_part_of_domain = clean(objItem.PartOfDomain)
   system_primary_owner_name = clean(objItem.PrimaryOwnerName)
   domain_role = clean(objItem.DomainRole)
Next
If domain_role = "0" Then domain_role_text = "Standalone Workstation" End If
If domain_role = "1" Then domain_role_text = "Workstation" End If
If domain_role = "2" Then domain_role_text = "Standalone Server" End If
If domain_role = "3" Then domain_role_text = "Member Server" End If
If domain_role = "4" Then domain_role_text = "Backup Domain Controller" End If
If domain_role = "5" Then domain_role_text = "Primary Domain Controller" End If

Set colItems = objWMIService.ExecQuery("Select * from Win32_SystemEnclosure",,48)
For Each objItem In colItems
   system_system_type = Join(objItem.ChassisTypes, ",")
Next

Set colItems = objWMIService.ExecQuery("Select * from Win32_TimeZone",,48)
For Each objItem In colItems
  tm_zone = clean(objItem.Caption)
  tm_daylight = clean(objItem.DaylightName)
Next

If system_system_type = "1" Then system_system_type = "Other" End If
If system_system_type = "2" Then system_system_type = "Unknown" End If
If system_system_type = "3" Then system_system_type = "Desktop" End If
If system_system_type = "4" Then system_system_type = "Low Profile Desktop" End If
If system_system_type = "5" Then system_system_type = "Pizza Box" End If
If system_system_type = "6" Then system_system_type = "Mini Tower" End If
If system_system_type = "7" Then system_system_type = "Tower" End If
If system_system_type = "8" Then system_system_type = "Portable" End If
If system_system_type = "9" Then system_system_type = "Laptop" End If
If system_system_type = "10" Then system_system_type = "Notebook" End If
If system_system_type = "11" Then system_system_type = "Hand Held" End If
If system_system_type = "12" Then system_system_type = "Docking Station" End If
If system_system_type = "13" Then system_system_type = "All in One" End If
If system_system_type = "14" Then system_system_type = "Sub Notebook" End If
If system_system_type = "15" Then system_system_type = "Space-Saving" End If
If system_system_type = "16" Then system_system_type = "Lunch Box" End If
If system_system_type = "17" Then system_system_type = "Main System Chassis" End If
If system_system_type = "18" Then system_system_type = "Expansion Chassis" End If
If system_system_type = "19" Then system_system_type = "SubChassis" End If
If system_system_type = "20" Then system_system_type = "Bus Expansion Chassis" End If
If system_system_type = "21" Then system_system_type = "Peripheral Chassis" End If
If system_system_type = "22" Then system_system_type = "Storage Chassis" End If
If system_system_type = "23" Then system_system_type = "Rack Mount Chassis" End If
If system_system_type = "24" Then system_system_type = "Sealed-Case PC"  End If

form_input = "system02^^^" & Trim(system_model) & "^^^" & system_name _
                  & "^^^" & system_num_processors & "^^^" & system_part_of_domain _
                  & "^^^" & system_primary_owner_name & "^^^" & system_system_type _
                  & "^^^" & mem_size & "^^^" & system_id_number _
                  & "^^^" & Trim(system_vendor) & "^^^" & domain_role_text _
                  & "^^^" & tm_zone & "^^^" & tm_daylight & "^^^"
entry form_input,comment,objTextFile,oAdd,oComment
form_input = ""

'----------------------------------------------------------------------------------------
'--------------------------------   Windows Information   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Windows Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next

Set colItems = objWMIService.ExecQuery("Select * from Win32_OperatingSystem",,48)
For Each objItem In colItems
   OSName = objItem.Caption
   If objItem.OSType = "16" Then
     OSName = "Microsoft Windows 95"
   End If
   If objItem.OSType = "17" Then
     OSName = "Microsoft Windows 98"
     If InStr(objItem.Name, "|") Then
        OSName = Left(objItem.Name, InStr(objItem.Name, "|") - 1)
     Else
        OSName = objItem.Name
     End If
   End If
   OSInstall = objItem.InstallDate
   OSInstall = Left(OSInstall, 8)
   OSInstallYear = Left(OSInstall, 4)
   OSInstallMonth = Mid(OSInstall, 5, 2)
   OSInstallDay = Right(OSInstall, 2)
   OSInstall = OSInstallYear & "/" & OSInstallMonth & "/" & OSInstallDay
   OSType = objItem.OSType
   ServicePack = objItem.ServicePackMajorVersion
   OSLang = objItem.OSLanguage
   SystemBuildNumber = objItem.BuildNumber
   sys_version = objItem.Version
   system_description = clean(objItem.Description)
   OSCaption = objItem.Caption
   RegUser = clean(objItem.RegisteredUser)
   WinDir = clean(objItem.WindowsDirectory)
   RegOrg = clean(objItem.Organization)
   Country = objItem.CountryCode
   SerNum = objItem.SerialNumber
   OSSerPack = objItem.ServicePackMajorVersion & "." & objItem.ServicePackMinorVersion
   boot_device = clean(objItem.BootDevice)
   build_number = clean(objItem.BuildNumber)
   Version = objItem.Version
Next
form_input = "system03^^^" & boot_device & "^^^" & build_number _
                  & "^^^" & OSType _
                  & "^^^" & OSName & "^^^" & Country _
                  & "^^^" & system_description & "^^^" & OSInstall _
                  & "^^^" & RegOrg & "^^^" & OSLang _
                  & "^^^" & RegUser & "^^^" & SerNum _
                  & "^^^" & OSSerPack & "^^^" & Version & "^^^" & WinDir & "^^^"
entry form_input,comment,objTextFile,oAdd,oComment
form_input = ""

If online = "p" Then
    oIE.document.WriteLn "<div id=""content"">"
    oIE.document.WriteLn "<table border=""0"" cellpadding=""2"" cellspacing=""0"" class=""content"">"
    oIE.document.WriteLn "<tr><td colspan=""2""><b>Network Information</b></td></tr>"
    oIE.document.WriteLn "<tr><td width=""250"">System Name: </td><td>" & system_name & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Description: </td><td>" & system_description & "</td></tr>"
    oIE.document.WriteLn "<tr><td>MAC Address: </td><td>" & net_mac & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>IP Address: </td><td> " & net_ip_address & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Subnet: </td><td>" & net_ip_subnet & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>DHCP Enabled: </td><td>" & net_dhcp_enabled & "</td></tr>"
    oIE.document.WriteLn "<tr><td>DHCP Server: </td><td>" & net_dhcp_server & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>WINS Server: </td><td>" & net_wins_primary & "</td></tr>"
    oIE.document.WriteLn "<tr><td>DNS Server: </td><td>" & net_dns_server & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>NIC Manufacturer: </td><td>" & net_manufacturer & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Description: </td><td>" & net_description & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Part of Domain: </td><td>" & system_part_of_domain & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Domain Role: </td><td>" & domain_role_text & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Domain: </td><td>" & net_domain & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Domain Site Name: </td><td>" & net_client_site_name & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Domain Controller Address: </td><td>" & Replace(net_domain_controller_address, "\\", "") & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Domain Controller Name: </td><td>" & Replace(net_domain_controller_name, "\\", "") & "</td></tr>"
    oIE.document.WriteLn "</table>"
    oIE.document.WriteLn "</div>"
    oIE.document.WriteLn "<br />"
    oIE.document.WriteLn "<div id=""content"">"
    oIE.document.WriteLn "<table border=""0"" cellpadding=""2"" cellspacing=""0"" class=""content"">"
    oIE.document.WriteLn "<tr><td colspan=""2""><b>System Information</b></td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>User Name: </td><td>" & Replace(net_user_name, "\\", "\") & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Date Audited: </td><td>" & date & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Time Zone: </td><td>" & tm_zone & "</td></tr>"
    oIE.document.WriteLn "<tr><td width=""250"">Registered Owner: </td><td>" & system_primary_owner_name & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>UUID: </td><td>" & system_uuid & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Model: </td><td>" & system_model & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Serial: </td><td>" & system_id_number & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Manufacturer: </td><td>" & trim(system_vendor) & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Chassis: </td><td>" & system_system_type & "</td></tr>"
    oIE.document.WriteLn "</table></div>"
    oIE.document.WriteLn "<br />"
    oIE.document.WriteLn "<div id=""content"">"
    oIE.document.WriteLn "<table border=""0"" cellpadding=""2"" cellspacing=""0"" class=""content"">"
    oIE.document.WriteLn "<tr><td colspan=""2""><b>Windows Information</b></td></tr>"
    oIE.document.WriteLn "<tr><td>OS Name: </td><td>" & OSName & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>OS Install Date: </td><td>" & OSInstall & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Registered User: </td><td>" & RegUser & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Registered Organisation: </td><td>" & RegOrg & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Country: </td><td>" & Country & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Language: </td><td>" & OSLang & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Serial Number: </td><td>" & SerNum & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Service Pack: </td><td>" & OSSerPack & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Windows Directory: </td><td>" & Country & "</td></tr>"
    oIE.document.WriteLn "</table></div>"
    oIE.document.WriteLn "<br style=""page-break-before:always;"" />" 
    oIE.document.WriteLn "<div id=""content"">"
    oIE.document.WriteLn "<table border=""0"" cellpadding=""2"" cellspacing=""0"" class=""content"">"
    oIE.document.WriteLn "<tr><td colspan=""2""><b>Hardware</b></td></tr>"
End If

'----------------------------------------------------------------------------------------
'----------------------------------   Bios Information   --------------------------------
'----------------------------------------------------------------------------------------
comment = "Bios Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next

Set colSMBIOS = objWMIService.ExecQuery ("Select * from Win32_SystemEnclosure",,48)
For Each objSMBIOS in colSMBIOS
bios_asset = objSMBIOS.SMBIOSAssetTag
Next 

Set colItems = objWMIService.ExecQuery("Select * from Win32_BIOS",,48)
For Each objItem in colItems
   form_input = "bios^^^" & clean(objItem.Description) _
                     & "^^^" & clean(objItem.Manufacturer) _
                     & "^^^" & clean(objItem.SerialNumber) _
                     & "^^^" & clean(objItem.SMBIOSBIOSVersion) _
                     & "^^^" & clean(objItem.Version) _
                     & "^^^" & clean(bios_asset) & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  form_input = ""
  If online = "p" Then
    oIE.document.WriteLn "<tr><td>BIOS Manufacturer: </td><td>" & clean(objItem.Manufacturer) & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>BIOS Version: </td><td>" & clean(objItem.Version) & "</td></tr>"
  End If
Next

'----------------------------------------------------------------------------------------
'-------------------------------   Processor Information   ------------------------------
'----------------------------------------------------------------------------------------
comment = "Processor Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_Processor",,48)
count = 0
For Each objItem In colItems
  count = count + 1
  If count > Int(system_num_processors) Then
     Exit For
  End If
  form_input = "processor^^^" & clean(objItem.Caption)                  & "^^^" & clean(objItem.CurrentClockSpeed) & "^^^" _
                              & clean(objItem.CurrentVoltage)           & "^^^" & clean(objItem.DeviceID)          & "^^^" _
                              & clean(objItem.ExtClock)                 & "^^^" & clean(objItem.Manufacturer)      & "^^^" _
                              & clean(objItem.MaxClockSpeed)            & "^^^" & LTrim(clean(objItem.Name))       & "^^^" _
                              & clean(objItem.PowerManagementSupported) & "^^^" & clean(objItem.SocketDesignation) & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  form_input = ""
  If online = "p" Then
    oIE.document.WriteLn "<tr><td width=""250"">Processor: </td><td>" & clean(objItem.Caption) & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Processor Speed: </td><td>" & clean(objItem.MaxClockSpeed) & "</td></tr>"
  End If
Next

'----------------------------------------------------------------------------------------
'---------------------------------   Memory Information   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Memory Info"
If verbose = "y" Then
   WScript.echo comment
End If
Set colItems = objWMIService.ExecQuery("Select MemoryDevices FROM Win32_PhysicalMemoryArray",,48)
For Each objItem in colItems
   system_memory_banks = objItem.MemoryDevices
Next
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select Capacity,DeviceLocator,FormFactor,MemoryType,TypeDetail,Speed FROM Win32_PhysicalMemory",,48)
mem_count = 0
mem_size = 0

For Each objItem In colItems
   mem_count = mem_count + 1

   If mem_count > Int(system_memory_banks) Then
     If verbose = "y" Then
        WScript.echo "mem_count: " & mem_count & "   - system_memory_banks: " & Int(system_memory_banks)
     End If
     Exit For
   End If

   If objItem.FormFactor = "7" Then
      mem_formfactor = "SIMM"
   ElseIf objItem.FormFactor = "8" Then
      mem_formfactor = "DIMM"
   ElseIf objItem.FormFactor = "11" Then
      mem_formfactor = "RIMM"
   ElseIf objItem.FormFactor = "12" Then
      mem_formfactor = "SODIMM"
   ElseIf objItem.FormFactor = "13" Then
      mem_formfactor = "SRIMM"
   Else
      mem_formfactor = "Unknown"
   End If

   If objItem.MemoryType = "0" Then
      mem_detail = "Unknown"
   ElseIf objItem.MemoryType = "1" Then
      mem_detail = "Other"
   ElseIf objItem.MemoryType = "2" Then
      mem_detail = "DRAM"
   ElseIf objItem.MemoryType = "3" Then
      mem_detail = "Synchronous DRAM"
   ElseIf objItem.MemoryType = "4" Then
      mem_detail = "Cache DRAM"
   ElseIf objItem.MemoryType = "5" Then
      mem_detail = "EDO"
   ElseIf objItem.MemoryType = "6" Then
      mem_detail = "EDRAM"
   ElseIf objItem.MemoryType = "7" Then
      mem_detail = "VRAM"
   ElseIf objItem.MemoryType = "8" Then
      mem_detail = "SRAM"
   ElseIf objItem.MemoryType = "9" Then
      mem_detail = "RAM"
   ElseIf objItem.MemoryType = "10" Then
      mem_detail = "ROM"
   ElseIf objItem.MemoryType = "11" Then
      mem_detail = "Flash"
   ElseIf objItem.MemoryType = "12" Then
      mem_detail = "EEPROM"
   ElseIf objItem.MemoryType = "13" Then
      mem_detail = "FEPROM"
   ElseIf objItem.MemoryType = "14" Then
      mem_detail = "EPROM"
   ElseIf objItem.MemoryType = "15" Then
      mem_detail = "CDRAM"
   ElseIf objItem.MemoryType = "16" Then
      mem_detail = "3DRAM"
   ElseIf objItem.MemoryType = "17" Then
      mem_detail = "SDRAM"
   ElseIf objItem.MemoryType = "18" Then
      mem_detail = "SGRAM"
   ElseIf objItem.MemoryType = "19" Then
      mem_detail = "RDRAM"
   ElseIf objItem.MemoryType = "20" Then
      mem_detail = "DDR"
   End If

   If objItem.TypeDetail = "1" Then
      mem_typedetail = "Reserved"
   ElseIf objItem.TypeDetail = "2" Then
      mem_typedetail = "Other"
   ElseIf objItem.TypeDetail = "4" Then
      mem_typedetail = "Unknown"
   ElseIf objItem.TypeDetail = "8" Then
      mem_typedetail = "Fast-paged"
   ElseIf objItem.TypeDetail = "16" Then
      mem_typedetail = "Static column"
   ElseIf objItem.TypeDetail = "32" Then
      mem_typedetail = "Pseudo-static"
   ElseIf objItem.TypeDetail = "64" Then
      mem_typedetail = "RAMBUS"
   ElseIf objItem.TypeDetail = "128" Then
      mem_typedetail = "Synchronous"
   ElseIf objItem.TypeDetail = "256" Then
      mem_typedetail = "CMOS"
   ElseIf objItem.TypeDetail = "512" Then
      mem_typedetail = "EDO"
   ElseIf objItem.TypeDetail = "1024" Then
      mem_typedetail = "Window DRAM"
   ElseIf objItem.TypeDetail = "2048" Then
      mem_typedetail = "Cache DRAM"
   ElseIf objItem.TypeDetail = "4096" Then
      mem_typedetail = "Non-volatile"
   Else
      mem_typedetail = "Unknown"
   End If
   mem_bank = objItem.DeviceLocator
   mem_size = Int(objItem.Capacity /1024 /1024)

   form_input = "memory^^^" & mem_bank       & "^^^" & mem_formfactor & "^^^" & mem_detail & "^^^" _
                            & mem_typedetail & "^^^" & mem_size       & "^^^" & clean(objItem.Speed) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr><td>Memory Slot / Type: </td><td>" & mem_bank & " / " & mem_detail & "</td></tr>"
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Memory Size: </td><td>" & mem_size & "</td></tr>"
   End If
Next

If mem_size = 0 Then
   Set colItems = objWMIService.ExecQuery("Select * from Win32_LogicalMemoryConfiguration",,48)

   For Each objItem In colItems
      mem_size = objItem.TotalPhysicalMemory
   Next
   mem_size = Int(mem_size /1024)

   form_input = "memory^^^" & "Unknown" & "^^^" & "Unknown" & "^^^" & "Unknown" & "^^^" _
                            & "Unknown" & "^^^" & mem_size  & "^^^" & "0" & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""

   If online = "p" Then
      oIE.document.WriteLn "<tr><td>Memory Slot / Type: </td><td>" & mem_bank & " / " & mem_detail & "</td></tr>"
      oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Memory Size: </td><td>" & mem_size & "</td></tr>"
   End If
End If

'----------------------------------------------------------------------------------------
'----------------------------------   Video Information   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Video Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_VideoController",,48)
For Each objItem in colItems
If (InStr(objItem.Caption, "vnc") = 0 And InStr(objItem.Caption, "Innobec SideWindow") = 0) Then
   LeftString = Left(objItem.DriverDate, 8)
   form_input = "video^^^" & Int(objItem.AdapterRAM / 1024 / 1024)    & "^^^" _
                           & clean(objItem.Caption)                   & "^^^" & clean(objItem.CurrentHorizontalResolution) & "^^^" _
                           & clean(objItem.CurrentNumberOfColors)     & "^^^" & clean(objItem.CurrentRefreshRate)          & "^^^" _
                           & clean(objItem.CurrentVerticalResolution) & "^^^" & clean(objItem.Description)                 & "^^^" _
                           & Left(LeftString, 4) & "/" & Mid(LeftString, 5, 2) & "/" & Right(LeftString, 2)                & "^^^" _
                           & clean(objItem.DriverVersion)             & "^^^" & clean(objItem.MaxRefreshRate)              & "^^^" _
                           & clean(objItem.MinRefreshRate)            & "^^^" & clean(objItem.DeviceID)                    & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Video Card: </td><td>" & clean(objItem.Caption) & " mb</td></tr>"
    oIE.document.WriteLn "<tr><td>Video Memory: </td><td>" & Int(objItem.AdapterRAM / 1024 / 1024) & "</td></tr>"
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Video Driver Date: </td><td>" & Left(LeftString, 4) & "/" & Mid(LeftString, 5, 2) & "/" & Right(LeftString, 2) & "</td></tr>"
    oIE.document.WriteLn "<tr><td>Video Driver Version: </td><td>" & clean(objItem.DriverVersion) & "</td></tr>"
   End If
End If
Next

'----------------------------------------------------------------------------------------
'---------------------------------   Monitor Information   ------------------------------
'----------------------------------------------------------------------------------------
comment = "Monitor Info"
If verbose = "y" Then
   WScript.echo comment
End If
Dim strarrRawEDID()
intMonitorCount=0
Const HKLM = &H80000002
sBaseKey = "SYSTEM\CurrentControlSet\Enum\DISPLAY\"
iRC = oReg.EnumKey(HKLM, sBaseKey, arSubKeys)

For Each sKey In arSubKeys
  sBaseKey2 = sBaseKey & sKey & "\"
  iRC2 = oReg.EnumKey(HKLM, sBaseKey2, arSubKeys2)
  For Each sKey2 In arSubKeys2
    oReg.GetMultiStringValue HKLM, sBaseKey2 & sKey2 & "\", "HardwareID", sValue
    For tmpctr=0 To UBound(svalue)
      If LCase(Left(svalue(tmpctr),8))="monitor\" Then
        sBaseKey3 = sBaseKey2 & sKey2 & "\"
        iRC3 = oReg.EnumKey(HKLM, sBaseKey3, arSubKeys3)
        For Each sKey3 In arSubKeys3
          If skey3="Control" Then
            oReg.GetStringValue HKLM, sbasekey3, "DeviceDesc", temp_model
            oReg.GetStringValue HKLM, sbasekey3, "Mfg", temp_manuf
            oReg.GetBinaryValue HKLM, sbasekey3 & "Device Parameters\", "EDID", arrintEDID
            If VarType(arrintedid) <> 8204 Then
              strRawEDID="EDID Not Available"
            Else
              For Each bytevalue In arrintedid
                strRawEDID=strRawEDID & Chr(bytevalue)
              Next
            End If
            ReDim Preserve strarrRawEDID(intMonitorCount)
            strarrRawEDID(intMonitorCount)=strRawEDID
            intMonitorCount=intMonitorCount+1
          End If
        Next
      End If
    Next
  Next
Next

Dim arrMonitorInfo()
ReDim arrMonitorInfo(intMonitorCount-1,5)
Dim location(3)

'for tmpctr=0 to intMonitorCount-1
tmpctr=0
  If strarrRawEDID(tmpctr) <> "EDID Not Available" Then
    location(0)=Mid(strarrRawEDID(tmpctr),&H36+1,18)
    location(1)=Mid(strarrRawEDID(tmpctr),&H48+1,18)
    location(2)=Mid(strarrRawEDID(tmpctr),&H5a+1,18)
    location(3)=Mid(strarrRawEDID(tmpctr),&H6c+1,18)
    strSerFind=Chr(&H00) & Chr(&H00) & Chr(&H00) & Chr(&Hff)
    strMdlFind=Chr(&H00) & Chr(&H00) & Chr(&H00) & Chr(&Hfc)
    intSerFoundAt=-1
    intMdlFoundAt=-1
    For findit = 0 To 3
      If InStr(location(findit),strSerFind)>0 Then
        intSerFoundAt=findit
      End If
      If InStr(location(findit),strMdlFind)>0 Then
        intMdlFoundAt=findit
      End If
    Next
    If intSerFoundAt<>-1 Then tmp=Right(location(intSerFoundAt),14)
    If InStr(tmp,Chr(&H0a))>0 Then
      tmpser=Trim(Left(tmp,InStr(tmp,Chr(&H0a))-1))
    Else
      tmpser=Trim(tmp)
    End If
    If Left(tmpser,1)=Chr(0) Then
      tmpser=Right(tmpser,Len(tmpser)-1)
    Else
      tmpser="Serial Number Not Found in EDID data"
    End If
    If intMdlFoundAt<>-1 Then tmp=Right(location(intMdlFoundAt),14)
    If InStr(tmp,Chr(&H0a))>0 Then
      tmpmdl=Trim(Left(tmp,InStr(tmp,Chr(&H0a))-1))
    Else
      tmpmdl=Trim(tmp)
    End If
    If Left(tmpmdl,1)=Chr(0) Then
      tmpmdl=Right(tmpmdl,Len(tmpmdl)-1)
    Else
      tmpmdl="Model Descriptor Not Found in EDID data"
    End If
    tmpmfgweek=Asc(Mid(strarrRawEDID(tmpctr),&H10+1,1))
    tmpmfgyear=(Asc(Mid(strarrRawEDID(tmpctr),&H11+1,1)))+1990
    tmpmdt=Month(DateAdd("ww",tmpmfgweek,DateValue("1/1/" & tmpmfgyear))) & "/" & tmpmfgyear
    tmpEDIDMajorVer=Asc(Mid(strarrRawEDID(tmpctr),&H12+1,1))
    tmpEDIDRev=Asc(Mid(strarrRawEDID(tmpctr),&H13+1,1))
    tmpver=Chr(48+tmpEDIDMajorVer) & "." & Chr(48+tmpEDIDRev)
    tmpEDIDMfg=Mid(strarrRawEDID(tmpctr),&H08+1,2)
    Char1=0 : Char2=0 : Char3=0
    Byte1=Asc(Left(tmpEDIDMfg,1))
    Byte2=Asc(Right(tmpEDIDMfg,1))
    If (Byte1 And 64) > 0 Then Char1=Char1+16
    If (Byte1 And 32) > 0 Then Char1=Char1+8
    If (Byte1 And 16) > 0 Then Char1=Char1+4
    If (Byte1 And 8) > 0 Then Char1=Char1+2
    If (Byte1 And 4) > 0 Then Char1=Char1+1
    If (Byte1 And 2) > 0 Then Char2=Char2+16
    If (Byte1 And 1) > 0 Then Char2=Char2+8
    If (Byte2 And 128) > 0 Then Char2=Char2+4
    If (Byte2 And 64) > 0 Then Char2=Char2+2
    If (Byte2 And 32) > 0 Then Char2=Char2+1
    Char3=Char3+(Byte2 And 16)
    Char3=Char3+(Byte2 And 8)
    Char3=Char3+(Byte2 And 4)
    Char3=Char3+(Byte2 And 2)
    Char3=Char3+(Byte2 And 1)
    tmpmfg=Chr(Char1+64) & Chr(Char2+64) & Chr(Char3+64)
    tmpEDIDDev1=Hex(Asc(Mid(strarrRawEDID(tmpctr),&H0a+1,1)))
    tmpEDIDDev2=Hex(Asc(Mid(strarrRawEDID(tmpctr),&H0b+1,1)))
    If Len(tmpEDIDDev1)=1 Then tmpEDIDDev1="0" & tmpEDIDDev1
    If Len(tmpEDIDDev2)=1 Then tmpEDIDDev2="0" & tmpEDIDDev2
    tmpdev=tmpEDIDDev2 & tmpEDIDDev1
    ' Accounts for model
    If (tmpmdl = "Model Descriptor Not Found in EDID data" And temp_model <> "") Then tmpmdl = temp_model End If
    If (tmpmdl = ""  And temp_model <> "") Then tmpmdl = temp_model End If
    If (tmpmdl = ""  And temp_model =  "") Then tmpmdl = "Model Descriptor Not Found in EDID data"
    ' Account for serial
    If tmpser = "" Then tmpser = "Serial Number Not Found in EDID data"
    ' Accounts for manufacturer
    If (temp_manuf <> "(Standard monitor types)" And temp_manuf <> "") Then tmpmfg = temp_manuf
    arrMonitorInfo(tmpctr,0)=tmpmfg
    arrMonitorInfo(tmpctr,1)=tmpdev
    arrMonitorInfo(tmpctr,2)=tmpmdt
    arrMonitorInfo(tmpctr,3)=tmpser
    arrMonitorInfo(tmpctr,4)=tmpmdl
    arrMonitorInfo(tmpctr,5)=tmpver

    man_id = clean(arrMonitorInfo(tmpctr,0))
    dev_id = clean(arrMonitorInfo(tmpctr,1))
    man_dt = clean(arrMonitorInfo(tmpctr,2))
    mon_sr = clean(arrMonitorInfo(tmpctr,3))
    mon_md = clean(arrMonitorInfo(tmpctr,4))
    mon_md = escape(mon_md)
    mon_md = replace(mon_md, "%00", "")
    mon_md = unescape(mon_md)
    mon_ed = clean(arrMonitorInfo(tmpctr,5))

     ' Inserts a 0 if month < 10
     temp_date = Split(man_dt, "/", -1, 1)
     temp_date(0) = Right("0" & temp_date(0),2)
     man_dt = temp_date(0) & "/" & temp_date(1)
    If man_id <> "" Then
      form_input = "monitor_sys^^^" & man_id & "^^^" & dev_id & "^^^" & man_dt & "^^^" _
                                    & mon_md & "^^^" & mon_sr & "^^^" & mon_ed & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      form_input = ""
      If verbose = "y" Then
        WScript.echo comment
      End If
      If online = "p" Then
        oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Monitor Manufacturer: </td><td>" & man_id & "</td></tr>"
        oIE.document.WriteLn "<tr><td>Monitor Model: </td><td>" & mon_md & "</td></tr>"
      End If
    End If
  End If
'Next

'----------------------------------------------------------------------------------------
'--------------------------------   USB Attached Devices   ------------------------------
'----------------------------------------------------------------------------------------
comment = "USB Devices"
If verbose = "y" Then
   WScript.echo comment
End If
Set colDevices = objWMIService.ExecQuery ("Select * From Win32_USBControllerDevice")
For Each objDevice In colDevices
  strDeviceName = objDevice.Dependent
  strQuotes = Chr(34)
  strDeviceName = Replace(strDeviceName, strQuotes, "")
  arrDeviceNames = Split(strDeviceName, "=")
  strDeviceName = arrDeviceNames(1)
  Set colUSBDevices = objWMIService.ExecQuery ("Select * From Win32_PnPEntity Where DeviceID = '" & strDeviceName & "'")
  For Each objUSBDevice In colUSBDevices
    If ((objUSBDevice.Description <> "USB Root Hub") And _
        (objUSBDevice.Description <> "HID-compliant mouse") And _
        (objUSBDevice.Description <> "Generic USB Hub") And _
        (objUSBDevice.Description <> "Generic volume") And _
        (objUSBDevice.Description <> "USB Mass Storage Device") And _
        (objUSBDevice.Description <> "HID-compliant device") And _
        (objUSBDevice.Description <> "USB Human Interface Device") And _
        (objUSBDevice.Description <> "HID Keyboard Device") And _
        (objUSBDevice.Description <> "USB Composite Device") And _
        (objUSBDevice.Description <> "HID-compliant consumer control device") And _
        (objUSBDevice.Description <> "USB Mass Storage Device") And _
        (objUSBDevice.Description <> "USB Printing Support")) Then
      If name <> objUSBDevice.Caption Then
        form_input = "usb^^^" & clean(objUSBDevice.Caption)      & "^^^" _
                              & clean(objUSBDevice.Description)  & "^^^" _
                              & clean(objUSBDevice.Manufacturer) & "^^^" _
                              & clean(objUSBDevice.DeviceID)     & "^^^"
        entry form_input,comment,objTextFile,oAdd,oComment
        form_input = ""
      End If
    End If
  Next  
Next

'----------------------------------------------------------------------------------------
'-------------------------------   Hard Drive Information   -----------------------------
'----------------------------------------------------------------------------------------
comment = "Hard Disk Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_DiskDrive",,48)
For Each objItem in colItems
   form_input = "harddrive^^^" _
     & clean(objItem.Caption)      & "^^^" & clean(objItem.Index)           & "^^^" & clean(objItem.InterfaceType) & "^^^" _
     & clean(objItem.Manufacturer) & "^^^" & clean(objItem.Model)           & "^^^" & clean(objItem.Partitions)    & "^^^" _
     & clean(objItem.SCSIBus)      & "^^^" & clean(objItem.SCSILogicalUnit) & "^^^" & clean(objItem.SCSIPort)      & "^^^" _
     & clean(Int(objItem.Size /1024 /1024)) & "^^^" & clean(objItem.PNPDeviceID) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Hard Drive Type: </td><td>" & clean(objItem.InterfaceType) & "</td></tr>"
     oIE.document.WriteLn "<tr><td>Hard Drive Size: </td><td>" & clean(int(objItem.Size /1024 /1024)) & " mb</td></tr>"
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Hard Drive Model: </td><td>" & clean(objItem.Model) & "</td></tr>"
     oIE.document.WriteLn "<tr><td>Hard Drive Partitions: </td><td>" & clean(objItem.Partitions) & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'-------------------------------   Partition Information   ------------------------------
'----------------------------------------------------------------------------------------
comment = "Partition Info"
If verbose = "y" Then
   WScript.echo comment
End If
LocalDrives = HostDrives(strComputer)
For Each LocalDrive in LocalDrives
   On Error Resume Next
   Set colItems = objWMIService.ExecQuery("Select * from Win32_DiskPartition WHERE DeviceID='" & DrivePartition(strComputer, LocalDrive) & "'",,48)
   For Each objItem In colItems
     partition_bootable = objItem.Bootable
     If ((partition_bootable <> "True") Or isnull(partition_bootable)) Then partition_bootable = "False" End If
     partition_boot_partition = objItem.BootPartition
     If ((partition_boot_partition <> "True") Or isnull(partition_boot_partition)) Then partition_boot_partition = "False" End If
     partition_device_id = objItem.DeviceID
     partition_disk_index = objItem.DiskIndex
     partition_index = objItem.Index
     partition_primary_partition = objItem.PrimaryPartition
   Next
   On Error Resume Next
   Set colItems = objWMIService.ExecQuery("Select * from Win32_LogicalDisk WHERE caption='" & LocalDrive &"'",,48)
   For Each objItem In colItems
     partition_caption = objItem.Caption
     partition_file_system = objItem.FileSystem
     partition_free_space = 0
     partition_free_space = int(objItem.FreeSpace /1024 /1024)
     partition_size = 0
     partition_size = int(objItem.Size /1024 /1024)
     partition_volume_name = objItem.VolumeName
     partition_percent = 0
     partition_percent = Round(((partition_size - partition_free_space) / partition_size) * 100 ,0)
   Next
   form_input = "partition^^^" & partition_bootable          & "^^^" & partition_boot_partition    & "^^^" _
                               & partition_device_id         & "^^^" & partition_disk_index        & "^^^" _
                               & partition_percent           & "^^^" & partition_primary_partition & "^^^" _
                               & partition_caption           & "^^^" & partition_file_system       & "^^^" _
                               & partition_free_space        & "^^^" & partition_size              & "^^^" _
                               & partition_volume_name       & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
Next

'----------------------------------------------------------------------------------------
'-------------------------------   SCSI Card Information   ------------------------------
'----------------------------------------------------------------------------------------
comment = "SCSI Cards"
If verbose = "y" Then
   wscript.echo comment
end if
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_SCSIController",,48)
For Each objItem In colItems
   form_input = "scsi_controller^^^" & clean(objItem.Caption) & "^^^" & clean(objItem.DeviceID) & "^^^" & clean(objItem.Manufacturer) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>SCSI Controller: </td><td>" & clean(objItem.Caption) & "</td></tr>"
     oIE.document.WriteLn "<tr><td>SCSI Controller Manufacturer: </td><td>" & clean(objItem.Manufacturer) & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'------------------------------   SCSI Device Information   -----------------------------
'----------------------------------------------------------------------------------------
comment = "SCSI Devices"
If verbose = "y" Then
   wscript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_SCSIControllerDevice",,48)
For Each objItem In colItems
  form_input = "scsi_device^^^" & clean(objItem.Antecedent) & "^^^" & clean(objItem.Dependent) & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  form_input = ""
  'wscript.echo "Device on " & objItem.Antecedent & "   is " & objItem.Dependent
Next

'----------------------------------------------------------------------------------------
'-----------------------------   Optical Drive Information   ----------------------------
'----------------------------------------------------------------------------------------
comment = "Optical Drive Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_CDROMDrive",,48)
For Each objItem in colItems
   form_input = "optical^^^" & clean(objItem.Caption) & "^^^" & clean(objItem.Drive) & "^^^" & clean(objItem.DeviceID) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Optical Drive: </td><td>" & clean(objItem.Drive) & "</td></tr>"
     oIE.document.WriteLn "<tr><td>Optical Drive Caption: </td><td>" & clean(objItem.Caption) & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'------------------------------   Floppy Drive Information   ----------------------------
'----------------------------------------------------------------------------------------
comment = "Floppy Drives"
If verbose = "y" Then
   WScript.echo comment
End If
Set colItems = objWMIService.ExecQuery("SELECT * FROM Win32_FloppyDrive",,48)
For Each objItem In colItems
   form_input = "floppy^^^" & clean(objItem.Description)  & "^^^" _
                            & clean(objItem.Manufacturer) & "^^^" _
                            & clean(objItem.Caption)      & "^^^" _
                            & clean(objItem.DeviceID)     & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Floppy Drive: </td><td>" & clean(objItem.Caption) & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'-------------------------------   Tape Drive Information   -----------------------------
'----------------------------------------------------------------------------------------
comment = "Tape Drive Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next

Set colItems = objWMIService.ExecQuery("Select * from Win32_TapeDrive",,48)
For Each objItem In colItems
   form_input = "tape^^^" & clean(objItem.Caption)      & "^^^" & clean(objItem.Description) & "^^^" _
                          & clean(objItem.Manufacturer) & "^^^" & clean(objItem.Name) & "^^^" & clean(objItem.DeviceID) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr><td>Tape Drive Description: </td><td>" & tape_desc & "</td></tr>"
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Tape Drive Manufacturer: </td><td>" & tape_man & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'--------------------------------   Keyboard Information   ------------------------------
'----------------------------------------------------------------------------------------
comment = "Keyboard Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_Keyboard",,48)
For Each objItem In colItems
   form_input = "keyboard^^^" & clean(objItem.Caption) & "^^^" & clean(objItem.Description) & "^^^" & clean(objItem.DeviceID) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
    oIE.document.WriteLn "<tr><td>Keyboard Description: </td><td>" & clean(objItem.Description) & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'--------------------------------   Battery Information   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Battery Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_Battery",,48)
For Each objItem In colItems
   form_input = "battery^^^" & clean(objItem.Description) & "^^^" & clean(objItem.DeviceID) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Battery Description: </td><td>" & clean(objItem.Description) & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'----------------------------------   Modem Information   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Modem Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_POTSModem",,48)
For Each objItem In colItems
   form_input = "modem^^^" & clean(objItem.AttachedTo)  & "^^^" & clean(objItem.CountrySelected) & "^^^" _
                           & clean(objItem.Description) & "^^^" & clean(objItem.DeviceType)  & "^^^" _
                           & clean(objItem.DeviceID)    & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr><td>Modem Description: </td><td>" & clean(objItem.Description) & "</td></tr>"
   End If
Next

'----------------------------------------------------------------------------------------
'---------------------------------   Mouse Information   --------------------------------
'----------------------------------------------------------------------------------------
comment = "Mouse Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_PointingDevice",,48)
For Each objItem In colItems
  mouse_type = objItem.PointingType
  If mouse_type = "1" Then mouse_type = "Other" End If
  If mouse_type = "2" Then mouse_type = "Unknown" End If
  If mouse_type = "3" Then mouse_type = "Mouse" End If
  If mouse_type = "4" Then mouse_type = "Track Ball" End If
  If mouse_type = "5" Then mouse_type = "Track Point" End If
  If mouse_type = "6" Then mouse_type = "Glide Point" End If
  If mouse_type = "7" Then mouse_type = "Touch Pad" End If
  If mouse_type = "8" Then mouse_type = "Touch Screen" End If
  If mouse_type = "9" Then mouse_type = "Mouse - Optical Sensor" End If
  mouse_port = objItem.DeviceInterface
  If mouse_port = "1" Then mouse_port = "Other" End If
  If mouse_port = "2" Then mouse_port = "Unknown" End If
  If mouse_port = "3" Then mouse_port = "Serial" End If
  If mouse_port = "4" Then mouse_port = "PS/2" End If
  If mouse_port = "5" Then mouse_port = "Infrared" End If
  If mouse_port = "6" Then mouse_port = "HP-HIL" End If
  If mouse_port = "7" Then mouse_port = "Bus mouse" End If
  If mouse_port = "8" Then mouse_port = "ADB (Apple Desktop Bus)" End If
  If mouse_port = "160" Then mouse_port = "Bus mouse DB-9" End If
  If mouse_port = "161" Then mouse_port = "Bus mouse micro-DIN" End If
  If mouse_port = "162" Then mouse_port = "USB" End If
  form_input = "mouse^^^" & clean(objItem.Description) & "^^^" _
                          & clean(objItem.NumberOfButtons) & "^^^" _
                          & clean(objItem.DeviceID) & "^^^" _
                          & mouse_type & "^^^" _
                          & mouse_port & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  form_input = ""
  If online = "p" Then
    oIE.document.WriteLn "<tr bgcolor=""#F1F1F1""><td>Mouse Description: </td><td>" & clean(objItem.Description) & "</td></tr>"
  End If
Next

'----------------------------------------------------------------------------------------
'-------------------------------   Sound Card Information   -----------------------------
'----------------------------------------------------------------------------------------
comment = "Sound Card Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_SoundDevice",,48)
For Each objItem In colItems
   form_input = "sound^^^" & clean(objItem.Manufacturer) & "^^^" & clean(objItem.Name) & "^^^" & clean(objItem.DeviceID) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
   If online = "p" Then
     oIE.document.WriteLn "<tr><td>Sound Description: </td><td>" & clean(objItem.Name) & "</td></tr>"
   End If
Next
sql = ""

' End of Hardware
If online = "p" Then
  oIE.document.WriteLn "</table>"
  oIE.document.WriteLn "</div>"
  oIE.document.WriteLn "<br style=""page-break-before:always;"" />"
End If

'----------------------------------------------------------------------------------------
'--------------------------------   Printer Information   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Printer Info"
If verbose = "y" Then
   WScript.echo comment
End If
create_sql sql, objTextFile, database
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_Printer",,48)
For Each objItem In colItems
   If (objItem.Caption) Then printer_caption = clean(objItem.Caption) Else printer_caption = "" End If
   If (objItem.Default) Then printer_default = clean(objItem.Default) Else printer_default = "" End If
   If (objItem.DriverName) Then printer_driver_name = clean(objItem.DriverName) Else printer_driver_name = "" End If
   printer_horizontal_resolution = objItem.HorizontalResolution
   If (objItem.Local) Then printer_local = clean(objItem.Local) Else printer_local = "False" End If
   printer_port_name = clean(objItem.PortName)
   printer_shared = clean(objItem.Shared)
   printer_share_name = clean(objItem.ShareName)
   printer_vertical_resolution = objItem.VerticalResolution
   If (objItem.SystemName) Then printer_system_name = clean(objItem.SystemName) Else printer_system_name = "" End If
   If (objItem.Location) Then printer_location = clean(objItem.Location) Else printer_location = "" End If
     form_input = "printer^^^" _
     & printer_caption        & "^^^" _
     & printer_local          & "^^^" _
     & printer_port_name      & "^^^" _
     & printer_shared         & "^^^" _
     & printer_share_name     & "^^^" _
     & printer_system_name    & "^^^" _
     & printer_location       & "^^^"
     entry form_input,comment,objTextFile,oAdd,oComment
     form_input = ""
Next

'----------------------------------------------------------------------------------------
'-------------------------------   Local Share Information   ----------------------------
'----------------------------------------------------------------------------------------
comment = "Share Info"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_Share",,48)
For Each objItem In colItems
   form_input = "shares^^^" & clean(objItem.Caption) & "^^^" & clean(objItem.Name) & "^^^" & clean(objItem.Path) & "^^^"
   entry form_input,comment,objTextFile,oAdd,oComment
   form_input = ""
Next

'----------------------------------------------------------------------------------------
'--------------------------------   Locally Mapped Drives   -----------------------------
'----------------------------------------------------------------------------------------
If audit_location = "l" Then
  comment = "Mapped Drives Info"
  If verbose = "y" Then
    WScript.echo comment
  End If
  On Error Resume Next
  Set colItems = objWMIService.ExecQuery("Select * from Win32_LogicalDisk",,48)
  For Each objItem In colItems
    If Left(objItem.ProviderName,2)="\\" Then
      form_input = "mapped^^^" & clean(objItem.DeviceID)                            & "^^^" _
                               & clean(objItem.FileSystem)                          & "^^^" _
                               & Int(Round(objItem.FreeSpace /1024 /1024 /1024 ,1)) & "^^^" _
                               & clean(objItem.ProviderName)                        & "^^^" _
                               & int(Round(objItem.Size /1024 /1024 /1024 ,1))      & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      form_input = ""
    End If
  Next
End If

'----------------------------------------------------------------------------------------
'-------------------------------   Local Group Information   ----------------------------
'----------------------------------------------------------------------------------------
If ((domain_role = "4") Or (domain_role="5")) Then
   If verbose = "y" Then
     WScript.echo "Bypassing Local Groups - This is a domain controller."
   End If
Else
  comment = "Local Groups Info"
  If verbose = "y" Then
     WScript.echo comment
  End If
  On Error Resume Next
  Set colItems = objWMIService.ExecQuery("Select * from Win32_Group where Domain = '" & system_name & "'",,48)
  For Each objItem In colItems
    users = ""
    Set colGroups = GetObject("WinNT://" & strComputer & "")
    colGroups.Filter = Array("group")
    For Each objGroup In colGroups
      If objGroup.Name = objItem.Name Then
        For Each objUser In objGroup.Members
          If users = "" Then
            users = objUser.Name
          Else
            users = users & ", " & objUser.Name
          End If
        Next
      End If
    Next
    If users = "" Then
      users = "No Members in this group."
    End If
    form_input = "l_group^^^" & clean(objItem.Description) & "^^^" _
                             & clean(objItem.Name)         & "^^^" _
                             & users                       & "^^^" _
                             & clean(objItem.SID)          & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    form_input = ""
  Next
End If

'----------------------------------------------------------------------------------------
'-------------------------------   Local User Information   -----------------------------
'----------------------------------------------------------------------------------------
If ((domain_role = "4") Or (domain_role="5")) Then
  If verbose = "y" Then
    WScript.echo "Bypassing Local Users - This is a domain controller."
  End If
Else
  comment = "Local Users Info"
  If verbose = "y" Then
    WScript.echo comment
  End If
  On Error Resume Next
  Set colItems = objWMIService.ExecQuery("Select * from Win32_UserAccount where Domain = '" & system_name & "'",,48)
  For Each objItem In colItems
    form_input = "l_user^^^" & clean(objItem.Description)        & "^^^" _
                             & clean(objItem.Disabled)           & "^^^" _
                             & clean(objItem.FullName)           & "^^^" _
                             & clean(objItem.Name)               & "^^^" _
                             & clean(objItem.PasswordChangeable) & "^^^" _
                             & clean(objItem.PasswordExpires)    & "^^^" _
                             & clean(objItem.PasswordRequired)   & "^^^" _
                             & clean(objItem.SID)                & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    form_input = ""
  Next
End If

'----------------------------------------------------------------------------------------
'------------------------------   HFNetChk Scan Information   ---------------------------
'----------------------------------------------------------------------------------------
If (strComputer <> "KEDRON-QPCU" And strComputer <> "ACADEMY02-QPCU" And strComputer <> "ACADEMY05-QPCU") Then 'QPCU
If hfnet = "y" then
  comment = "HFNetChk"
  If verbose = "y" Then
    WScript.echo comment
  End If
  Set oShell = CreateObject("Wscript.Shell")
  Set oFS = CreateObject("Scripting.FileSystemObject")
  sTemp = oShell.ExpandEnvironmentStrings("%TEMP%")
  sTempFile = sTemp & "\" & oFS.GetTempName
  If (strUser <> "" And strPass <>"") Then
    hfnetchk = "hfnetchk.exe -h " & system_name & " -u " & strUser & " -p " & strPass & " -nosum -vv -x mssecure.xml -o tab -f " & sTempFile
  Else
    hfnetchk = "hfnetchk.exe -h " & system_name & " -vv -x mssecure.xml -nosum -o tab -f " & sTempFile
  End If
  Set sh=WScript.CreateObject("WScript.Shell")
  sh.Run hfnetchk, 6, True
  Set sh = Nothing
  'sql = "DELETE FROM system_security WHERE ss_name = '" & strComputer & "'"
  'create_sql sql, objTextFile, database
  Set objFSO = CreateObject("Scripting.FileSystemObject")
  Set objTextFile2 = objFSO.OpenTextFile(sTempFile, 1)
  Do Until objTextFile2.AtEndOfStream
    strString = objTextFile2.ReadLine
    MyArray = Split(strString, vbTab, -1, 1)
    'hf_org_name = MyArray(0)
    'hf_name = Split(hf_org_name, " ", -1, 1)
    'echo "Name: " & hf_name(0)
    qno = clean(Right(MyArray(4),6))
    If MyArray(0) <> "Machine Name" Then
      'sql = "INSERT IGNORE INTO system_security_bulletins (ssb_bulletin, ssb_title, ssb_qno, ssb_url, ssb_description) VALUES ('" _
      '& clean(MyArray(2)) & "','" & clean(MyArray(3)) & "','" & qno & "','" & clean(MyArray(5)) & "','" & clean(MyArray(7)) & "')"
      'create_sql sql, objTextFile, database
      'form_input = "sys_sec_bul^^^" & clean(MyArray(3)) & "^^^" _
      '                              & clean(MyArray(7)) & "^^^" _
      '                              & clean(MyArray(2)) & "^^^" _
      '                              & qno & "^^^" _
      '                              & clean(MyArray(5)) & "^^^"
      'entry form_input,comment,objTextFile,oAdd,oComment
      'form_input = ""
      
      'sql = "INSERT INTO system_security (ss_name, ss_product, ss_qno, ss_reason, ss_status) VALUES ('" _
      '& hf_name(0) & "','" & clean(MyArray(1)) & "','" & qno & "','" & clean(MyArray(6)) & "','" & clean(MyArray(8)) & "')"
      'create_sql sql, objTextFile, database
      form_input = "hfnet^^^" & qno & "^^^" _
                              & clean(MyArray(8)) & "^^^" _
                              & clean(MyArray(6)) & "^^^" _
                              & clean(MyArray(1)) & "^^^" _
                              & clean(MyArray(3)) & "^^^" _
                              & clean(MyArray(7)) & "^^^" _
                              & clean(MyArray(2)) & "^^^" _
                              & clean(MyArray(5)) & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      form_input = ""
      
    End If
  Loop
  objTextFile2.Close
  objFSO.DeleteFile sTempFile
End If
End If 'QPCU

'----------------------------------------------------------------------------------------
'-------------------------------   Anti-Virus Information   -----------------------------
'----------------------------------------------------------------------------------------
If (ServicePack = "2" And SystemBuildNumber = "2600") Then
  Set objWMIService_AV = GetObject("winmgmts:\\" & strComputer & "\root\SecurityCenter")
  comment = "AV - XP sp2 Settings"
  If verbose = "y" Then
    WScript.echo comment
  End If
  Set colItems = objWMIService_AV.ExecQuery("Select * from AntiVirusProduct")
  For Each objAntiVirusProduct In colItems
    If IsNull(objAntiVirusProduct.companyName) Then av_prod = "" Else av_prod = objAntiVirusProduct.companyName End If
    If IsNull(objAntiVirusProduct.displayName) Then av_disp = "" Else av_disp = objAntiVirusProduct.displayName End If
    If IsNull(objAntiVirusProduct.productUptoDate) Then av_up2d = "" Else av_up2d = objAntiVirusProduct.productUptoDate End If
    If IsNull(objAntiVirusProduct.versionNumber) Then av_vers = "" Else av_vers = objAntiVirusProduct.versionNumber End If
    form_input = "system10^^^" & clean(av_prod) & "^^^" _
                               & clean(av_disp) & "^^^" _
                               & clean(av_up2d) & "^^^" _
                               & clean(av_vers) & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    form_input = ""
  Next
End If

If software_audit = "y" Then
' software audit finishes further down the script

'----------------------------------------------------------------------------------------
'-----------------------------------   Starup Programs   --------------------------------
'----------------------------------------------------------------------------------------
comment = "Startup Programs"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_StartupCommand",,48)
For Each objItem In colItems
  'if objItem.Command <> "desktop.ini" then
  If objItem.Location <> "Startup" And (objItem.User <> ".DEFAULT" Or objItem.User <> "NT AUTHORITY\SYSTEM") Then
    form_input = "startup^^^" & objItem.Caption     & " ^^^" _
                              & objItem.Command     & " ^^^" _
                              & objItem.Description & " ^^^" _
                              & objItem.Location    & " ^^^" _
                              & objItem.Name        & " ^^^" _
                              & objItem.User        & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    form_input = ""
  End If
Next

'----------------------------------------------------------------------------------------
'------------------------------   Local Service Information   ---------------------------
'----------------------------------------------------------------------------------------
comment = "Services"
If verbose = "y" Then
   WScript.echo comment
End If
On Error Resume Next
Set colItems = objWMIService.ExecQuery("Select * from Win32_Service",,48)
For Each objItem In colItems
  form_input = "service^^^" & clean(objItem.Description) & " ^^^" _
                            & clean(objItem.DisplayName) & " ^^^" _
                            & clean(objItem.Name)        & " ^^^" _
                            & clean(objItem.PathName)    & " ^^^" _
                            & clean(objItem.Started)     & " ^^^" _
                            & clean(objItem.StartMode)   & " ^^^" _
                            & clean(objItem.State)       & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  form_input = ""
  If objItem.Name = "W3SVC" Then
    iis = "True"
  End If
Next

'----------------------------------------------------------------------------------------
'-----------------------------   IE Browser Helper Objects   ----------------------------
'----------------------------------------------------------------------------------------
If (OSName <> "Microsoft Windows 95" And OSName <> "Microsoft Windows 98") Then
  comment = "Internet Explorer Browser Helper Objects"
  If verbose = "y" Then
    WScript.echo comment
  End If
  If strUser <> "" And strPass <> "" Then
    Set objWMIService_IE = wmiLocator.ConnectServer(strComputer, "root\cimv2\Applications\MicrosoftIE",strUser,strPass)
    objWMIService_IE.Security_.ImpersonationLevel = 3
  Else
    Set objWMIService_IE = GetObject("winmgmts:\\" & strComputer & "\root\cimv2\Applications\MicrosoftIE")
  End If
  Set colIESettings = objWMIService_IE.ExecQuery ("Select * from MicrosoftIE_Object")
  For Each strIESetting In colIESettings
    form_input = "ie_bho^^^" & clean(strIESetting.CodeBase)    & "^^^" _
                             & clean(strIESetting.Status)      & "^^^" _
                             & clean(strIESetting.ProgramFile) & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    form_input = ""
  Next
End If

'----------------------------------------------------------------------------------------
'---------------------------------   Installed Software   -------------------------------
'----------------------------------------------------------------------------------------
comment = "Installed Software"
If verbose = "y" Then
   WScript.echo comment
End If
If online = "p" Then
    Dim software
    oIE.document.WriteLn "<div id=""content"">"
    oIE.document.WriteLn "<table border=""0"" cellpadding=""2"" cellspacing=""0"" class=""content"">"
    oIE.document.WriteLn "<tr><td colspan=""2""><b>Installed Software</b></td></tr>"
End If
On Error Resume Next
strKeyPath = "SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall"
oReg.EnumKey HKEY_LOCAL_MACHINE,strKeyPath,arrSubKeys
For Each subkey In arrSubKeys
   newpath = strKeyPath & "\" & subkey
   newkey = "DisplayName"
   oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
   If strValue <> "" Then
     version = ""
     uninstall_string = ""
     install_date = ""
     publisher = ""
     install_source = ""
     install_location = ""
     system_component = ""
     display_name = strValue
     newkey = "DisplayVersion"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     version = strValue
     If (IsNull(version)) Then version = "" End If
     
     newkey = "UninstallString"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     uninstall_string = strValue
     If (IsNull(uninstall_string)) Then uninstall_string = "" End If
     
     newkey = "InstallDate"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     install_date = strValue
     If (IsNull(install_date)) Then install_date = "" End If
     
     newkey = "Publisher"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     publisher = strValue
     If (IsNull(publisher)) Then publisher = "" End If
     
     newkey = "InstallSource"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     install_source = strValue
     If (IsNull(install_source)) Then install_source = "" End If
     
     newkey = "InstallLocation"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     install_location = strValue
     If (IsNull(install_location)) Then install_location = "" End If
     
     newkey = "SystemComponent"
     oReg.GetDWORDValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     system_component = strValue
     If (IsNull(system_component)) Then system_component = "" End If
     
     newkey = "URLInfoAbout"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     software_url = strValue
     If (IsNull(software_url)) Then software_url = "" End If
     
     newkey = "Comments"
     oReg.GetStringValue HKEY_LOCAL_MACHINE, newpath, newkey, strValue
     software_comments = strValue
     If (IsNull(software_comments)) Then software_comments = " " End If
     
	 If online = "p" Then
	   software = software & display_name & vbcrlf
	 End If
    form_input = "software^^^" & clean(display_name)      & " ^^^" _
                               & clean(version)           & " ^^^" _
                               & clean(install_location)  & " ^^^" _
                               & clean(uninstall_string)  & " ^^^" _
                               & clean(install_date)      & " ^^^" _
                               & clean(publisher)         & " ^^^" _
                               & clean(install_source)    & " ^^^" _
                               & clean(system_component)  & " ^^^" _
                               & clean(software_url)      &  "^^^" _
                               & clean(software_comments) & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    form_input = ""
   End If
Next

'------------------------  Include Customer Specific Audits  ------------------------
ExecuteGlobal CreateObject("Scripting.FileSystemObject").OpenTextFile("audit_custom_software.inc").ReadAll

'-------------------------------   Installed Codecs   -------------------------------
comment = "Installed Media Codecs"
If verbose = "y" Then
   WScript.echo comment
End If
Set colItems = objWMIService.ExecQuery("SELECT * FROM Win32_CodecFile", , 48)
For Each objItem In colItems
  If clean(objItem.Manufacturer) <> "Microsoft Corporation" Then
    form_input = "software^^^Codec - " & clean(objItem.Group) & " - " & clean(objItem.Filename) & "^^^" _
                                       & clean(objItem.Version) & "^^^" _
                                       & clean(objItem.Caption) & "^^^" _
                                       & " ^^^" _
                                       & clean(objItem.InstallDate) & "^^^" _
                                       & clean(objItem.Manufacturer) & "^^^" _
                                       & " ^^^" _
                                       & " ^^^" _
                                       & " ^^^" _
                                       & clean(objItem.Description) & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    form_input = ""
  End If
Next

comment = "DirectX & Media Player & IE Versions"
If verbose = "y" Then
   WScript.echo comment
End If

'----------------------   Add DirectX to the Software Register   --------------------
strKeyPath = "SOFTWARE\Microsoft\DirectX"
strValueName = "Version"
oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,dx_version
display_name = "DirectX"
If dx_version = "4.08.01.0810" Then display_name = "DirectX 8.1" End If
If dx_version = "4.08.01.0881" Then display_name = "DirectX 8.1" End If
If dx_version = "4.08.01.0901" Then display_name = "DirectX 8.1a" End If
If dx_version = "4.08.01.0901" Then display_name = "DirectX 8.1b" End If
If dx_version = "4.08.02.0134" Then display_name = "DirectX 8.2" End If
If dx_version = "4.09.00.0900" Then display_name = "DirectX 9" End If
If dx_version = "4.09.00.0901" Then display_name = "DirectX 9a" End If
If dx_version = "4.09.00.0902" Then display_name = "DirectX 9b" End If
If dx_version = "4.09.00.0903" Then display_name = "DirectX 9c" End If
If dx_version = "4.09.00.0904" Then display_name = "DirectX 9c" End If
form_input = "software^^^" & display_name       & "^^^" _
                           & dx_version         & "^^^" _
                           & ""                 & "^^^" _
                           & ""                 & "^^^" _
                           & OSInstall          & "^^^" _
                           & "Microsoft Corporation^^^" _
                           & ""                 & "^^^" _
                           & ""                 & "^^^" _
                           & "http://www.microsoft.com/windows/directx/" & "^^^ "
entry form_input,comment,objTextFile,oAdd,oComment
form_input = ""

'--------------   Add Windows Media Player to the Software Register   ------------
strKeyPath = "SOFTWARE\Microsoft\MediaPlayer\PlayerUpgrade"
strValueName = "PlayerVersion"
oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,wmp_version
form_input = "software^^^Windows Media Player^^^" _
                  & wmp_version         & "^^^" _
                  & ""                 & "^^^" _
                  & ""                 & "^^^" _
                  & OSInstall          & "^^^" _
                  & "Microsoft Corporation^^^" _
                  & ""                 & "^^^" _
                  & ""                 & "^^^" _
                  & "http://www.microsoft.com/windows/windowsmedia/default.aspx" & "^^^ "
entry form_input,comment,objTextFile,oAdd,oComment
form_input = ""

'----------------------   Add IE to the Software Register   ----------------------
strKeyPath = "SOFTWARE\Microsoft\Internet Explorer"
strValueName = "Version"
oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,version_ie
form_input = "software^^^Internet Explorer^^^" _
                  & version_ie         & "^^^" _
                  & ""                 & "^^^" _
                  & ""                 & "^^^" _
                  & OSInstall          & "^^^" _
                  & "Microsoft Corporation^^^" _
                  & ""                 & "^^^" _
                  & ""                 & "^^^" _
                  & "http://www.microsoft.com/windows/ie/community/default.mspx" & "^^^ "
entry form_input,comment,objTextFile,oAdd,oComment
form_input = ""

'----------------   Add Outlook Express to the software Register   -----------------
Set colFiles = objWMIService.ExecQuery("Select * from CIM_Datafile Where Name = 'c:\\program files\\Outlook Express\\msimn.exe'",,48)
For Each objFile In colFiles
  form_input = "software^^^Outlook Express^^^" _
                & clean(objFile.Version)         & "^^^" _
                & ""                             & "^^^" _
                & ""                             & "^^^" _
                & OSInstall                      & "^^^" _
                & "Microsoft Corporation^^^" _
                & ""                             & "^^^" _
                & ""                             & "^^^" _
                & "http://support.microsoft.com/default.aspx?xmlid=fh;en-us;oex" & "^^^ "
  entry form_input,comment,objTextFile,oAdd,oComment
  form_input = ""
Next

'----------------   Add Operating System to the software Register   ----------------
form_input = "software^^^" & OSName             & "^^^" _
                           & sys_version        & "^^^" _
                           & ""                 & "^^^" _
                           & ""                 & "^^^" _
                           & OSInstall          & "^^^" _
                           & "Microsoft Corporation^^^" _
                           & ""                 & "^^^" _
                           & ""                 & "^^^" _
                           & "http://www.microsoft.com/windows/default.mspx" & "^^^ "
entry form_input,comment,objTextFile,oAdd,oComment
form_input = ""

If online = "p" Then
 split_software = Split(software, vbcrlf, -1, 1)
 For n = 0 To UBound(split_software) -1
  For m = n+1 To UBound(split_software)
    If LCase(split_software(m)) < LCase(split_software(n)) Then
      temp = split_software(m)
      split_software(m) = split_software(n)
      split_software(n) = temp
    End If
  Next
 Next 
 For g = 1 To UBound(split_software)
  oIE.document.WriteLn "<tr><td>Package Name: </td><td>" & split_software(g) & "</td></tr>"
 Next
  oIE.document.WriteLn "</table>"
  oIE.document.WriteLn "</div>"
  oIE.document.WriteLn "<br style=""page-break-before:always;"" />"
End If

'----------------   Add FireFox Extentions to the software Register   ----------------
comment = "Firefox Extensions"
If verbose = "y" Then
   WScript.echo comment
End If
folder = "c:\documents and settings"
Dim folder_array()
Dim folder_array_2()
i = 0
'Set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")
Set colSubfolders = objWMIService.ExecQuery ("Associators of {Win32_Directory.Name='" & folder & "'} Where AssocClass = Win32_Subdirectory ResultRole = PartComponent")
ReDim folder_array(colSubFolders.count)
ReDim folder_array_2(colSubFolders.count)
For Each objFolder In colSubfolders
  folder = Split(objFolder.Name,"\",-1,1)
  moz_folder = "\\" & system_name & "\c$\documents and settings" & "\" & folder(2) & "\application data\mozilla\firefox\profiles"
  Set objFSO = CreateObject("Scripting.FileSystemObject")
  If objFSO.FolderExists(moz_folder) Then
    folder_array(i) = objFolder.Name & "\application data\mozilla\firefox\profiles"
    folder_array_2(i) = moz_folder
    i = i + 1
  End If
Next
ReDim preserve folder_array(i - 1)
ReDim preserve folder_array_2(i -1)
For i = 0 To UBound(folder_array)
  'If don't want to redim preserve above, you could comment out and do something like
  'instead.
  'if folder_array(i) = "" then
  ' exit for
  'end if
  Set colSubfolders2 = objWMIService.ExecQuery ("Associators of {Win32_Directory.Name='" & folder_array(i) & "'} Where AssocClass = Win32_Subdirectory ResultRole = PartComponent")
  For Each objFolder2 In colSubfolders2
    split_folder = Split(objFolder2.Name,"\",-1,1)
    'wscript.echo "Returned (local) directory"
    'wscript.echo objFolder2.Name
    'wscript.echo "--------------------------"
    moz_folder_2 = folder_array_2(i) & "\" & split_folder(7) & "\Extensions.rdf"
    moz_folder_3 = folder_array_2(i) & "\" & split_folder(7) & "\extensions\Extensions.rdf"
    'wscript.echo "Calculated remote filename"
    'wscript.echo moz_folder_2
    'wscript.echo "--------------------------"
    If objFSO.FileExists(moz_folder_2) Then
      Set objTextFile = objFSO.OpenTextFile(moz_folder_2, 1)
      Do Until objTextFile.AtEndOfStream
        input_string = objTextFile.ReadLine
        MyPos = InStr(1, input_string, "<RDF:Description")
        If MyPos > 0 Then
          Do Until objTextFile.AtEndOfStream
            input_string2 = objTextFile.ReadLine
            MyPos2 = InStr(1, input_string2, "</RDF:Description>")
            If MyPos2 > 0 Then Exit Do
            MyArray = Split(input_string2, Chr(34), -1, 1)
            If InStr(1, MyArray(0), "S1:version=") Then version = MyArray(1)
            If InStr(1, MyArray(0), "S1:name=") Then name = MyArray(1)
            If InStr(1, MyArray(0), "S1:description=") Then description = MyArray(1)
            If InStr(1, MyArray(0), "S1:creator=") Then creator = MyArray(1)
            If InStr(1, MyArray(0), "S1:homepageURL=") Then homepage = MyArray(1)
          Loop
          'wscript.echo "--------------------"
          'wscript.echo "Name: Mozilla Firefox Extension - " & name
          'wscript.echo "Version: " & version
          'wscript.echo "Description: " & description
          'wscript.echo "Creator: " & creator
          'wscript.echo "Homepage: " & homepage
          If name <> "" Then
            form_input = "software^^^Mozilla Firefox Extension - " & clean(name) & "^^^" _
                                      & clean(version) & "^^^" _
                                      & "^^^" _
                                      & "^^^" _
                                      & "^^^" _
                                      & clean(creator) & "^^^" _
                                      & "^^^" _
                                      & "^^^" _
                                      & clean(homepage) & "^^^" _
                                      & clean(description) & "^^^"
            entry form_input,comment,objTextFile,oAdd,oComment
          End If
          form_input = ""
          name = ""
          version = ""
          description = ""
          creator = ""
          homepage = ""
        End If
      Loop
    End If
    If objFSO.FileExists(moz_folder_3) Then
      Set objTextFile = objFSO.OpenTextFile(moz_folder_3, 1)
      Do Until objTextFile.AtEndOfStream
        input_string = objTextFile.ReadLine
        MyPos = InStr(1, input_string, "<RDF:Description")
        If MyPos > 0 Then
          Do Until objTextFile.AtEndOfStream
            input_string2 = objTextFile.ReadLine
            MyPos2 = InStr(1, input_string2, "</RDF:Description>")
            If MyPos2 > 0 Then Exit Do
            MyArray = Split(input_string2, Chr(34), -1, 1)
            If InStr(1, MyArray(0), "em:version=") Then version = MyArray(1)
            If InStr(1, MyArray(0), "em:name=") Then name = MyArray(1)
            If InStr(1, MyArray(0), "em:description=") Then description = MyArray(1)
            If InStr(1, MyArray(0), "em:creator=") Then creator = MyArray(1)
            If InStr(1, MyArray(0), "em:homepageURL=") Then homepage = MyArray(1)
          Loop
          'wscript.echo "--------------------"
          'wscript.echo "Name: Mozilla Firefox Extension - " & name
          'wscript.echo "Version: " & version
          'wscript.echo "Description: " & description
          'wscript.echo "Creator: " & creator
          'wscript.echo "Homepage: " & homepage
          If name <> "" Then
            form_input = "software^^^Mozilla Firefox Extension - " & clean(name) & "^^^" _
                                      & clean(version) & "^^^" _
                                      & "^^^" _
                                      & "^^^" _
                                      & "^^^" _
                                      & clean(creator) & "^^^" _
                                      & "^^^" _
                                      & "^^^" _
                                      & clean(homepage) & "^^^" _
                                      & clean(description) & "^^^"
            entry form_input,comment,objTextFile,oAdd,oComment
          End If
          form_input = ""
          name = ""
          version = ""
          description = ""
          creator = ""
          homepage = ""
        End If
      Loop
    End If
  Next
Next

End If '--> End of software audit section

'----------------------------------------------------------------------------------------
'------------------------------   XP SP2 Firewall Settings   ----------------------------
'----------------------------------------------------------------------------------------
If (ServicePack = "2" And SystemBuildNumber = "2600") Then
  comment = "Firewall Settings"
  If verbose = "y" Then
    WScript.echo comment
  End If
  On Error Resume Next
  ' Domain Settings
  strKeyPath = "SYSTEM\CurrentControlSet\Services\SharedAccess\Parameters\FirewallPolicy\DomainProfile"
  strValueName = "EnableFirewall"
  oReg.GetDWORDValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,dm_EnFirewall
  If IsNull(dm_EnFirewall) Then dm_EnFirewall = "" End If
  strValueName = "DisableNotifications"
  oReg.GetDWORDValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,dm_DisNotifications
  If IsNull(dm_DisNotifications) Then dm_DisNotifications = "" End If
  strValueName = "DoNotAllowExceptions"
  oReg.GetDWORDValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,dm_DNExceptions
  If IsNull(dm_DNExceptions) Then dm_DNExceptions = "" End If
  ' Non-Domain Settings
  strKeyPath = "SYSTEM\CurrentControlSet\Services\SharedAccess\Parameters\FirewallPolicy\StandardProfile"
  strValueName = "EnableFirewall"
  oReg.GetDWORDValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,std_EnFirewall
  If IsNull(std_EnFirewall) Then std_EnFirewall = "" End If
  strValueName = "DisableNotifications"
  oReg.GetDWORDValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,std_DisNotifications
  If IsNull(std_DisNotifications) Then std_DisNotifications = "" End If
  strValueName = "DoNotAllowExceptions"
  oReg.GetDWORDValue HKEY_LOCAL_MACHINE,strKeyPath,strValueName,std_DNExceptions
  If IsNull(std_DNExceptions) Then std_DNExceptions = "" End If
  form_input = "system11^^^" & clean(dm_EnFirewall) & "^^^" _
                             & clean(dm_DisNotifications) & "^^^" _
                             & clean(dm_DNExceptions) & "^^^" _
                             & clean(std_EnFirewall) & "^^^" _
                             & clean(std_DisNotifications) & "^^^" _
                             & clean(std_DNExceptions) & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  form_input = ""
  strKeyPath = "SYSTEM\CurrentControlSet\Services\SharedAccess\Parameters\FirewallPolicy\StandardProfile\AuthorizedApplications\List"
  oReg.EnumValues HKEY_LOCAL_MACHINE,strKeyPath,arrSubKeys
  For Each subkey In arrSubKeys
    If subkey <> "" Then
      oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,subKey,key
      value = Split(key, ":", -1, 1)
      If InStr(value(0),"%windir%") <> 0 Then
        application_path = clean(value(0))
        application_remote_address = clean(value(1))
        application_enabled = clean(value(2))
        application_name = clean(value(3))
      Else
        application_path = value(0) & ":" & value(1)
        application_path = clean(application_path)
        application_remote_address = clean(value(2))
        application_enabled = clean(value(3))
        application_name = clean(value(4))
      End If
      form_input = "fire_app^^^" & application_name           & "^^^" _
                                 & application_path           & "^^^" _
                                 & application_remote_address & "^^^" _
                                 & application_enabled        & "^^^" _
                                 & "Standard"                 & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      form_input = ""
    End If
  Next
  strKeyPath = "SYSTEM\CurrentControlSet\Services\SharedAccess\Parameters\FirewallPolicy\DomainProfile\AuthorizedApplications\List"
  oReg.EnumValues HKEY_LOCAL_MACHINE,strKeyPath,arrSubKeys
  For Each subkey In arrSubKeys
    If subkey <> "" Then
      oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,subKey,key
      value = Split(key, ":", -1, 1)
      If InStr(value(0),"%windir%") <> 0 Then
        application_path = clean(value(0))
        application_remote_address = clean(value(1))
        application_enabled = clean(value(2))
        application_name = clean(value(3))
      Else
        application_path = value(0) & ":" & value(1)
        application_path = clean(application_path)
        application_remote_address = clean(value(2))
        application_enabled = clean(value(3))
        application_name = clean(value(4))
      End If
      form_input = "fire_app^^^" & application_name           & "^^^" _
                                 & application_path           & "^^^" _
                                 & application_remote_address & "^^^" _
                                 & application_enabled        & "^^^" _
                                 & "Domain"                   & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      form_input = ""
    End If
  Next
  strKeyPath = "SYSTEM\CurrentControlSet\Services\SharedAccess\Parameters\FirewallPolicy\StandardProfile\GloballyOpenPorts\List"
  oReg.EnumValues HKEY_LOCAL_MACHINE,strKeyPath,arrSubKeys
  For Each subkey In arrSubKeys
    If subkey <> "" Then
      oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,subKey,key
      value = Split(key, ":", -1, 1)
      port_number = value(0)
      port_protocol = value(1)
      port_scope = value(2)
      port_enabled = value(3)
      form_input = "fire_port^^^" & port_number   & "^^^" _
                                  & port_protocol & "^^^" _
                                  & port_scope    & "^^^" _
                                  & port_enabled  & "^^^" _
                                  & "User"        & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
    End If
  Next
  strKeyPath = "SYSTEM\CurrentControlSet\Services\SharedAccess\Parameters\FirewallPolicy\DomainProfile\GloballyOpenPorts\List"
  oReg.EnumValues HKEY_LOCAL_MACHINE,strKeyPath,arrSubKeys
  For Each subkey In arrSubKeys
    If subkey <> "" Then
      oReg.GetStringValue HKEY_LOCAL_MACHINE,strKeyPath,subKey,key
      value = Split(key, ":", -1, 1)
      port_number = value(0)
      port_protocol = value(1)
      port_scope = value(2)
      port_enabled = value(3)
      form_input = "fire_port^^^" & port_number   & "^^^" _
                                  & port_protocol & "^^^" _
                                  & port_scope    & "^^^" _
                                  & port_enabled  & "^^^" _
                                  & "Domain"      & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
    End If
  Next
End If

'----------------------------------------------------------------------------------------
'--------------------------------------   CD Keys   -------------------------------------
'----------------------------------------------------------------------------------------
comment = "CD Keys"
If verbose = "y" Then
  WScript.echo comment
End If

'-----------------------------   MS CD Keys for Office 2003 -----------------------------
strKeyPath = "SOFTWARE\Microsoft\Office\11.0\Registration"
oReg.EnumKey HKEY_LOCAL_MACHINE, strKeyPath, arrSubKeys
For Each subkey In arrSubKeys
  name_2003 = get_sku_2003(subkey)
  release_type = get_release_type(subkey)
  edition_type = get_edition_type(subkey)
  path = strKeyPath & "\" & subkey
  strOffXPRU = "HKLM\" & path & "\DigitalProductId"
  subKey = "DigitalProductId"
  oReg.GetBinaryValue HKEY_LOCAL_MACHINE,path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey=GetKey(key)
      form_input = "ms_keys^^^" & name_2003     & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                                & "office_2003" & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
  End If
Next

'------------------------------   MS CD Keys for Office XP ------------------------------
strKeyPath = "SOFTWARE\Microsoft\Office\10.0\Registration"
oReg.EnumKey HKEY_LOCAL_MACHINE, strKeyPath, arrSubKeys
For Each subkey In arrSubKeys
  name_xp = get_sku_xp(subkey)
  release_type = get_release_type(subkey)
  edition_type = get_edition_type(subkey)
  path = strKeyPath & "\" & subkey
  strOffXPRU = "HKLM\" & path & "\DigitalProductId"
  subKey = "DigitalProductId"
  oReg.GetBinaryValue HKEY_LOCAL_MACHINE,path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey=GetKey(key)
      form_input = "ms_keys^^^" & name_xp       & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                                & "office_xp"   & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
  End If
Next

'-----------------------   MS CD Keys for Windows OS (XP, 2K, 2K3)   ---------------------
IsOSXP = InStr(OSName, "Windows XP")
IsOS2K = InStr(OSName, "Windows 2000")
IsOS2K3 = InStr(OSName, "Server 2003")
IsOSXP2K2K3 = CInt(IsOSXP + IsOS2K + IsOS2K3)

If (IsOSXP2K2K3 > 0) Then
  path = "SOFTWARE\Microsoft\Windows NT\CurrentVersion"
  subKey = "DigitalProductId"
  oReg.GetBinaryValue HKEY_LOCAL_MACHINE,path,subKey,key
  strXPKey=GetKey(key)
  If IsNull(strXPKey) Then
  Else
    form_input = "ms_keys^^^" & OSName            & "^^^" _
                              & strXPKey          & "^^^" _
                              & SystemBuildNumber & "^^^" _
                              & Version           & "^^^" _
                              & "windows_xp"      & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If
End If

'-----------------------------   MS CD Keys for Windows OS (NT)   -------------------------
If InStr(OSName, "Windows NT") Then
  path = "SOFTWARE\Microsoft\Windows NT\CurrentVersion"
  subKey = "ProductId"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,path,subKey,key
  If IsNull(Key) Then
  Else
  form_input = "ms_keys^^^" & OSName            & "^^^" _
                            & Key               & "^^^" _
                            & SystemBuildNumber & "^^^" _
                            & Version           & "^^^" _
                            & "windows_nt"      & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  strOffXPRUKey = ""
  release_type = ""
  edition_type = ""
  form_input = ""
  End If
End If

'-----------------------------   MS CD Keys for Windows OS (98)   -------------------------
If (InStr(OSName, "Windows 98") Or InStr(OSName, "Windows ME")) Then
  path = "Software\Microsoft\Windows\CurrentVersion"
  subKey = "ProductKey"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,path,subKey,key
  If IsNull(Key) Then
  Else
  form_input = "ms_keys^^^" & OSName            & "^^^" _
                            & Key               & "^^^" _
                            & SystemBuildNumber & "^^^" _
                            & Version           & "^^^" _
                            & "windows_98"      & "^^^"
  entry form_input,comment,objTextFile,oAdd,oComment
  strOffXPRUKey = ""
  release_type = ""
  edition_type = ""
  form_input = ""
  End If
End If

'-----------------------------   CD Keys for Crystal Reports 9.0   -------------------------
strKeyPath = "SOFTWARE\Crystal Decisions\9.0\Crystal Reports\Keycodes"
oReg.EnumKey HKEY_LOCAL_MACHINE, strKeyPath, arrSubKeys
For Each subkey In arrSubKeys

  name_xp = "Crystal Reports 9.0 " & subkey
  release_type = ""
  edition_type = subkey
  path = strKeyPath & "\" & subkey
  subKey = ""
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = GetCrystalKey(key)
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "crystal_reports_9" & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If
Next

'----------------------------   CD Keys for Crystal Reports 11.0   -------------------------
strKeyPath = "SOFTWARE\Business Objects\Suite 11.0\Crystal Reports"

  name_xp = "Crystal Reports - 11.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "PIDKEY"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = key
    subKey = "Version"
    oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
    release_type = key
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "crystal_reports_11" & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If

'----------------------------------   CD Keys for Nero 6.0   ------------------------------
strKeyPath = "SOFTWARE\Ahead\Nero - Burning Rom\Info"

  name_xp = "Nero Burning Rom - 6.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "Serial6"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = key
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "nero_6"      & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If

'-------------------------------   CD Adobe Photoshop 5.0 LE   ---------------------------
strKeyPath = "SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\Adobe Photoshop 5.0 Limited Edition"

  name_xp = "Adobe Photoshop 5.0 LE"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "ProductID"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = key
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "photoshop_5" & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If

'---------------------------------   CD Keys AutoCAD 2004 LT  ------------------------------
strKeyPath = "SOFTWARE\Autodesk\AutoCAD LT\R9\ACLT-201:40A"

  name_xp = "Autocad 2004 LT"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "SerialNumber"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
  strOffXPRUKey = key
      form_input = "ms_keys^^^" & name_xp       & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                                & "autocad_2000" & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
  end If

'---------------------------------   CD Keys AutoCAD 2005 LT  ------------------------------
strKeyPath = "SOFTWARE\Autodesk\AutoCAD LT\R10\ACLT-301:409"

  name_xp = "Autocad 2005 LT"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "SerialNumber"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
  strOffXPRUKey = key
      form_input = "ms_keys^^^" & name_xp       & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                                & "autocad_2000" & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
 End If

'-------------------------------   CD Keys Adobe Photoshop 7.0  ----------------------------
strKeyPath = "SOFTWARE\Adobe\Photoshop\7.0\Registration"

  name_xp = "Adobe Photoshop 7.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "SERIAL"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = key
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "photoshop_7" & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If

'--------------------------------   CD Keys Adobe Acrobat 5.0   ----------------------------
strKeyPath = "SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\Adobe Acrobat 5.0"

  name_xp = "Adobe Acrobat 5.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "ProductID"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = key
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "acrobat_5"   & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If

'--------------------------------   CD Keys MS SQL Svr 2000   ----------------------------
strKeyPath = "SOFTWARE\Microsoft\Microsoft SQL Server\80\Registration"

  name_xp = "SQL Server 2000"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "CD_Key"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = key
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "sql_server_2000" & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  End If

'-----------------------------   CD Keys VMware Workstation 4.0   --------------------------
strKeyPath = "SOFTWARE\VMware, Inc.\VMware Workstation\License.ws.4.0"

  name_xp = "VMWare Workstation 4.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "Serial"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
    strOffXPRUKey = key
    form_input = "ms_keys^^^" & name_xp       & "^^^" _
                              & strOffXPRUKey & "^^^" _
                              & release_type  & "^^^" _
                              & edition_type  & "^^^" _
                              & "vmware_4"    & "^^^"
    entry form_input,comment,objTextFile,oAdd,oComment
    strOffXPRUKey = ""
    release_type = ""
    edition_type = ""
    form_input = ""
  end if

'---------------------------------   CD Keys Autocad 2006 LT   -----------------------------
strKeyPath = "SOFTWARE\Autodesk\AutoCAD LT\R11\ACLT-4001:409"

  name_xp = "Autocad 2006 LT"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "SerialNumber"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
  strOffXPRUKey = key
      form_input = "ms_keys^^^" & name_xp       & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                               & "autocad_2000" & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
  End If

'------------------------------   CD Keys VMWare workstation 5.0   --------------------------
strKeyPath = "SOFTWARE\VMware, Inc.\VMware Workstation\License.ws.5.0"

  name_xp = "VMWare Workstation 5.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "Serial"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
  strOffXPRUKey = key
      form_input = "ms_keys^^^" & name_xp       & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                                & "vmware_5"    & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
  End If

'----------------------------   CD Keys Adobe Illustrator 10.0   -------------------------
strKeyPath = "SOFTWARE\Adobe\Illustrator\10\Registration"

  name_xp = "Adobe Illustrator 10.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "SERIAL"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
  strOffXPRUKey = key
      form_input = "ms_keys^^^" & name_xp       & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                                & "illustrator_10" & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
  End If

'----------------------------   CD Keys Cyberlink PowerDVD 4.0   ------------------------
strKeyPath = "SOFTWARE\CyberLink\PowerDVD"

  name_xp = "Cyberlink PowerDVD 4.0"
  release_type = ""
  edition_type = ""
  path = strKeyPath
  subKey = "CDKey"
  oReg.GetStringValue HKEY_LOCAL_MACHINE,Path,subKey,key
  If IsNull(key) Then
  Else
  strOffXPRUKey = key
      form_input = "ms_keys^^^" & name_xp       & "^^^" _
                                & strOffXPRUKey & "^^^" _
                                & release_type  & "^^^" _
                                & edition_type  & "^^^" _
                                & "powerdvd_4"  & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
      strOffXPRUKey = ""
      release_type = ""
      edition_type = ""
      form_input = ""
  End If

'----------------------------------------------------------------------------------------
'----------------------------   Enumerate IIS Information   -----------------------------
'----------------------------------------------------------------------------------------
If iis = "True" Then
  comment = "IIS Info"
  If verbose = "y" Then
     WScript.echo comment
  End If

WinDir = Right(WinDir,len(WinDir)-2)
full_path = "\\" & system_name & "\c$" & WinDir & "\system32\inetsrv\inetinfo.exe"
If verbose = "y" Then
Wscript.Echo "IIS Version: " & objFSO.GetFileVersion(full_path)
End If

  On Error Resume Next '--> Initialize error checking
  For WebSiteID = 1 To 255

s = WebSiteID
p = system_name

    On Error Resume Next '--> Initialize error checking

' Initialize variables
Dim ArgPhysicalServer, ArgSiteIndex, ArgFilter, ArgVirtualDirectory
Dim ArgsCounter, ArgNum
Dim objWebServer, objWebRootDir, objWebLog, objWebFilter, objWebVirtualDir
Dim BindingArray, strServerBinding, strSecureBinding
Dim SecurityDescriptor, DiscretionaryAcl, IPSecurity
Dim strPath, Item, Member, VirDirCounter, Counter

' Default values
ArgNum = 0

ArgPhysicalServer = system_name
ArgSiteIndex = WebSiteID

'------------------   Specify and bind to the administrative objects   --------------------
    Set objWebServer = GetObject("IIS://" & ArgPhysicalServer & "/w3svc/" & ArgSiteIndex)
    Set objWebRootDir = GetObject("IIS://" & ArgPhysicalServer & "/w3svc/" & ArgSiteIndex & "/Root")

    If Err <> 0 Then '--> Verify that the specified website exists
    '
    Else  '--> do enumerate for this websiteID - will end if at end of function
'--------------------------------------   Web Site Tab   ----------------------------------
      iis_desc = objWebServer.ServerComment
      For Each Item In objWebServer.ServerBindings
        strServerBinding = Item
        BindingArray = Split(strServerBinding, ":", -1, 1)
        If BindingArray(0) = "" Then
          iis_ip = "<All Unassigned>"
        Else
          iis_ip =  BindingArray(0)
        End If
        iis_port =  BindingArray(1)
        If BindingArray(2) = "" Then
          iis_host = "<None>"
        Else
          iis_host = BindingArray(2)
        End If
        form_input = "iis_3^^^" & ArgSiteIndex & "^^^" _
                                & iis_ip       & "^^^" _
                                & iis_port     & "^^^" _
                                & iis_host     & "^^^"
        entry form_input,comment,objTextFile,oAdd,oComment
        form_input = ""
      Next
      For Each Item In objWebServer.SecureBindings
        strSecureBinding = Item
        BindingArray = Split(strSecureBinding, ":", -1, 1)
        If BindingArray(0) = "" Then
          iis_sec_ip = "<All Unassigned>"
        Else
          iis_sec_ip = BindingArray(0)
        End If
        iis_sec_port = BindingArray(1)
      Next
      If strSecureBinding = "" Then
        iis_sec_port = "No Secure Bindings"
      End If
      If objWebServer.LogType = 0 Then
        iis_log_en =  "Disabled"
      Else
        iis_log_en =  "Enabled"
        Set objWebLog = GetObject("IIS://" & ArgPhysicalServer & "/logging")
        For Each Item In objWebLog
          If objWebServer.LogPluginCLSID = Item.LogModuleID Then
            iis_log_format = Item.Name
            objWebLog = Item.Name
          End If
        Next
        If objWebServer.LogFilePeriod = 0 Then
          If objWebServer.LogFileTruncateSize = -1 Then
            iis_log_per = "Unlimited file size"
          Else
            iis_log_per = "When file size reaches " & (objWebServer.LogfileTruncateSize/1048576) & " MB"
          End If
        End If
        If objWebServer.LogFilePeriod = 1 Then
          iis_log_per = "Daily"
        Else
          If objWebServer.LogFilePeriod = 2 Then
            iis_log_per = "Weekly"
          Else
            If objWebServer.LogFilePeriod =3 Then
              iis_log_per = "Monthly"
            End If
          End If
        End If
        iis_log_dir = objWebServer.LogFileDirectory
      End If
'----------------------------------   Home Dirtectory Tab   -------------------------------
      If objWebRootDir.HttpRedirect <> "" Then
        '
      Else
        strPath = objWebRootDir.Path
        strPath = Left(strPath, 2)
        iis_path = objWebRootDir.Path
        iis_dir_browsing =  objWebRootDir.EnableDirBrowsing
      End If
'-------------------------------------   Documents Tab   ----------------------------------
      If objWebRootDir.EnableDefaultDoc = False Then
        iis_def_doc = "False"
      Else
        iis_def_doc = objWebRootDir.DefaultDoc
      End If
      form_input = "iis_1^^^" & WebSiteID          & "^^^" _
                              & clean(iis_desc)    & "^^^" _
                              & iis_log_en         & "^^^" _
                              & clean(iis_log_dir) & "^^^" _
                              & iis_log_format     & "^^^" _
                              & iis_log_per        & "^^^" _
                              & clean(iis_path)    & "^^^" _
                              & iis_dir_browsing   & "^^^" _
                              & clean(iis_def_doc) & "^^^" _
                              & iis_sec_ip         & "^^^" _
                              & iis_sec_port       & "^^^"
      entry form_input,comment,objTextFile,oAdd,oComment
'----------------------------   Enumerating Virtual Directories   ------------------------
      VirDirCounter = 0
      For Each Item In objWebRootDir
        If Item.Class = "IIsWebVirtualDir" Then
          ArgVirtualDirectory = Item.Name
          Set objWebVirtualDir = GetObject("IIS://" & ArgPhysicalServer & "/w3svc/" & ArgSiteIndex & "/Root/" & ArgVirtualDirectory)
          iis_vd_name = Item.Name
          iis_vd_path = objWebVirtualDir.Path
          form_input = "iis_2^^^" & ArgSiteIndex       & "^^^" _
                                  & clean(iis_vd_name) & "^^^" _
                                  & clean(iis_vd_path) & "^^^"
          entry form_input,comment,objTextFile,oAdd,oComment
          form_input = ""
          VirDirCounter = VirDirCounter + 1
        End If
      Next
    End If
  Next '--> next for 1 to 255 sites
Else
'
' End of IIS = True
End If

If online = "n" Then
   objTextFile.Close
End If

end_time = Timer
elapsed_time = end_time - start_time
If verbose = "y" Then
  WScript.echo "Audit.vbs Execution Time: " & Int(elapsed_time) & " seconds."
End If

If online = "ie" Then
  ie_time = Timer
'----------------------   Create an IE instance to display results   ----------------
  Dim ie
  Set ie = CreateObject("InternetExplorer.Application")
  ie.navigate ie_form_page
  Do Until IE.readyState = 4 : WScript.sleep(200) : Loop
  If ie_visible = "y" Then
    ie.visible= True
  Else
    ie.visible = False
  End If
  Dim oUser
  Dim oPwd
  Dim oDoc
  Set oDoc = IE.document
  Set oAdd = oDoc.getElementById("add")
'--------------------------   Output UUID and Timestamp to IE   --------------------
  oAdd.value = oAdd.value + form_total + vbcrlf
  If ie_auto_submit = "y" Then
    IE.Document.All("submit").Click
    Do Until IE.readyState = 4 : WScript.sleep(2000) : Loop
  End If

  If ie_auto_close = "y" Then
    Do Until IE.readyState = 4 : WScript.sleep(5000) : Loop
    WScript.sleep(5000)
    ie.Quit
  End If

  end_time = Timer
  elapsed_time = end_time - ie_time
  If verbose = "y" Then
    WScript.echo "IE Execution Time: " & Int(elapsed_time) & " seconds."
  End If
End If 

'----------------------------------------------------------------------------------------
'-----------------------------   Submit Data To Web Site   ------------------------------
'----------------------------------------------------------------------------------------

If online = "yesxml" Then
   url = non_ie_page
   Set objHTTP = CreateObject("MSXML2.XMLHTTP")
   Call objHTTP.Open("POST", url, FALSE)
   objHTTP.setRequestHeader "Content-Type","application/x-www-form-urlencoded"
   objHTTP.Send "add=" + escape(Deconstruct(form_total + vbcrlf))
'   If verbose = "y" Then
'      WScript.Echo(objHTTP.ResponseText)
'   End If
End If

If online = "p" Then
  oIE.document.WriteLn "</div>"
End If

end_time = Timer
elapsed_time = end_time - start_time
If verbose = "y" Then
  WScript.echo "Total Execution Time: " & Int(elapsed_time) & " seconds."
  WScript.echo
  WScript.sleep(2500)
End If
' database.close conn

End Function
'========================================================================================
'=======================    END OF PRIMARY AUDIT SCAN FUNCTION    =======================
'========================================================================================


'----------------------------------------------------------------------------------------
'-------------------------------   Auxilliary Functions   -------------------------------
'----------------------------------------------------------------------------------------

'-----------------------------   String Cleanup Function   ------------------------------
Function Deconstruct(strIn)
  strOut = ""
  For x = 1 to Len(strIn)
    If Asc(Mid(strIn,x,1)) > 128 Then
      strOut = strOut & "&#" & Asc(Mid(strIn,x,1))
    Else
      strOut = strOut & Mid(strIn,x,1)
    End If
  Next

  Deconstruct = strOut
End Function

'---------------------------   Hard Disk Detection Function   ---------------------------
Function HostDrives(sHost)
Const LOCAL_DISK = 3
Dim Disks, Disk, aTmp(), i
Set Disks = objWMIService.ExecQuery ("Select * from Win32_LogicalDisk where DriveType=" & LOCAL_DISK)
ReDim aTmp(Disks.Count - 1)
i = -1
For Each Disk In Disks
   i = i + 1
   aTmp(i) = Disk.DeviceID
Next
HostDrives = aTmp
End Function

'-------------------------------   Partition ID Function   ------------------------------
Function DrivePartition(sHost, sDrive)
Dim Associator, Associators
Set Associators = objWMIService.ExecQuery ("Associators of {Win32_LogicalDisk.DeviceID=""" & sDrive & """} WHERE ResultClass=CIM_DiskPartition")
On Error Resume Next
For Each Associator In Associators
   DrivePartition = Associator.Name
   If Err.Number <>0 Then Err.Clear
Next
End Function

'------------------------------   Product Key Function   -------------------------------
Function GetKey(rpk)
Const rpkOffset=52:i=28
szPossibleChars="BCDFGHJKMPQRTVWXY2346789"
Do 'Rep1
  dwAccumulator=0 : j=14
  Do
    dwAccumulator=dwAccumulator*256
    dwAccumulator=rpk(j+rpkOffset)+dwAccumulator
    rpk(j+rpkOffset)=(dwAccumulator\24) And 255
    dwAccumulator=dwAccumulator Mod 24
    j=j-1
  Loop While j>=0
  i=i-1 :
  szProductKey=Mid(szPossibleChars,dwAccumulator+1,1)&szProductKey
  If (((29-i) Mod 6)=0) And (i<>-1) Then
    i=i-1 : szProductKey="-"&szProductKey
  End If
Loop While i>=0 'Goto Rep1
GetKey=szProductKey
End Function

'--------------------------------   Ping Check Function 1  -------------------------------
Function IsConnectible(sHost,iPings,iTO)
 If sHost = "." Then
   IsConnectible = True
 Else
   If iPings = "" Then iPings = 2
   If iTO = "" Then iTO = 750
    Set oShell = CreateObject("WScript.Shell")
    Set oExCmd = oShell.Exec("ping -n " & iPings & " -w " & iTO & " " & sHost)
    Select Case InStr(oExCmd.StdOut.Readall,"TTL=")
      Case 0 IsConnectible = False
      Case Else IsConnectible = True
    End Select
  End If
End Function

'------------------------------   W2K3 Software ID Function   ----------------------------
Function get_sku_2003(subkey)
  vers = Mid(subkey,4,2)
If vers = "11" Then vers_name = "Microsoft Office Professional Enterprise Edition 2003" End If
If vers = "12" Then vers_name = "Microsoft Office Standard Edition 2003" End If
If vers = "13" Then vers_name = "Microsoft Office Basic Edition 2003" End If
If vers = "14" Then vers_name = "Microsoft Windows SharePoint Services 2.0" End If
If vers = "15" Then vers_name = "Microsoft Office Access 2003" End If
If vers = "16" Then vers_name = "Microsoft Office Excel 2003" End If
If vers = "17" Then vers_name = "Microsoft Office FrontPage 2003" End If
If vers = "18" Then vers_name = "Microsoft Office PowerPoint 2003" End If
If vers = "19" Then vers_name = "Microsoft Office Publisher 2003" End If
If vers = "1A" Then vers_name = "Microsoft Office Outlook Professional 2003" End If
If vers = "1B" Then vers_name = "Microsoft Office Word 2003" End If
If vers = "1C" Then vers_name = "Microsoft Office Access 2003 Runtime" End If
If vers = "1E" Then vers_name = "Microsoft Office 2003 User Interface Pack" End If
If vers = "1F" Then vers_name = "Microsoft Office 2003 Proofing Tools" End If
If vers = "23" Then vers_name = "Microsoft Office 2003 Multilingual User Interface Pack" End If
If vers = "24" Then vers_name = "Microsoft Office 2003 Resource Kit" End If
If vers = "26" Then vers_name = "Microsoft Office XP Web Components" End If
If vers = "2E" Then vers_name = "Microsoft Office 2003 Research Service SDK" End If
If vers = "44" Then vers_name = "Microsoft Office InfoPath 2003" End If
If vers = "83" Then vers_name = "Microsoft Office 2003 HTML Viewer" End If
If vers = "92" Then vers_name = "Windows SharePoint Services 2.0 English Template Pack" End If
If vers = "93" Then vers_name = "Microsoft Office 2003 English Web Parts and Components" End If
If vers = "A1" Then vers_name = "Microsoft Office OneNote 2003" End If
If vers = "A4" Then vers_name = "Microsoft Office 2003 Web Components" End If
If vers = "A5" Then vers_name = "Microsoft SharePoint Migration Tool 2003" End If
If vers = "AA" Then vers_name = "Microsoft Office PowerPoint 2003 Presentation Broadcast" End If
If vers = "AB" Then vers_name = "Microsoft Office PowerPoint 2003 Template Pack 1" End If
If vers = "AC" Then vers_name = "Microsoft Office PowerPoint 2003 Template Pack 2" End If
If vers = "AD" Then vers_name = "Microsoft Office PowerPoint 2003 Template Pack 3" End If
If vers = "AE" Then vers_name = "Microsoft Organization Chart 2.0" End If
If vers = "CA" Then vers_name = "Microsoft Office Small Business Edition 2003" End If
If vers = "D0" Then vers_name = "Microsoft Office Access 2003 Developer Extensions" End If
If vers = "DC" Then vers_name = "Microsoft Office 2003 Smart Document SDK" End If
If vers = "E0" Then vers_name = "Microsoft Office Outlook Standard 2003" End If
If vers = "E3" Then vers_name = "Microsoft Office Professional Edition 2003 (with InfoPath 2003)" End If
If vers = "FF" Then vers_name = "Microsoft Office 2003 Edition Language Interface Pack" End If
If vers = "F8" Then vers_name = "Remove Hidden Data Tool" End If
If vers = "3B" Then vers_name = "Microsoft Office Project Professional 2003" End If
If vers = "32" Then vers_name = "Microsoft Office Project Server 2003" End If
If vers = "51" Then vers_name = "Microsoft Office Visio Professional 2003" End If
If vers = "52" Then vers_name = "Microsoft Office Visio Viewer 2003" End If
If vers = "53" Then vers_name = "Microsoft Office Visio Standard 2003" End If
If vers = "5E" Then vers_name = "Microsoft Office Visio 2003 Multilingual User Interface Pack" End If
If vers = "5F" Then vers_name = "Microsoft Visual Studio .NET Enterprise Architect 2003" End If
If vers = "60" Then vers_name = "Microsoft Visual Studio .NET Enterprise Developer 2003" End If
If vers = "61" Then vers_name = "Microsoft Visual Studio .NET Professional 2003" End If
If vers = "62" Then vers_name = "Microsoft Visual Basic .NET Standard 2003" End If
If vers = "63" Then vers_name = "Microsoft Visual C# .NET Standard 2003" End If
If vers = "64" Then vers_name = "Microsoft Visual C++ .NET Standard 2003" End If
If vers = "65" Then vers_name = "Microsoft Visual J# .NET Standard 2003" End If
get_sku_2003 = vers_name
End Function

'-------------------------------   XP Software ID Function   -------------------------------
Function get_sku_xp(value)
vers = Mid(value,4,2)
If vers = "11" Then vers_name = "Microsoft Office XP Professional" End If
If vers = "12" Then vers_name = "Microsoft Office XP Standard" End If
If vers = "13" Then vers_name = "Microsoft Office XP Small Business" End If
If vers = "14" Then vers_name = "Microsoft Office XP Web Server" End If
If vers = "15" Then vers_name = "Microsoft Access 2002" End If
If vers = "16" Then vers_name = "Microsoft Excel 2002" End If
If vers = "17" Then vers_name = "Microsoft FrontPage 2002" End If
If vers = "18" Then vers_name = "Microsoft PowerPoint 2002" End If
If vers = "19" Then vers_name = "Microsoft Publisher 2002" End If
If vers = "1A" Then vers_name = "Microsoft Outlook 2002" End If
If vers = "1B" Then vers_name = "Microsoft Word 2002" End If
If vers = "1C" Then vers_name = "Microsoft Access 2002 Runtime" End If
If vers = "1D" Then vers_name = "Microsoft FrontPage Server Extensions 2002" End If
If vers = "1E" Then vers_name = "Microsoft Office Multilingual User Interface Pack" End If
If vers = "1F" Then vers_name = "Microsoft Office Proofing Tools Kit" End If
If vers = "20" Then vers_name = "System Files Update" End If
If vers = "22" Then vers_name = "unused" End If
If vers = "23" Then vers_name = "Microsoft Office Multilingual User Interface Pack Wizard" End If
If vers = "24" Then vers_name = "Microsoft Office XP Resource Kit" End If
If vers = "25" Then vers_name = "Microsoft Office XP Resource Kit Tools (download from Web)" End If
If vers = "26" Then vers_name = "Microsoft Office Web Components" End If
If vers = "27" Then vers_name = "Microsoft Project 2002" End If
If vers = "28" Then vers_name = "Microsoft Office XP Professional with FrontPage" End If
If vers = "29" Then vers_name = "Microsoft Office XP Professional Subscription" End If
If vers = "2A" Then vers_name = "Microsoft Office XP Small Business Edition Subscription" End If
If vers = "2B" Then vers_name = "Microsoft Publisher 2002 Deluxe Edition" End If
If vers = "2F" Then vers_name = "Standalone IME (JPN Only)" End If
If vers = "30" Then vers_name = "Microsoft Office XP Media Content" End If
If vers = "31" Then vers_name = "Microsoft Project 2002 Web Client" End If
If vers = "32" Then vers_name = "Microsoft Project 2002 Web Server" End If
If vers = "33" Then vers_name = "Microsoft Office XP PIPC1 (Pre Installed PC) (JPN Only)" End If
If vers = "34" Then vers_name = "Microsoft Office XP PIPC2 (Pre Installed PC) (JPN Only)" End If
If vers = "35" Then vers_name = "Microsoft Office XP Media Content Deluxe" End If
If vers = "3A" Then vers_name = "Project 2002 Standard" End If
If vers = "3B" Then vers_name = "Project 2002 Professional" End If
If vers = "51" Then vers_name = "Microsoft Visio Professional 2002" End If
If vers = "5F" Then vers_name = "Microsoft Visual Studio .NET Enterprise Architect 2003" End If
If vers = "60" Then vers_name = "Microsoft Visual Studio .NET Enterprise Developer 2003" End If
If vers = "61" Then vers_name = "Microsoft Visual Studio .NET Professional 2003" End If
If vers = "62" Then vers_name = "Microsoft Visual Basic .NET Standard 2003" End If
If vers = "63" Then vers_name = "Microsoft Visual C# .NET Standard 2003" End If
If vers = "64" Then vers_name = "Microsoft Visual C++ .NET Standard 2003" End If
If vers = "65" Then vers_name = "Microsoft Visual J# .NET Standard 2003" End If
get_sku_xp = vers_name
End Function

'-------------------------------   Software Release Type Function   -------------------------------
Function get_release_type(value)
vers = Mid(value,2,1)
If vers = "0" Then release_type = "Any release before Beta 1" End If
If vers = "1" Then release_type = "Beta 1" End If
If vers = "2" Then release_type = "Beta 2" End If
If vers = "3" Then release_type = "RC0<BR/>" End If
If vers = "4" Then release_type = "RC1/OEM Preview Release" End If
If vers = "5" Then release_type = "Reserved - Not Defined by Microsoft" End If
If vers = "6" Then release_type = "Reserved - Not Defined by Microsoft" End If
If vers = "7" Then release_type = "Reserved - Not Defined by Microsoft" End If
If vers = "8" Then release_type = "Reserved - Not Defined by Microsoft" End If
If vers = "9" Then release_type = "RTM (first shipped version)" End If
If vers = "A" Then release_type = "SR1 (unused if the product code is not changed after RTM)" End If
If vers = "B" Then release_type = "SR2 (unused if the product code is not changed after RTM)" End If
If vers = "C" Then release_type = "SR3 (unused if the product code is not changed after RTM)" End If
get_release_type = release_type
End Function

'----------------------------   Software Edition Detect Function   ----------------------------
Function get_edition_type(value)
vers = Mid(value,3,1)
If vers = "0" Then release_type = "Enterprise" End If
If vers = "1" Then release_type = "Retail/OEM" End If
If vers = "2" Then release_type = "Trial" End If
get_edition_type = release_type
End Function

'--------------------------------   Result Cleanup Function   ---------------------------------
Function clean(value)
If IsNull(value) Then value = ""
'value = Replace(value, chr(34), "\'")
'value = Replace(value, chr(39), "\'")
value = Replace(value, vbCr, "")
value = Replace(value, vbLf, "")
'If Right(value, 1) = "\" Then
'  value = value + " "
'End If
clean = value
End Function

'-----------------------------   Crystal Reports CD Key Detect   ------------------------------
Function GetCrystalKey(rpk)
  GetCrystalKey = Mid(rpk,3,21)
End Function

'------------------------------------   NSlookup Function   -----------------------------------
Function NSlookup(sHost) '--> Both IP address and DNS name is allowed, Function will return the opposite
   Set oRE = New RegExp
   oRE.Pattern = "^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$"
   bInpIP = False
   If oRE.Test(sHost) Then
       bInpIP = True
   End If
   Set oShell = CreateObject("Wscript.Shell")
   Set oFS = CreateObject("Scripting.FileSystemObject")
   sTemp = oShell.ExpandEnvironmentStrings("%TEMP%")
   sTempFile = sTemp & "\" & oFS.GetTempName '--> Run NSLookup via Command Prompt, Dump results into a temp text file
    oShell.Run "%ComSpec% /c nslookup.exe " & sHost & " >" & sTempFile, 0, True  '--> Open the temp Text File and Read out the Data
   Set oTF = oFS.OpenTextFile(sTempFile) '--> Parse the text file
   Do While Not oTF.AtEndOfStream
       sLine = Trim(oTF.Readline)
       If LCase(Left(sLine, 5)) = "name:" Then
           sData = Trim(Mid(sLine, 6))
           If Not bInpIP Then '--> Next line will be IP address(es). Line can be prefixed with "Address:" or "Addresses":
               aLine = Split(oTF.Readline, ":")
               sData = Trim(aLine(1))
           End If
           Exit Do
       End If
   Loop
   oTF.Close '--> Close it
   oFS.DeleteFile sTempFile '--> Delete It
   If LCase(TypeName(sData)) = LCase("Empty") Then
       NSlookup = ""
   Else
       NSlookup = sData
   End If
End Function

'---------------------------   Active Process Detection Function   ---------------------------
Function HowMany()
  Dim Proc1,Proc2,Proc3
  Set Proc1 = GetObject("winmgmts:{impersonationLevel=impersonate}!\\.\root\cimv2")
  Set Proc2 = Proc1.ExecQuery("select * from win32_process" )
  HowMany=0
  For Each Proc3 In Proc2
    If LCase(Proc3.Caption) = "cscript.exe" Then
      HowMany=HowMany + 1
    End If
  Next
End Function

'-----------------------------   IE Output Formatting Subroutine   --------------------------
Sub entry(form_input, comment,objTextFile,oAdd,oComment)
If form_input <> "" Then
  If online = "n" Then
    objTextFile.WriteLine(form_input)
  End If
  If online = "ie" Or online = "yesxml" Then
    form_total = form_total + form_input + vbcrlf
    ' oAdd.value = oAdd.value + form_input + vbcrlf
    ' oComment.value = comment
  End If
End If
End Sub

'========================================================================================
'     =======================    END OF AUDIT SCRIPT CODE    =======================
'========================================================================================
