<?php
/**********************************************************************************************************
Recent Changes:

[Edoardo]	30/01/2008	Added Chassis, Mobo and Onboard devices items in yhe Hardware menu
						Added Scheduled Tasks, Env. vars, Event Logs. IP routes and Pagefile items in the OS settings menu
[Edoardo]	13/04/2008	Added the Security - Automatic Updating menu item
[Edoardo]	23/05/2008	Added the Queries - All MS Windows local Administrators menu item
[Edoardo]	06/06/2008	Added the Queries - All Mapped drives menu item
[Edoardo]	07/06/2008	Changed some Hardware and OS Settings icons	
[Edoardo]	13/02/2009	Moved the All partitions menu item from Statistics to Queries
[Edoardo]	12/03/2009	Fixed the Queries - All mapped drives menu item - Fix by Michalak
[Edoardo]	10/10/2009	Added the Other items - All modems menu item
[Edoardo]	10/10/2009	Fixed Security - Firewall menu to include Windows Firewall system settings
[Edoardo]	22/05/2010	Added the Statistics - Printer model menu item
[Edoardo]	28/05/2010	Added the Queries - All Hard Disks menu item
	
					
**********************************************************************************************************/
if(!isset($name)) $name = "";
$menue_array = array(

  "machine" => array(
      "10" => array("name"=>"Hardware",
                    "link"=>"system.php?pc=$pc&amp;view=hardware",
                    "image"=>"images/printer.png",
                    "class"=>"menuparent",
                    "childs"=>array("05"=>array("name"=>"All", "link"=>"system.php?pc=$pc&amp;view=hardware", "image"=>"images/statistics.png", "title"=>"",),
                                    "06"=>array("name"=>"Chassis", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=chassis", "image"=>"images/harddisk.png", "title"=>"",),
                                    "07"=>array("name"=>"Motherboard", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=motherboard", "image"=>"images/partition.png", "title"=>"",),
                                    "08"=>array("name"=>"Onboard Devices", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=onboard_device", "image"=>"images/scsi.png", "title"=>"",),
                                    "10"=>array("name"=>"Fixed Disks", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=hard_drive", "image"=>"images/harddisk.png", "title"=>"",),
                                    "20"=>array("name"=>"Partitions", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=partition", "image"=>"images/partition.png", "title"=>"",),
                                    "30"=>array("name"=>"SCSI Controller", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=scsi_controller", "image"=>"images/scsi.png", "title"=>"",),
                                    "40"=>array("name"=>"Optical Drive", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=optical_drive", "image"=>"images/optical.png", "title"=>"",),
                                    "50"=>array("name"=>"Floppy Drive", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=floppy", "image"=>"images/floppy.png", "title"=>"",),
                                    "60"=>array("name"=>"Tape Drive", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=tape_drive", "image"=>"images/tape.png", "title"=>"",),
                                    "70"=>array("name"=>"Processor", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=processor", "image"=>"images/processor.png", "title"=>"",),
                                    "80"=>array("name"=>"Bios", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=bios", "image"=>"images/bios.png", "title"=>"",),
                                    "90"=>array("name"=>"Memory", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=memory", "image"=>"images/memory.png", "title"=>"",),
                                    "100"=>array("name"=>"Network Card", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=network_card", "image"=>"images/network_device.png", "title"=>"",),
                                    "105"=>array("name"=>"Gateway", "link"=>"list.php?view=statistic_gateway", "image"=>"images/network_device.png", "title"=>"",), 
                                    "110"=>array("name"=>"Video Adapter", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=video", "image"=>"images/display.png", "title"=>"",),
                                    "120"=>array("name"=>"Monitor", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=monitor", "image"=>"images/display.png", "title"=>"",),
                                    "130"=>array("name"=>"Soundcard", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=sound", "image"=>"images/audio.png", "title"=>"",),
                                    "140"=>array("name"=>"Keyboard and Mouse", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=keyboard,mouse", "image"=>"images/keyboard.png", "title"=>"",),
                                    "150"=>array("name"=>"Modem", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=modem", "image"=>"images/modem.png", "title"=>"",),
                                    "160"=>array("name"=>"Battery", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=battery", "image"=>"images/battery.png", "title"=>"",),
                                    "170"=>array("name"=>"Printer", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=printer", "image"=>"images/printer.png", "title"=>"",),
                                    "180"=>array("name"=>"USB", "link"=>"system.php?pc=$pc&amp;view=hardware&amp;category=usb", "image"=>"images/usb.png", "title"=>"",),
                              ),
              ),
      "20" => array("name"=>"Software",
                    "link"=>"list.php?pc=$pc&amp;view=software_for_system",
                    "image"=>"images/software.png",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"Installed Software", "link"=>"list.php?pc=$pc&amp;view=software_for_system", "image"=>"images/software.png", "title"=>"",),
									"11"=>array("name"=>"All Software versions", "link"=>"list.php?pc=$pc&amp;view=softwareapps_for_system", "image"=>"images/software.png", "title"=>"Softwareversionen",),
									"12"=>array("name"=>"Installed ModernApps", "link"=>"list.php?pc=$pc&amp;view=softwareapps_for_system", "image"=>"images/software.png", "title"=>"Modern Apps",),
                                    "20"=>array("name"=>"System Components", "link"=>"list.php?pc=$pc&amp;view=syscomp_for_system", "image"=>"images/settings_2.png", "title"=>"",),
                                    "30"=>array("name"=>"Hotfixes &amp; Patches", "link"=>"list.php?pc=$pc&amp;view=hotfixes_patches_for_system", "image"=>"images/software_2.png", "title"=>"",),
                                    "40"=>array("name"=>"Run at Startup", "link"=>"list.php?pc=$pc&amp;view=startupsoftware_for_system", "image"=>"images/scsi.png", "title"=>"",),
                                    "50"=>array("name"=>"Software Audit-Trail", "link"=>"list.php?pc=$pc&amp;view=software_audit_system_trail", "image"=>"images/audit.png", "title"=>"",),
                                    "60"=>array("name"=>"Uninstalled Software", "link"=>"list.php?pc=$pc&amp;view=software_uninstalled_for_system", "image"=>"images/audit.png", "title"=>"",),
                                    "70"=>array("name"=>"Keys", "link"=>"list.php?pc=$pc&amp;view=keys_for_system", "image"=>"images/key_2.png", "title"=>"",),
                                    "80"=>array("name"=>"IE BHO's", "link"=>"list.php?pc=$pc&amp;view=ie_bho_for_system", "image"=>"images/browser_bho.png", "title"=>"",),
                                    "90"=>array("name"=>"Codecs", "link"=>"list.php?pc=$pc&amp;view=codecs_for_system", "image"=>"images/audio.png", "title"=>"",),
                                    "100"=>array("name"=>"Services", "link"=>"list.php?pc=$pc&amp;view=services_for_system", "image"=>"images/services.png", "title"=>"",),
                           ),
              ),
      "30" => array("name"=>"OS Settings",
                    "link"=>"system.php?pc=$pc&amp;view=os",
                    "image"=>"images/os.png",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"All", "link"=>"system.php?pc=$pc&amp;view=os", "image"=>"images/statistics.png", "title"=>"",),
                                    "20"=>array("name"=>"OS Information", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=os", "image"=>"images/os.png", "title"=>"",),
                                    "30"=>array("name"=>"Software", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=software", "image"=>"images/software.png", "title"=>"",),
                                    "40"=>array("name"=>"Shared Drives", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=shares", "image"=>"images/shared_drive.png", "title"=>"",),
                                    "50"=>array("name"=>"Scheduled Tasks", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=scheduled_tasks", "image"=>"images/os.png", "title"=>"",),
                                    "60"=>array("name"=>"Env. Variables", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=env_variables", "image"=>"images/software.png", "title"=>"",),
                                    "70"=>array("name"=>"Event Logs", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=event_logs", "image"=>"images/shared_drive.png", "title"=>"",),
                                    "80"=>array("name"=>"IP Routes", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=ip_routes", "image"=>"images/shared_drive.png", "title"=>"",),
                                    "90"=>array("name"=>"Pagefile", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=pagefile", "image"=>"images/shared_drive.png", "title"=>"",),
                                    "100"=>array("name"=>"Mapped Drives", "link"=>"system.php?pc=$pc&amp;view=os&amp;category=mapped", "image"=>"images/shared_drive.png", "title"=>"",),

                             ),
              ),
      "50" => array("name"=>"Security",
                    "link"=>"system.php?pc=$pc&amp;view=security",
                    "image"=>"images/security.png",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"All", "link"=>"system.php?pc=$pc&amp;view=security&amp;category=", "image"=>"images/statistics.png", "title"=>"",),
                                    "20"=>array("name"=>"Firewall", "link"=>"system.php?pc=$pc&amp;view=security&amp;category=win_firewall,firewall_other", "image"=>"images/firewall.png", "title"=>"",),
                                    "30"=>array("name"=>"Antivirus", "link"=>"system.php?pc=$pc&amp;view=security&amp;category=antivirus_xp,antivirus_other", "image"=>"images/antivirus.png", "title"=>"",),
                                    "40"=>array("name"=>"Automatic Updating", "link"=>"system.php?pc=$pc&amp;view=security&amp;category=auto_updating", "image"=>"images/scsi.png", "title"=>"",),
                                    "50"=>array("name"=>"Portscan", "link"=>"system.php?pc=$pc&amp;view=security&amp;category=nmap", "image"=>"images/nmap.png", "title"=>"",),
                              ),
              ),
      "60" => array("name"=>"Users &amp; Groups",
                    "link"=>"system.php?pc=$pc&amp;view=users_groups",
                    "image"=>"images/users_2.png",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"All", "link"=>"system.php?pc=$pc&amp;view=users_groups&amp;category=", "image"=>"images/statistics.png", "title"=>"",),
                                    "20"=>array("name"=>"Users", "link"=>"system.php?pc=$pc&amp;view=users_groups&amp;category=users", "image"=>"images/users.png", "title"=>"",),
                                    "30"=>array("name"=>"Groups", "link"=>"system.php?pc=$pc&amp;view=users_groups&amp;category=groups", "image"=>"images/groups.png", "title"=>"",),
                              ),
              ),
      "70" => array("name"=>"IIS Settings",
                    "link"=>"system.php?pc=$pc&amp;view=iis",
                    "image"=>"images/browser.png",
                    "title"=>"",
              ),
      "80" => array("name"=>"Disk Usage Graphs",
                    "link"=>"system_graphs.php?pc=$pc",
                    "image"=>"images/harddisk.png",
                    "title"=>"",
              ),
      "90" => array("name"=>"Audit Trail",
                    "link"=>"./list.php?pc=$pc&amp;view=audit_trail_for_system",
                    "image"=>"images/audit.png",
                    "title"=>"",
              ),
  ),
  "misc" => array(

      "10" => array("name"=>"List Hardware", "image"=>"images/iconviewdoc.gif",
                    "link"=>"./list.php?view=all_systems_more",
                    "title"=>"Total Computers",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"All Audited Systems", "link"=>"./list.php?view=all_systems", "image"=>"images/computer.png", "title"=>"All Audited Systems",),
                                    "20"=>array("name"=>"All Systems More Info", "link"=>"./list.php?view=all_systems_more", "image"=>"images/computer.png", "title"=>"All Audited Systems More Info",),
                                    "25"=>array("name"=>"All Systems Manual Fields", "link"=>"./list.php?view=all_systems_manual_fields", "image"=>"images/computer.png", "title"=>"All Audited Systems More Info",),
                                    "30"=>array("name"=>"All Servers", "link"=>"./list.php?view=all_servers", "image"=>"images/server.png", "title"=>"All Servers",),
                                    "40"=>array("name"=>"All Win-Workstations", "link"=>"./list.php?view=all_win_workstations", "image"=>"images/computer_2.png", "title"=>"All Win-Workstations",),
                                    "50"=>array("name"=>"All Laptops", "link"=>"./list.php?view=all_laptops", "image"=>"images/laptop.png", "title"=>"All Laptops",),
                                    "210"=>array("name"=>"All Partitions", "link"=>"./list.php?view=all_partition_space", "image"=>"images/partition.png", "title"=>"All Partitions",),
                                    "220"=>array("name"=>"All Hard Disks", "link"=>"./list.php?view=all_hard_disks", "image"=>"images/harddisk_l.png", "title"=>"All Hard Disks",),
                              ),
              ),
      "11" => array("name"=>"List Software", "image"=>"images/software_2.png",
                    "link"=>"list.php?view=all_software",
                    "title"=>"Total Computers",
                    "class"=>"menuparent",
                    "childs"=>array("60"=>array("name"=>"All Software", "link"=>"./list.php?view=all_software", "image"=>"images/software_2.png", "title"=>" All Software",),
									"65"=>array("name"=>"All Software short", "link"=>"./list.php?view=all_software-short", "image"=>"images/software_2.png", "title"=>" All Software Shortlist",),
									"70"=>array("name"=>"All Software w/ Hosts", "link"=>"./list.php?view=all_software_hosts", "image"=>"images/software_2.png", "title"=>"All Software with Hosts",),
                                    "71"=>array("name"=>"All Software versions", "link"=>"./list.php?view=all_softwareversionen", "image"=>"images/software.png", "title"=>"All Software versions imported from PB",),
                                    "72"=>array("name"=>"All Modern Apps", "link"=>"./list.php?view=all_softwareapps", "image"=>"images/software_2.png", "title"=>" All Modern Apps",),
                                    "80"=>array("name"=>"All SW Patches", "link"=>"./list.php?view=all_hotfixes_patches", "image"=>"images/software.png", "title"=>"All Hotfixes &amp; Patches",),
                                    "85"=>array("name"=>"All SW Patches w/ Hosts", "link"=>"./list.php?view=all_hotfixes_patches", "image"=>"images/software.png", "title"=>"All Hotfixes &amp; Patches with Hosts",),
                                    "90"=>array("name"=>"All Anti Virus Status", "link"=>"./list.php?view=all_systems_virus_uptodate", "image"=>"images/o_firewall.png", "title"=>" All Anti Virus Software",),
                                    "100"=>array("name"=>"All IE BHO's", "link"=>"./list.php?view=all_ie_bho", "image"=>"images/browser_bho.png", "title"=>"All IE Browser-Helper-Objects",),
                                    "110"=>array("name"=>"All Services", "link"=>"./list.php?view=all_services", "image"=>"images/services.png", "title"=>"All Services",),
                                    "120"=>array("name"=>"All Scheduled Tasks", "link"=>"list.php?pc=$pc&amp;view=all_sch_tasks", "image"=>"images/sched_task_l.png", "title"=>"",),
                                    "130"=>array("name"=>"All Software Keys", "link"=>"./list.php?view=all_keys", "image"=>"images/key_2.png", "title"=>"All Keys",),
                                    "140"=>array("name"=>"All MS Office Keys", "link"=>"./list.php?view=keys_for_software&amp;type=office%&amp;headline_addition=Office", "image"=>"images/key_1.png", "title"=>"All Office Keys",),
                                    "150"=>array("name"=>"All MS Windows Keys", "link"=>"./list.php?view=keys_for_software&amp;type=windows%&amp;headline_addition=Windows", "image"=>"images/key_3.png", "title"=>"All Windows Keys",),
                                    "160"=>array("name"=>"All MS Windows Shares", "link"=>"./list.php?view=all_network_shares", "image"=>"images/shared_drive_l.png", "title"=>"All Windows Shares by Host",),
                                    "170"=>array("name"=>"All MS Windows Admins", "link"=>"./list.php?view=all_win_admins", "image"=>"images/users.png", "title"=>"All Admins by Host",),
                                    "180"=>array("name"=>"All Mapped Drives", "link"=>"./list.php?view=all_mapped_drives", "image"=>"images/shared_drive_l.png", "title"=>"All Mapped Drives by Host",),
                                    "190"=>array("name"=>"All LDAP Systems", "link"=>"./list.php?view=ldap_computers", "image"=>"images/computer.png", "title"=>"All LDAP Audited Systems",),
                                    "200"=>array("name"=>"All LDAP Users", "link"=>"./list.php?view=ldap_users", "image"=>"images/users.png", "title"=>"All LDAP Audited Users",),
                              ),
              ),


	  "20" => array("name"=>"Other Items","image"=>"images/iconphone.gif",
                    "link"=>"list.php?view=other_networked",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"Printers", "link"=>"./list.php?view=all_printers", "image"=>"images/printer.png", "title"=>"List all Printer",),
                                    "20"=>array("name"=>"Monitors", "link"=>"./list.php?view=all_monitors", "image"=>"images/display.png", "title"=>"List all monitors",),
                                    "30"=>array("name"=>"Modems", "link"=>"./list.php?view=all_modems", "image"=>"images/modem.png", "title"=>"List all Modems",),								
                                    "40"=>array("name"=>"Networked Items", "link"=>"./list.php?view=other_networked", "image"=>"images/network_device.png", "title"=>"List all other networked devices",),
                                    "50"=>array("name"=>"Non-Networked", "link"=>"./list.php?view=other_non_networked", "image"=>"images/non_network.png", "title"=>"List all other non-networked devices",),
                                    "60"=>array("name"=>"All Other Devices", "link"=>"./list.php?view=other_all", "image"=>"images/non_network.png", "title"=>"List all other devices",),
                              ),
              ),
      "28" => array("name"=>"Statistics","image"=>"images/iconstatistics.gif",
                    "link"=>"list.php?view=statistic_gateway",
                    "class"=>"menuparent",
                    "childs"=>array(
                                    "11"=>array("name"=>"Gateway", "link"=>"list.php?view=statistic_gateway", "image"=>"images/network_device.png", "title"=>"Netzwerke-Übersicht",), 
									"12"=>array("name"=>"OS Type", "link"=>"./list.php?view=statistic_os", "image"=>"images/os.png", "title"=>"OS Type",),
                                    "13"=>array("name"=>"GoogleChrome Versions", "link"=>"./list.php?view=statistic_chrome", "image"=>"images/browser_gc.png", "title"=>"Google Chrome Versions",),
                                    "14"=>array("name"=>"Edge on Chromium Versions", "link"=>"./list.php?view=statistic_edgenew", "image"=>"images/browser-newedge.png", "title"=>"Edge on Chromium Versions",),
                                    "15"=>array("name"=>"IE Versions", "link"=>"./list.php?view=statistic_ie", "image"=>"images/browser.png", "title"=>"Internet Explorer Versions",),
                                    "21"=>array("name"=>"Firefox Versions", "link"=>"./list.php?view=statistic_firefox", "image"=>"images/browser_ff.png", "title"=>"Mozilla Firefox Versions",),
                                    "22"=>array("name"=>"Java Versions", "link"=>"./list.php?view=statistic_java", "image"=>"images/java.png", "title"=>"Java Versions",),
                                    "23"=>array("name"=>"Thunderbird Versions", "link"=>"./list.php?view=statistic_thunderbird", "image"=>"images/mail_tb.png", "title"=>"Mozilla Thunderbird Versions",),
                                    "24"=>array("name"=>"Adobe Reader Versions", "link"=>"./list.php?view=statistic_adobe_reader", "image"=>"images/adobe_reader.png", "title"=>"Adobe Reader Versions",),
                                    "25"=>array("name"=>"Libreoffice Versions", "link"=>"./list.php?view=statistic_openoffice.org", "image"=>"softwarelogos/libreoffice.png", "title"=>"Libreoffice Versions",),
                                    "26"=>array("name"=>"Msoffice Versions", "link"=>"./list.php?view=statistic_microsoftoffice", "image"=>"softwarelogos/microsoft.png", "title"=>"Microsoft Office Versions",),
                                    "29"=>array("name"=>"Adobe Flash Player Versions", "link"=>"./list.php?view=statistic_adobe_flash_player", "image"=>"images/adobe_flash_player.png", "title"=>"Adobe Flash Player Versions",),
                                    "30"=>array("name"=>"Printer Models", "link"=>"list.php?view=statistic_printer", "image"=>"images/printer.png", "title"=>"Printer Models",), 
                                    "31"=>array("name"=>"Memory Size", "link"=>"./list.php?view=statistic_memory", "image"=>"images/memory.png", "title"=>"Memory Size",),
                                    "40"=>array("name"=>"Processor Types", "link"=>"./list.php?view=statistic_processor", "image"=>"images/processor.png", "title"=>"Processor Types",),
                                    "50"=>array("name"=>"Hard Drive", "link"=>"./list.php?view=statistic_harddrive", "image"=>"images/harddisk.png", "title"=>"Hard Drive",),
                                    "60"=>array("name"=>"Keys", "link"=>"./list.php?view=statistic_keys", "image"=>"images/key_2.png", "title"=>"Keys",),
                                    "80"=>array("name"=>"Model", "link"=>"list.php?view=statistic_model", "image"=>"images/computer.png", "title"=>"Modelle",), 
                                    "90"=>array("name"=>"Manufacturer", "link"=>"list.php?view=statistic_manufacturer", "image"=>"images/computer.png", "title"=>"Hersteller",), 
                              ),
              ),
      "30" => array("name"=>"Discovered Ports","image"=>"images/iconnumber.gif",
                    "link"=>"list.php?view=all_nmap_ports",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"All Active Ports", "link"=>"./list.php?view=all_nmap_ports", "image"=>"images/nmap.png", "title"=>"Active ports discovered by NMAP scan",),
                                    "20"=>array("name"=>"All Active Ports with hosts", "link"=>"./list.php?view=nmap_ports_hosts", "image"=>"images/nmap.png", "title"=>"Active ports discovered by NMAP scan with hosts",),
                                    "30"=>array("name"=>"Active Ports on Systems", "link"=>"./list.php?view=all_nmap_ports_systems", "image"=>"images/nmap.png", "title"=>"Active ports discovered by NMAP scan on systems",),
                                    "40"=>array("name"=>"Active Ports with Systems", "link"=>"./list.php?view=nmap_ports_systems", "image"=>"images/nmap.png", "title"=>"Active ports discovered by NMAP scan with systems",),
                                    "50"=>array("name"=>"Active Ports on Other Hosts", "link"=>"./list.php?view=all_nmap_ports_other", "image"=>"images/nmap.png", "title"=>"Active ports discovered by NMAP scan on other systems",),
                                    "60"=>array("name"=>"Active Ports with Other Hosts", "link"=>"./list.php?view=nmap_ports_other", "image"=>"images/nmap.png", "title"=>"Active ports discovered by NMAP scan with other systems",),

                             ),
              ),
      "40" => array("name"=>"Software Register","image"=>"images/iconsoftware.gif",
                    "link"=>"./list.php?view=software_register",
                    "class"=>"menuparent",
                    "childs"=>array("10"=>array("name"=>"Software Register", "link"=>"./list.php?view=software_register", "image"=>"images/software.png", "title"=>"",),
									"12"=>array("name"=>"Add Licenses", "link"=>"./software_register.php", "image"=>"images/software.png", "title"=>"",),
                                    "20"=>array("name"=>"Add Software", "link"=>"./software_register_add.php", "image"=>"images/software_2.png", "title"=>"",),
                                    "30"=>array("name"=>"Delete Software", "link"=>"./software_register_del.php", "image"=>"images/software_3.png", "title"=>"",),
                              ),
              ),
      "60" => array("name"=>"Admin", "image"=>"images/iconsettings.gif",
                    "link"=>"import_svversion.php",
                    "class"=>"menuparent",
                    "childs"=>array(
                    				"10"=>array("name"=>"Import SVVersionen", "link"=>"import_svversion.php", "image"=>"images/database.gif", "title"=>"Softwareversions and license Database import",),
                    				"12"=>array("name"=>"Select Database", "link"=>"changedatabase.php", "image"=>"images/database.gif", "title"=>"Select what database of installed dbs to use",),
									"14"=>array("name"=>"Delete/Create new database", "link"=>"./setup.php", "image"=>"images/notes.png", "title"=>"",),
                    				"20"=>array("name"=>"Config", "link"=>"admin_config.php?sub=1", "image"=>"images/settings.png", "title"=>"",),
                                    "30"=>array("name"=>"Add a System", "link"=>"admin_pc_add_1.php?sub=1", "image"=>"images/add.png", "title"=>"",),
                                    "31"=>array("name"=>"Add NMAP-Scanned", "link"=>"admin_nmap_input.php?sub=1", "image"=>"images/add.png", "title"=>"",),
                                    "40"=>array("name"=>"Delete Systems", "link"=>"./delete_systems.php", "image"=>"images/delete.png", "title"=>"",),
                                    "45"=>array("name"=>"Delete Systems older " . $days_systems_not_audited ." days", "link"=>"./delete_missed_audits.php", "image"=>"images/delete.png", "title"=>"",),
                                    "50"=>array("name"=>"Delete Other Items", "link"=>"./delete_other_systems.php", "image"=>"images/delete.png", "title"=>"",),
                                    "60"=>array("name"=>"Audit My Machine", "link"=>"launch_local_audit.php", "image"=>"images/audit.png", "title"=>"Download and Run the Audit Script from your machine.",),
                                    "70"=>array("name"=>"Backup Database", "link"=>"database_backup_form.php", "image"=>"images/tape.png", "title"=>"",),
                                    "80"=>array("name"=>"Restore Database", "link"=>"database_restore_form.php", "image"=>"images/tape.png", "title"=>"",),
                                    "90"=>array("name"=>"View Event Log", "link"=>"./list.php?view=event_log", "image"=>"images/notes.png", "title"=>"",),
                            ),
              ),

      "65" => array("name"=>"PHPMyAdmin", "image"=>"images/database.gif",
                    "link"=>"/phpmyadmin",
                    "class"=>"menuparent",
              ),

      "68" => array("name"=>"Wordpress-BLOG", "image"=>"images/action_run.png",
                    "link"=>"/wordpress",
                    "class"=>"menuparent",
              ),

      "70" => array("name"=>"Help", "image"=>"images/iconinfo.gif",
                    "link"=>"./show_tips.php",
                    "class"=>"menuparent",
                    "childs"=>array("60"=>array("name"=>"Help Tips&Tricks", "link"=>"./show_tips.php", "image"=>"images/notes.png", "title"=>"Tipps und Tricks anzeigen",),
                                    "50"=>array("name"=>"GPL License Display", "link"=>"./show_license.php", "image"=>"images/notes.png", "title"=>"GPL license text",),
                             ),       
              ),
  ),
);

// Add in the following entry for Auditing the LDAP if necessary.
if ((isset($use_ldap_integration))and($use_ldap_integration == 'y')) {
 $menue_array['misc']['60']['childs']['100']=array("name"=>"Audit LDAP Directory", "link"=>"ldap_audit_script.php", "image"=>"images/o_PDA.png", "title"=>"Audit the LDAP Directory.",);
};
if ((isset($show_dell_warranty ))and($show_dell_warranty  == 'y')) {
 $menue_array['misc']['10']['childs']['230']=array("name"=>"All Dell Warranty", "link"=>"./list.php?view=all_dell_warranty", "image"=>"images/notes_l.png", "title"=>"All Dell Warranty.",);
};


?>
