== OPENAudit Classic ==

Open-Audit Classic ist eine quelloffene Software, die auf einem Windows Server betrieben, per WMI-Anfragen alle Windows PCs und Server mit
ihrer Hardware und Software und Konfiguration erfasst und in einer MySQL-Datenbank speichert.
Die Oberfläche ist komplett in PHP geschrieben und liegt in dieser Form vollumfänglich als Quellcode im Unterordner htdoc/openaudit.

Die notwendigen Basiskomponenten sind ebenfalls Quelloffen und in der Form als Windows-kompilierte Dateien im XAMPP for Windows Projekt
herunterladbar.

https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/

Aus dem Projekt werden nur Apache, Mysql (MariaDB), PHP und PHPMyadmin benötigt.

Für das Inventarisieren von SNMP- und Netzwerkgeräten (Switches, Kameras, Webserver, IRMCs) wird das ebenfalls quelloffene NMAP mit WinPCAP benötigt
Der Ordner htdocs/ lässt sich auch optimal für eine Entwicklerinstallation von Wordpress verwenden oder für ein kleines Intranet.

Ein optionales Setup-Paket lässt sich mit Innosetup erstellen. Es fügt diese Komponenten zu einem installierbaren Paket zusammen.


= Entstehungsgeschichte =

in den 2000er Jahren wurde Open-Audit als GPLv3 lizensierte Open Source Software erstellt und war als Quellcode verfügbar.
Seit Weggang des Haupt Entwicklers wird das Produkt unter einer AGPL-Lizenz weiterentwickelt.
Weil damit Teile der Software Closed Source sind aufgrund einer grundlegenden Neuprogrammierung wurde die Weiterentwicklung eigener Vorstellungen damit komplizierter.
Daher habe ich mich entschlossen, auf Basis der Open-Audit Stands, der noch unter der echten GPL-Lizenz stammt, diesen zu "forken" und weiter zu entwickeln.
Das Resultat ist "Open-Audit Classic". 

Berücksichtigt wurden Anpassungen auf neue PHP-Versionen, MySQL und Apache, häufig benutzte zusätzliche Auswertungen, Erweiterungen in der WMI-Erkennung und vieles mehr
Auch die Integration von NMAP und WinPCap wurde auf den aktuellen Stand gebracht.

Lizenz: GPLv3 --> GPL.txt im htdocs/openaudit Verzeichnis


= Entwicklung: =

Der Projekt-Fork ist unter Github zu finden. Beiträge und Weiterentwicklung jederzeit willkommen.


= Installation =
Mit dem beiliegenden Innosetup Skript und Innosetup ein Setup erzeugen oder von Hand wie folgt installieren:

Voraussetzungen: Apache benötigt die Visual C++ Runtimes 2015-19 (64-Bit) installiert. Diese kann bei Microsoft heruntergeladen werden.
Auf einem Windows Server ab Version 2012 R2 :
* xampp installieren
* nmap und winpcap installieren 
* Open-Audit Projektdateien unter htdocs/ kopieren
* Datenbankersteinrichtung: http://localhost:888/openaudit/setup.php aufrufen und den Anweisungen folgen
* htdocs/openaudit/scripts/pc-list-file.txt --> alle IP-Adressen aller Netze, die gescannt werden sollen, hier rein. Liste am Besten mit Excel erstellen
* htdocs/openaudit/all-tools-scripts/jobs und batches: Aufgabe in den Taskplaer importieren
* ./nmap/npcap09995.exe installieren auf dem Server (NMAP benötigt NPCAP auf der Netzwerkkarte des Servers installiert)

= Absichern =
Die open-Audit-Oberfläche kann mit einem Login versehen werden, dazu in der Oberfläche http://localhost:888/openaudit auf
 Administrator/Konfiguration/Security das Oberflächenpasswort anschalten und Zugangsdaten eingeben
PHPMyadmin sichern:
./phpmyadmin/config.inc.php öffnen und die Zeile $cfg['Servers'][$i]['auth_type'] = 'config';
 statt config dort http setzen


= Werkzeuge = 

--> htdocs/openaudit/all-tools-scripts
	Offline Scan zum Inventarisieren ohne Netzwerk (howto.txt)
	nmap-zweigstelle: Invenatisieren der IP-Geräte in Zweignetzen
	jobs und batches: Ausführbare Aufgaben für täglichen Scan am Server  (audit und nmap)

 = Benötigte freie IP-Ports auf dem Server = 
 
888 <- HTTP Oberfläche Webserver. Die Software sollte nur im Intranet verwendet werden. Dafür reicht http aus.
3306 <- Mysql Datenbank Port

= Post-Installation (optional, nur im Fehlerfall) =
--> Stamm-Ordner ist: C:\Program Files (x86)\xampplite\htdocs\openaudit
Nach der Installation auf Server 2016 müssen Apache und Mysql ggf. üder die CMD-Dateien 
./apache/apache_installservice-win10.cmd
./mysql/mysqli_installservice-win10.cmd
von Hand (Ausführen als Administrator) initialisiert werden.

Danach die "Datenbankersteinrichtung" vom Desktop starten und durchklicken.
Die "pc_list_File.txt" auf dem Desktop bearbeiten und alle IP-Adressen aller Netze untereinander dort hin kopieren
   (Man kann die Listen leicht mit Excel erstellen durch nach unten ziehen)
./scripts/audit.config bearbeiten und in der NMAP Sektion das richtige Supnetz eintragen
Die Geplanten Tasks (Aufgaben) für Scan und nmap scan installieren über die Aufgabenplanung.
Aufgabe ausführen.

= Mögliche Bausteine =

Openaudit
XAMPP-Komponenten:
	Apache
	PHP
	Mysql (MariaDB Open Source)
	phpMyAdmin
NMap mit NPCap
Wordpress (Oberfläche für Intranet-Blogseite)


= Hinweise zu Einstellungen und Befehlszeilen-Optionen: =

Die spitzen Klammern bitte nicht mit eingeben. sie sind nur Platzhalter für optionale Werte darin.
Open-Audit Console aufrufen (Desktop Verknüpfung)

cscript audit.vbs <ip> <domain\user> <passwort>
	^ aus der Konsole aufrufen für Einzelscan eines Windows-PCs

nmap-scan mit:    cscript nmap.vbs
	^ scannt alle Geräte von 1 bis 254

Einzelnes Gerät mit NMAP scannen (NMAP funktioniert nur jeweils für das Teilnetz, wo das Script aufgerufen wird
und WinPCAP installiert ist (im NMAP-Verzeichnis enthalten).

Möchte man auf einem Client-PC in der
Zweigstelle NMAP einsammeln, benötigt man den NMAP-Ordner an der gleichen Stelle wie auf dem Server,
zusätzlich den Openaudit Scripts-Ordner. Die Audit-config muss angepasst werden (Subnetz und Server-IP-Adresse).
mit:   cscript nmap.vbs <nr>
	^ <nr> ist die letzte Ziffer der IP (Hostnummer)


= Vorbereitete Skripte/Clients/Offline-Scan: =

Es gibt 2 Möglichkeiten, PCs zu erfassen, die nicht per WMI erreichbar sind:
* Client installieren auf dem PC und als Aufgabe täglich scannen lassen (oa-clientside-scan)
* Offline-Scan Ordner auf einen Stick, daten sammeln lassen und Textdatei importieren (offline-scan)
Howto-Anleitungen im jeweiligen Unterordner unter /all-tools-scripts

= Hinweise zu Wordpress: =

Benutzt leere Mysql-Datenbank mit User "openaudit"


= Kennwörter für Installation und Programmierung: =

mysql: root / flocke   openaudit / flocke

Zur Ersteinrichtung und zum Löschen der vorhandenen Datenbank: Admin / Delete/Create Database
MySQL administrieren: PHPMyAdmin aufrufen aus dem Menü


= Mehrere Datenbanken: =

Mit http://localhost:888/openaudit/setup.php einrichten, dabei den Datenbanknamen verändern - zB openaudit10892
Datenbanknamen müssen mit openaudit beginnen. Über die Oberfläche kann man dann jeweils die Defaultdatenbank für
Einsicht und den automatischen Scan festlegen (immer nur eine Standarddatenbank jeweils wählbar)


= WMI-Probleme lösen: =
Openaudit - WMI: Access denied Fehler beheben: bei Arbeitsguppe: lokalen User, mit dem inventarisitert wird, zufügen mit allen Rechten bei Step1 und Step2

Open Dcomcnfg
Expand Component Service -> Computers -> My computer
Go to the properties of My Computer
Select the COM Security Tab
Click on "Edit Limits" under Access Permissions, and ensure "Everyone" user group has "Local Access" and "Remote Access" permission.
Click on the "Edit Limit" for the launch and activation permissions, and ensure "Everyone" user group has "Local Activation" and "Local Launch" permission.
Highlight "DCOM Config" node, and right click "Windows Management and Instruments", and click Properties.
<Please add the steps to check Launch and Activation Permissions, Access Permissions, Configuration Permissions based on the default of Windows Server 2008>
 
Open WMImgmt.msc
Go to the Properties of WMI Control
Go to the Security Tab
Select "Root" and open "Security"
Ensure "Authenticated Users" has "Execute Methods", "Provider Right" and "Enable Account" right; ensure Administrators has all permission.
 
Click Start, click Run, type gpedit.msc, and then click OK.
Under Local Computer Policy, expand Computer Configuration, and then expand Windows Settings.
Expand Security Settings, expand Local Policies, and then click User Rights Assignment.
Verify that the SERVICE account is specifically granted Impersonate a client after authentication rights. 
