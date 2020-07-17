<?php
$sql = "SELECT system_os_type FROM system WHERE system_uuid = '" . $_REQUEST["pc"] . "'";
$result = mysqli_query($db,$sql);
$myrow = mysqli_fetch_array($result);
if ($myrow['system_os_type'] <> 'Linux'){

$query_array=array("headline"=>array("name"=>__("Installed Modern Apps"),
                                     "sql"=>"SELECT `system_name` FROM `system` WHERE `system_uuid` = '" . $_REQUEST["pc"] . "'",
                                     ),
                   "sql"=>"SELECT * FROM softwapps, system WHERE system_uuid = '".$_REQUEST["pc"]."' AND software_name NOT LIKE '%hotfix%' AND software_name NOT LIKE '%Service Pack%' AND software_name NOT REGEXP '[KB|Q][0-9]{6,}' AND software_uuid = system_uuid AND software_timestamp = system_timestamp GROUP BY software_name, software_version ",
                   "sort"=>"software_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Systems installed this Version of this Software"),
                                "var"=>array("name"=>"%software_name",
                                             "version"=>"%software_version",
                                             "view"=>"systems_for_software_version",
                                             "headline_addition"=>"%software_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"software_name",
                                               "head"=>__("Name"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"list.php",
                                                            "title"=>__("Systems installed this Software"),
                                                            "var"=>array("name"=>"%software_name",
                                                                         "view"=>"systems_for_software",
                                                                         "headline_addition"=>"%software_name",
                                                                        ),
                                                           ),
                                              ),
                                   "20"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),

                                   "30"=>array("name"=>"software_publisher",
                                               "head"=>__("Publisher"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"%software_url",
                                                            "title"=>__("External Link"),
                                                            "target"=>"_BLANK",
                                                           ),
                                              ),
                                    "40"=>array("name"=>"software_first_timestamp",
                                               "head"=>__("First Audited"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
									"50"=>array("name"=>"software_location",
                                               "head"=>__("Location"),
                                               "show"=>"n",
                                               "link"=>"n",
                                              ),
									"60"=>array("name"=>"software_install_source",
                                               "head"=>__("Install Source"),
                                               "show"=>"n",
                                               "link"=>"n",
                                              ),
									"70"=>array("name"=>"software_uninstall",
                                               "head"=>__("Uninstall String"),
                                               "show"=>"n",
                                               "link"=>"n",
                                              ),
                                  ),
                  );


} else {
// Only select major Linux packages.
$query_array=array("headline"=>array("name"=>__("Installed Software"),
                                     "sql"=>"SELECT `system_name` FROM `system` WHERE `system_uuid` = '" . $_REQUEST["pc"] . "'",
                                     ),
"sql"=>"SELECT software_name, software_version, software_publisher, software_url FROM software, system WHERE system_uuid = '".$_REQUEST["pc"]."' 
AND ( software_name = 'acroread' OR software_name = 'amarok' OR software_name = 'apache2' OR software_name = 'apt'
 OR software_name = 'azureus' OR software_name = 'banshee' OR software_name = 'bash' OR software_name = 'bzip2'
 OR software_name = 'compiz' OR software_name = 'cpp' OR software_name = 'cron' OR software_name = 'cupsys'
 OR software_name = 'dpkg' OR software_name = 'eclipse' OR software_name = 'epiphany-browser' OR software_name = 'esound' 
 OR software_name = 'firefox' OR software_name = 'ftp' OR software_name = 'gaim' OR software_name = 'gawk'
 OR software_name = 'gcc' OR software_name = 'gedit' OR software_name = 'gimp' OR software_name = 'gnome-about' 
 OR software_name = 'gnomebaker' OR software_name = 'gzip' OR software_name = 'hal' 
 OR software_name = 'inkscape' OR software_name = 'iptables' OR software_name = 'kdelibs-bin' 
 OR software_name = 'linux-386' OR software_name = 'linux-686' OR software_name = 'linux-686-smp' 
 OR software_name = 'make' OR software_name = 'metacity' OR software_name = 'mono' OR software_name = 'mozilla-browser' 
 OR software_name = 'mplayer' OR software_name = 'mysql-admin' OR software_name = 'mysql-query-browser' 
 OR software_name = 'mysql-server-4.0' OR software_name = 'mysql-server-4.1' OR software_name = 'mysql-server-5.0' 
 OR software_name = 'nano' OR software_name = 'nautilus' OR software_name = 'netcat' OR software_name = 'nvidia-glx' 
 OR software_name = 'nmap' OR software_name = 'ntp' OR software_name = 'ntp-server' OR software_name = 'openoffice.org' 
 OR software_name = 'openssh-client' OR software_name = 'openssh-server' OR software_name = 'openssl' OR software_name = 'opera' 
 OR software_name = 'parted' OR software_name = 'perl' OR software_name = 'php4' OR software_name = 'php5' 
 OR software_name = 'python' OR software_name = 'python2.4' OR software_name = 'rapidsvn' OR software_name = 'rdesktop' 
 OR software_name = 'rhythmbox' OR software_name = 'rsync' OR software_name = 'ruby' OR software_name = 'samba' 
 OR software_name = 'sed' OR software_name = 'sendmail' OR software_name = 'smbclient' OR software_name = 'sound-juicer' 
 OR software_name = 'spamassassin' OR software_name = 'sudo' OR software_name = 'sun-java5-bin' OR software_name = 'synaptic' 
 OR software_name = 'sysvinit' OR software_name = 'tar' OR software_name = 'telnet' OR software_name = 'totem' 
 OR software_name = 'udev' OR software_name = 'vim' OR software_name = 'vlc' OR software_name = 'w32codecs' 
 OR software_name = 'webmin' OR software_name = 'wget' OR software_name = 'x11-common' OR software_name = 'xserver-xorg' 
 OR software_name = 'xvncviewer' OR software_name = 'zenity')
AND software_uuid = system_uuid AND software_timestamp = system_timestamp GROUP BY software_name, software_version ",
#                   "sql"=>"SELECT software_name, software_version, software_publisher, software_url FROM software, system WHERE system_uuid = '".$_REQUEST["pc"]."' AND software_name NOT LIKE '%hotfix%' AND software_name NOT LIKE '%update%' AND software_name NOT LIKE '%Service Pack%' AND software_uuid = system_uuid AND software_timestamp = system_timestamp GROUP BY software_name, software_version ",
                   "sort"=>"software_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Systems installed this Version of this Software"),
                                "var"=>array("name"=>"%software_name",
                                             "version"=>"%software_version",
                                             "view"=>"systems_for_software_version",
                                             "headline_addition"=>"%software_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"software_name",
                                               "head"=>__("Software Name"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"list.php",
                                                            "title"=>__("Systems installed this Software"),
                                                            "var"=>array("name"=>"%software_name",
                                                                         "view"=>"systems_for_software",
                                                                         "headline_addition"=>"%software_name",
                                                                        ),
                                                           ),
                                              ),
                                   "20"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),

                                   "30"=>array("name"=>"software_publisher",
                                               "head"=>__("Publisher"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"%software_url",
                                                            "title"=>__("External Link"),
                                                            "target"=>"_BLANK",
                                                           ),
                                              ),
                                  ),
                  );
} # End of else for Linux test
?>
