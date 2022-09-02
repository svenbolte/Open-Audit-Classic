== OpenAudit Classic ==

OpenAudit Classic ist eine quelloffene Software, die auf einem Windows Server betrieben, per WMI-Anfragen alle Windows PCs und Server mit
ihrer Hardware und Software und Konfiguration erfasst und in einer MySQL-Datenbank speichert.
Die Oberfläche ist komplett in PHP geschrieben und liegt in dieser Form vollumfänglich als Quellcode im Unterordner htdocs/openaudit.


= Wichtig! Schnellstart nach Installation =

1) Produkt installieren (Achtung: wenn das Setup einen Neustart verlangt, mit "Nein" antworten und Setup erneut starten).
2) PC-List-File erzeugen (Verknüpfung auf dem Desktop) aufrufen, das oder die Netzwerke kommagetrennt eingeben und abschicken
3) OpenAudit Explorer (Ordner) aus der Startmenügruppe Openaudit öffnen und in der audit.conf das Netzwerk 2x unter der NMAP Sektion eintragen (einmal mit führenden Nullen)
4) Windows Aufgabenplanung (Verknüpfung auf dem Desktop) öffnen, im openaudit pc-scan und nmap-scan Benutzer und Domäne eintragen, dann Kennwort und abspeichern
5) Windows Aufgabe(n) starten


= Entstehungsgeschichte =

in den 2000er Jahren wurde OpenAudit als GPLv3 lizensierte Open Source Software erstellt und war als Quellcode verfügbar.
Seit Weggang des Haupt-Entwicklers wird das Produkt unter einer AGPL-Lizenz weiterentwickelt.
Weil damit Teile der Software Closed Source sind und aufgrund einer grundlegenden Neuprogrammierung wurde die Weiterentwicklung eigener Vorstellungen komplizierter.
Daher habe ich mich entschlossen, auf Basis der OpenAudit Stands, der noch unter der echten GPL3-Lizenz stammt, diesen weiter zu entwickeln.
Das Resultat ist "OpenAudit Classic". 

Berücksichtigt wurden Anpassungen auf neue PHP-Versionen, MySQL und Apache, häufig benutzte zusätzliche Auswertungen, Erweiterungen in der WMI-Erkennung und vieles mehr
Auch die Integration von NMAP und WinPCap wurde auf den aktuellen Stand gebracht.

Lizenz: GPLv3 --> GPL.txt im htdocs/openaudit Verzeichnis


= Entwicklung =

Der Projekt-Fork ist unter Github zu finden. Beiträge und Weiterentwicklung jederzeit willkommen.


= Setup selbst erstellen =

Die notwendigen Basiskomponenten sind ebenfalls Quelloffen und in der Form als Windows-kompilierte Dateien im XAMPP for Windows Projekt
herunterladbar.

https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/

Aus dem Projekt werden nur Apache, Mysql (MariaDB), PHP und PHPMyadmin benötigt.

Für das Inventarisieren von SNMP- und Netzwerkgeräten (Switches, Kameras, Webserver, IRMCs) wird das ebenfalls quelloffene NMAP mit WinPCAP benötigt
Für eine auch häufig benötigte, einfache Softwareverteilung ist WPKG leicht zu implementieren.
Der Ordner htdocs/ lässt sich auch optimal für eine Entwicklerinstallation von Wordpress verwenden oder für ein kleines Intranet.

Ein optionales Setup-Paket lässt sich mit Innosetup erstellen. Es fügt diese Komponenten zu einem installierbaren Paket zusammen.
