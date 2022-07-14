#define MyAppName "Open-Audit Classic"
#define MyDateString GetDateTimeString('yyyy/mm/dd', '.', '');
#define MyAppPublisher "Open-Audit Classic"
#define MyAppURL "https://github.com/svenbolte/Open-Audit-Classic"
#define Inhalte "Apache 2.4.54x64-VC16, MySQLMariaDB 10.4.25x64, PHP 8.0.21x64-thsafe, phpMyAdmin 5.2.0x64, NMap 7.92, NPCap 1.60, Wordpress 6.0.1, VCRuntimes Juni2022"

[Setup]
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
WizardImageFile=C:\temp\xampplite\openaudit_logo.bmp
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
Name: "desktopicon"; Description: "Desktop-Verknüpfungen erstellen"; GroupDescription: "{cm:AdditionalIcons}";
Name: "Aufgabepcscan"; Description: "P: Importieren der PC-Scan Aufgabe"; Flags: checkedonce;
Name: "AufgabeNMAPScan"; Description: "N: Aufgabe für NMAP-Scan importieren"; Flags: unchecked;

[Dirs]
Name: {app}; Permissions: users-full

[Files]
Source: "C:\temp\xampplite\*"; DestDir: "{app}"; Components: mitwordpress; Flags: ignoreversion recursesubdirs createallsubdirs
Source: "C:\temp\xampplite\*"; DestDir: "{app}"; Components: nuropenaudit; Excludes: "wordpress"; Flags: ignoreversion recursesubdirs createallsubdirs

[Icons]
Name: "{group}\Open-Audit Konsole"; Filename: "cmd.exe"; WorkingDir: "{app}\htdocs\openaudit\scripts"; Comment: "als angemeldeter User"
Name: "{group}\WPKG-Softwareverteilung"; Filename: "{app}\wpkg\"
Name: "{group}\ZENMap Gui für NMap"; Filename: "{app}\nmap\zenmap.exe"
Name: "{group}\Open-Audit Konsole (Admin)"; Filename: "%windir%\system32\cmd.exe /k pushd {app}\htdocs\openaudit\scripts\"; WorkingDir: "{app}\htdocs\openaudit\scripts"; IconFilename: "{app}\openaudit_logo.ico"; Comment: "mit elevated rights"
Name: "{commondesktop}\Open-Audit Konsole"; Filename: "cmd.exe"; WorkingDir: "{app}\htdocs\openaudit\scripts"; Comment: "als angemeldeter User"; Tasks: desktopicon
Name: "{commondesktop}\Open-Audit Oberfläche"; Filename: "http://localhost:888/openaudit"; Tasks: desktopicon
Name: "{commondesktop}\PC-IP-Listendatei ändern"; Filename: "{app}\htdocs\openaudit\scripts\pc_list_file.txt"; Tasks: desktopicon

[Run]
Filename: "{sys}\schtasks.exe"; Parameters: "/create /RU SYSTEM /XML ""{app}\htdocs\openaudit\all-tools-scripts\jobsundbatches\Open-Audit PC Inventar taeglich.xml"" /TN Openaudit-PCScan"; Flags: runascurrentuser; Description: "PC Scan Aufgabe importieren"; Tasks: Aufgabepcscan
Filename: "{sys}\schtasks.exe"; Parameters: "/create /RU SYSTEM /XML ""{app}\htdocs\openaudit\all-tools-scripts\jobsundbatches\Open-Audit NMAP Inventar taeglich.xml"" /TN Openaudit-NMAPScan"; Flags: runascurrentuser; Description: "NMAP Scan Aufgabe importieren"; Tasks: AufgabeNMAPScan
Filename: "{app}\vcruntimes\openaudit-vc2019_redist.x64.exe"; Parameters: "/install /quiet /norestart"; Flags: waituntilterminated shellexec; StatusMsg: "Installing VC2019/X64 Redist for Apache"; Check: VC2017RedistNeedsInstall
Filename: "{app}\apache\apache_installservice-win10.cmd"; Flags: shellexec postinstall runascurrentuser; Description: "Apache ab Win10 als Dienst und starten"
Filename: "{app}\mysql\mysql_installservice-win10.cmd"; Flags: shellexec postinstall runascurrentuser; Description: "MySQL ab Win10 als Dienst und starten"
Filename: "{app}\nmap\npcap-1.60.exe"; Flags: shellexec postinstall runascurrentuser; Description: "für NMAP benötigtes NPCap installieren"
Filename: "{app}\vcruntimes\openaudit-vc2019_redist.x86.exe"; Parameters: "/q /norestart"; Flags: waituntilterminated shellexec postinstall; Description: "VC Runtime 2019 x86 für NMAP installieren"; StatusMsg: "Installing VC2019/x86 Redist for NMAP"; Check: VC2013RedistNeedsInstall

[Types]
Name: typical; Description: "Typical"; Flags: iscustom;
Name: custom; Description: "Custom";

[Components]
Name: nuropenaudit; Description: Nur Openaudit installieren; ExtraDiskSpaceRequired: 180000; Types: typical; Flags:exclusive;
Name: mitwordpress; Description: Openaudit und Wordpress für Intranet installieren; ExtraDiskSpaceRequired: 200000; Types: custom; Flags:exclusive;

[UninstallRun]
Filename: "{app}\apache\apache_uninstallservice-win10.cmd"; Flags: shellexec; RunOnceId: "DELAPACHE"
Filename: "{app}\mysql\mysql_uninstallservice-win10.cmd"; Flags: shellexec; RunOnceId: "DELMYSQL"

[Code]
function VC2017RedistNeedsInstall: Boolean;
var 
  Version: String;
begin
  if (RegQueryStringValue(HKEY_LOCAL_MACHINE, 'SOFTWARE\WOW6432Node\Microsoft\VisualStudio\14.0\VC\Runtimes\X64', 'Version', Version)) then
  begin
    // Is the installed version at least 14.29 ? 
    Log('VC Redist Version check : found ' + Version);
    Result := (CompareStr(Version, 'v14.29.30133.0')<0);
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
  if (RegQueryStringValue(HKEY_LOCAL_MACHINE, 'SOFTWARE\WOW6432Node\Microsoft\VisualStudio\14.0\VC\Runtimes\X86', 'Version', Version)) then
  begin
    // Is the installed version at least 14.29 ? 
    Log('VC Redist Version check : found ' + Version);
    Result := (CompareStr(Version, 'v14.29.30133.0')<0);
  end
  else 
  begin
    // Not even an old version installed
    Result := True;
  end;
end;