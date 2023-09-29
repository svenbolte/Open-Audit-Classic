#define MyAppName "Open-Audit Classic"
#define MyDateString GetDateTimeString('yyyy/mm/dd', '.', '');
#define MyAppPublisher "OpenAudit Classic GPL3 Projekt"
#define MyAppURL "https://github.com/svenbolte/Open-Audit-Classic"
#define Inhalte "Apache 2.4.57x64-VS17, MySQLMariaDB 10.6.15x64, PHP 8.2.11x64-thsafe, phpMyAdmin 5.2.1x64, NMap 7.95, NPCap 1.76(für nmap), Wordpress 6.3.1, VC17Runtimes 09/23"

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
Name: "{group}\OpenAudit cl Oberfläche lokal"; Filename: "http://localhost:888/openaudit"
Name: "{group}\OpenAudit cl Konsole"; Filename: "cmd.exe"; WorkingDir: "{app}\htdocs\openaudit\scripts"; Comment: "als angemeldeter User"
Name: "{group}\OpenAudit cl Konsole (Admin)"; Filename: "%windir%\system32\cmd.exe"; Parameters: "/k pushd ""{app}\htdocs\openaudit\scripts\"""; WorkingDir: "{app}\htdocs\openaudit\scripts"; IconFilename: "{app}\openaudit_logo.ico"; Comment: "mit elevated rights"
Name: "{group}\OpenAudit cl Explorer (Ordner)"; Filename: "%windir%\explorer.exe"; Parameters: "/e,""C:\Program Files (x86)\xampplite\htdocs\openaudit\scripts"" "; WorkingDir: "{app}\htdocs\openaudit\scripts"; IconFilename: "{app}\openaudit_logo.ico"; Comment: "Ordner mit scripts öffnen"
Name: "{group}\PC-IP-Listfile.txt manuell ändern"; Filename: "{app}\htdocs\openaudit\scripts\pc_list_file.txt";  Comment: "nur im Notfall, lässt sich besser über Oberfläche erzeugen"
Name: "{group}\ZENMap Gui für NMap"; Filename: "{app}\nmap\zenmap\bin\pythonw.exe"; Parameters: "-c ""from zenmapGUI.App import run;run()""";
Name: "{commondesktop}\OpenAudit cl Oberfläche"; Filename: "http://{code:GetComputerName}:888/openaudit"; Tasks: desktopicon; Comment: "Netzwerkverknüpfung zum Open-Audit-Server"
Name: "{commondesktop}\OpenAudit cl Konsole"; Filename: "cmd.exe"; WorkingDir: "{app}\htdocs\openaudit\scripts"; Comment: "als angemeldeter User"; Tasks: desktopicon
Name: "{commondesktop}\PC-List-File erzeugen"; Filename: "http://{code:GetComputerName}:888/openaudit/export-ipliste-4-openaudit.php"; Tasks: desktopicon; Comment: "Netzwerke eingeben und Liste erzeugen"
Name: "{commondesktop}\Aufgabenplanung"; Filename: "%windir%\system32\taskschd.msc"; Parameters: "/s"; Tasks: desktopicon; Comment: "OpenAudit Aufgaben auf Domain-admin umstellen: PC-Scan und optional NMAP Scan bearbeiten"

[Run]
Filename: "{sys}\schtasks.exe"; Parameters: "/create /RU SYSTEM /XML ""{app}\htdocs\openaudit\all-tools-scripts\jobsundbatches\Open-Audit PC Inventar taeglich.xml"" /TN Openaudit-PCScan"; Flags: runascurrentuser; Description: "PC Scan Aufgabe importieren"; Tasks: Aufgabepcscan
Filename: "{sys}\schtasks.exe"; Parameters: "/create /RU SYSTEM /XML ""{app}\htdocs\openaudit\all-tools-scripts\jobsundbatches\Open-Audit NMAP Inventar taeglich.xml"" /TN Openaudit-NMAPScan"; Flags: runascurrentuser; Description: "NMAP Scan Aufgabe importieren"; Tasks: AufgabeNMAPScan
Filename: "{app}\vcruntimes\vc_redist.x64.exe"; Parameters: "/install /quiet /norestart"; Flags: waituntilterminated shellexec; StatusMsg: "Installing VC2019/X64 Redist for Apache"; Check: VC2017RedistNeedsInstall
Filename: "{app}\apache\apache_installservice-win10.cmd"; Flags: shellexec postinstall runascurrentuser; Description: "Apache ab Win10 als Dienst und starten"
Filename: "{app}\mysql\mysql_installservice-win10.cmd"; Flags: shellexec postinstall runascurrentuser; Description: "MySQL ab Win10 als Dienst und starten"
Filename: "{app}\nmap\npcap-1.76.exe"; Flags: shellexec postinstall runascurrentuser; Description: "für NMAP benötigtes NPCap installieren"
Filename: "{app}\vcruntimes\vc_redist.x86.exe"; Parameters: "/q /norestart"; Flags: waituntilterminated shellexec postinstall; Description: "VC Runtime 2019 x86 für NMAP installieren"; StatusMsg: "Installing VC2019/x86 Redist for NMAP"; Check: VC2013RedistNeedsInstall

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
// Rechnernamen, auf dem installiert wird, herausfinden
function GetComputerName(Param: string): string;
begin
  Result := GetComputerNameString;
end; 

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