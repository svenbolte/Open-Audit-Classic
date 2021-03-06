; Script generated by the Inno Setup Script 6.2.0 Wizard.

#define MyAppName "Open-Audit Classic"
#define MyDateString GetDateTimeString('yyyy/mm/dd', '.', '');
#define MyAppPublisher "Open-Audit Classic"
#define MyAppURL "https://github.com/svenbolte/Open-Audit-Classic"
#define Inhalte "Apache 2.4.48x64-VC16, MySQLMariaDB 10.4.20x64, PHP/PEAR 8.0.8x64-thrsafe, phpMyAdmin 5.1.1x64, NMap 7.91, NPCap 1.50, Wordpress 5.7.2, WPKG 1.31*"

[Setup]
; NOTE: The value of AppId uniquely identifies this application.
; Do not use the same AppId value in installers for other applications.
; (To generate a new GUID, click Tools | Generate GUID inside the IDE.)
PrivilegesRequired=admin
AppID={{E3C99A13-491B-4DE8-A06B-E81AA391561B}
AppName={#MyAppName}
AppVersion={#MyDateString}
AppVerName={#MyAppName}
AppPublisher={#MyAppPublisher}
AppPublisherURL={#MyAppURL}
AppSupportURL={#MyAppURL}
AppUpdatesURL={#MyAppURL}
LicenseFile=C:\temp\xampplite\readme.txt
DefaultDirName={commonpf}\xampplite
DisableDirPage=yes
DefaultGroupName={#MyAppName}
DisableProgramGroupPage=yes
OutputDir=c:\temp\
OutputBaseFilename=openaudit-cl-setup
SetupIconFile=C:\temp\xampplite\openaudit_logo.ico
Compression=lzma2/Ultra
SolidCompression=true
WizardImageFile=C:\temp\xampplite\openaudit_logolarge.bmp
WizardSmallImageFile=C:\temp\xampplite\openaudit_logo.bmp
AppCopyright={#MyAppPublisher}
ShowLanguageDialog=no
InternalCompressLevel=Ultra
AppComments={#MyDateString}-{#Inhalte}
VersionInfoDescription={#MyDateString}-{#Inhalte}
UninstallDisplayIcon={app}\openaudit_logo.ico

[Languages]
Name: "german"; MessagesFile: "compiler:Languages\German.isl"

[Tasks]
Name: "desktopicon"; Description: "Desktop-Verkn�pfungen erstellen"; GroupDescription: "{cm:AdditionalIcons}"
Name: "Aufgabepcscan"; Description: "Importieren der PC-Scan Aufgabe"; Flags: unchecked
Name: "AufgabeNMAPScan"; Description: "Aufgabe f�r NMAP-Scan importieren"; Flags: unchecked

[Files]
Source: "C:\temp\xampplite\*"; DestDir: "{app}"; Flags: ignoreversion recursesubdirs createallsubdirs

[Icons]
Name: "{group}\Open-Audit Konsole"; Filename: "cmd.exe"; WorkingDir: {app}\htdocs\openaudit\scripts; 
Name: "{group}\WPKG-Softwareverteilung"; Filename: "{app}\wpkg\"; 
Name: "{group}\Open-Audit Datenbankersteinrichtung"; Filename: "http://localhost:888/openaudit/setup.php"; Tasks: desktopicon;  
Name: "{commondesktop}\Open-Audit Konsole"; Filename: "cmd.exe"; Tasks: desktopicon; WorkingDir: {app}\htdocs\openaudit\scripts; 
Name: "{commondesktop}\Open-Audit Oberfl�che"; Filename: "http://localhost:888/openaudit"; Tasks: desktopicon;  
Name: "{commondesktop}\PC-IP-Listendatei �ndern"; Filename: "{app}\htdocs\openaudit\scripts\pc_list_file.txt"; Tasks: desktopicon;

[Run]
Filename: "{sys}schtasks.exe"; Parameters: "/create /XML ""C:\Program Files (x86)\xampplite\htdocs\openaudit\all-tools-scripts\jobsundbatches\Open-Audit PC Inventar taeglich.xml"" /TN Openaudit-PCScan"; Flags: postinstall runasoriginaluser; Description: "PC Scan Aufgabe importieren"; Tasks: Aufgabepcscan
Filename: "{sys}schtasks.exe"; Parameters: "/create /XML ""C:\Program Files (x86)\xampplite\htdocs\openaudit\all-tools-scripts\jobsundbatches\Open-Audit NMAP Inventar taeglich.xml"" /TN Openaudit-NMAPScan"; Flags: postinstall runasoriginaluser; Description: "NMAP Scan Aufgabe importieren"; Tasks: AufgabeNMAPScan
Filename: "{app}\vcruntimes\openaudit-vc2019_redist.x64.exe"; Parameters: "/install /quiet /norestart"; Flags: waituntilterminated shellexec; StatusMsg: "Installing VC2019/X64 Redist for Apache"; Check: VC2017RedistNeedsInstall
Filename: "{app}\apache\apache_installservice-win10.cmd"; Flags: shellexec postinstall runascurrentuser; Description: "Apache ab Win10 als Dienst und starten"
Filename: "{app}\mysql\mysql_installservice-win10.cmd"; Flags: shellexec postinstall runascurrentuser; Description: "MySQL ab Win10 als Dienst und starten"
Filename: "{app}\nmap\npcap-1.50.exe"; Flags: shellexec postinstall runascurrentuser; Description: "f�r NMAP ben�tigtes NPCap installieren"
Filename: "{app}\vcruntimes\openaudit-vc2013_redist_x86_nmap.exe"; Parameters: "/q /norestart"; Flags: waituntilterminated shellexec postinstall; Description: "VC Runtime 2013 x86 f�r NMAP installieren"; StatusMsg: "Installing VC2013/x86 Redist for NMAP"; Check: VC2013RedistNeedsInstall

[UninstallRun]
Filename: "{app}\apache\apache_uninstallservice-win10.cmd"; Flags: shellexec; 
Filename: "{app}\mysql\mysql_uninstallservice-win10.cmd"; Flags: shellexec; 

[Code]
function VC2017RedistNeedsInstall: Boolean;
var 
  Version: String;
begin
  if (RegQueryStringValue(HKEY_LOCAL_MACHINE, 'SOFTWARE\WOW6432Node\Microsoft\VisualStudio\14.0\VC\Runtimes\X64', 'Version', Version)) then
  begin
    // Is the installed version at least 14.14 ? 
    Log('VC Redist Version check : found ' + Version);
    Result := (CompareStr(Version, 'v14.14.26429.03')<0);
  end
  else 
  begin
    // Not even an old version installed
    Result := True;
  end;
end;

function VC2013RedistNeedsInstall: Boolean;
var 
  Version: String;
begin
  if (RegQueryStringValue(HKEY_LOCAL_MACHINE, 'SOFTWARE\WOW6432Node\Microsoft\VisualStudio\12.0\VC\Runtimes\X86', 'Version', Version)) then
  begin
    // Is the installed version at least 12.0 ? 
    Log('VC Redist Version check : found ' + Version);
    Result := (CompareStr(Version, 'v12.0.40664.00')<0);
  end
  else 
  begin
    // Not even an old version installed
    Result := True;
  end;
end;