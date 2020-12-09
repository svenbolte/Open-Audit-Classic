'
'*
'* @version $Id: testemail.vbs  6th December 2007
'*
'* @author The Open Audit Developer Team
'* @objective Index Page for Open Audit.
'* @package open-audit (www.open-audit.org)
'* @copyright Copyright (C) open-audit.org All rights reserved.
'* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see ../gpl.txt
'* Open-Audit is free software. This version may have been modified pursuant
'* to the GNU General Public License, and as distributed it includes or
'* is derivative of works licensed under the GNU General Public License or
'* other free or open source software licenses.
'* See www.open-audit.org for further copyright notices and details.
'*
' 
this_audit_log = "audit_log.txt" 
this_config="audit.config"
'
dim filesys
Set filesys = CreateObject("Scripting.FileSystemObject")
'
If filesys.FileExists(this_config) then
'
sScriptPath=Left(WScript.ScriptFullName, InStrRev(WScript.ScriptFullName,"\"))
ExecuteGlobal CreateObject("Scripting.FileSystemObject").OpenTextFile(sScriptPath & this_config).ReadAll
'
'
On Error Resume Next
  Set objShell = WScript.CreateObject("WScript.Shell")
  this_folder = objShell.CurrentDirectory
  this_file = this_folder & "\" & this_audit_log
  wscript.echo "Open-Audit testing email using  Mail Server: " & email_server
  Set objEmail = CreateObject("CDO.Message")
  objEmail.From = email_from
  objEmail.To   = email_to
  objEmail.Sender   = email_sender
  objEmail.Subject = "Open-AudIT - Email Tester."
  objEmail.Textbody = "Email sent from" & email_from & " Via Mail Server " & email_server & " : " & vbCRLF & email_failed
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/sendusing") = 2
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpserver") = email_server
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = email_port
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpauthenticate") = email_auth
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/sendusername") = email_user_id
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/sendpassword") = email_user_pwd
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpusessl") = email_use_ssl
  objEmail.Configuration.Fields.Item ("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = email_timeout
  objEmail.Configuration.Fields.Update
  objEmail.AddAttachment  this_file
  objEmail.Send
    if Err.Number <> 0 then
      ' Possibly the error will come from the above scripting, as an error box, however here is a generic error, just in case  
      wscript.echo "Error sending email: " & Err.Description
      wscript.echo "Log file name: " & this_file
  else wscript.echo "Email sent sucessfully." end if
  Err.Clear
  else wscript.echo "Email not sent. Please check your settings." end if 