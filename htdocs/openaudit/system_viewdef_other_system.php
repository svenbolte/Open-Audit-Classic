<?php

$query_array=array("name"=>array("name"=>__("Other System"),
                                 "sql"=>"SELECT `other_description` FROM `other` WHERE `other_id` = '".$_REQUEST['other']."' ",
                                ),
                   "image"=>"images/os_l.png",
                   "views"=>array("summary"=>array(
                                                    "headline"=>__("Summary"),
                                                    "sql"=>"SELECT * FROM other WHERE other_id = '".$_REQUEST['other']."'",
                                                    "image"=>"./images/summary_l.png",
                                                    "edit"=>"y",
                                                    "fields"=>array(
                                                                    "05"=>array("name"=>"other_description", "head"=>__("Description"), "edit"=>"y",),
                                                                    "10"=>array("name"=>"other_network_name", "head"=>__("Name"), "edit"=>"y",),
                                                                    "20"=>array("name"=>"other_type", "head"=>__("Type"), "edit"=>"y",),
                                                                    "30"=>array("name"=>"other_linked_pc",
                                                                                "head"=>__("Attached Device"),
                                                                                "edit"=>"y",
                                                                                "head"=>__("Associate with System"),
                                                                                "edit"=>"y",
                                                                                "edit_type"=>"select",
                                                                                "edit_sql"=>"SELECT system_uuid, system_name FROM system WHERE system_uuid!='' ORDER BY system_name",
                                                                                ),
                                                                    "40"=>array("name"=>"","head"=>__("")),
                                                                    "50"=>array("name"=>"other_ip_address", "head"=>__("IP Address"), "edit"=>"y",),
                                                                    "60"=>array("name"=>"other_mac_address", "head"=>__("MAC Address"), "edit"=>"y",),
                                                                    "70"=>array("name"=>"other_first_timestamp", "head"=>__("Date First Audited"), "edit"=>"n",),
                                                                    "80"=>array("name"=>"other_manufacturer", "head"=>__("Manufacturer"), "edit"=>"y",),
                                                                    "90"=>array("name"=>"other_model", "head"=>__("Model"), "edit"=>"y",),
                                                                    "100"=>array("name"=>"other_serial", "head"=>__("Serial"), "edit"=>"y",),
                                                                    "110"=>array("name"=>"other_location", "head"=>__("Location"), "edit"=>"y",),
                                                                    "120"=>array("name"=>"other_date_purchased", "head"=>__("Date of Purchase"), "edit"=>"y",),
                                                                    "130"=>array("name"=>"other_value", "head"=>__("Dollar Value"), "edit"=>"y",),
                                                                    "150"=>array("name"=>"other_p_port_name", "head"=>__("Port-Name"), "edit"=>"y",),
                                                                   ),
                                                    ),
                                   "nmap"=>array(
                                                    "headline"=>__("Nmap discovered on Host"),
                                                    "sql"=>"SELECT * from nmap_ports WHERE nmap_other_id = '" . $_REQUEST["other"] . "' ORDER BY nmap_port_number",
                                                    "table_layout"=>"horizontal",
                                                    "image"=>"./images/nmap_l.png",
                                                    "fields"=>array("10"=>array("name"=>"nmap_port_number", "head"=>__("Port"),),
                                                                    "20"=>array("name"=>"nmap_port_proto", "head"=>__("Protocol"),),
                                                                    "30"=>array("name"=>"nmap_port_name", "head"=>__("Service"),),
                                                                    "40"=>array("name"=>"nmap_port_version", "head"=>__("Version"),),
                                                                   ),
                                                    ),
                                    "management"=>array(
                                                        "headline"=>__("Remote Management"),
                                                        "sql"=>"SELECT * FROM other WHERE other_id = '".$_REQUEST['other']."'",
                                                        "image"=>"./images/display_l.png",
                                                        "print"=>"n",
                                                        "fields"=>array("10"=>array("name"=>"VNC",
                                                                                    "head"=>__("VNC"),
                                                                                    "get"=>array("head"=>"VNC-Session",
                                                                                                 "file"=>"launch_other.php",
                                                                                                 "title"=>__("VNC-Session"),
                                                                                                 "image"=>"./images/o_load_balancer.png",                                                                                         
                                                                                                 "image_width"=>"16",
                                                                                                 "image_height"=>"16",
                                                                                                 "var"=>array("hostname"=>"%other_ip_address",
                                                                                                              "application"=>"$vnc_type"."_"."vnc",
                                                                                                              "ext"=>"vnc",
                                                                                                             ),
                                                                                                ),
                                                                                    ),
                                                                        "20"=>array("name"=>"HTTP",
                                                                                    "head"=>__("HTTP"),
                                                                                    "get"=>array("head"=>"HTTP-Session",
                                                                                                 "file"=>"launch_other.php",
                                                                                                 "title"=>__("HTTP-Session"),
                                                                                                 "image"=>"./images/os_l.png",                                                                                         
                                                                                                 "image_width"=>"16",
                                                                                                 "image_height"=>"16",
                                                                                                 "target"=>"_BLANK",
                                                                                                 "var"=>array("hostname"=>"%other_ip_address",
                                                                                                               "application"=>"http",
                                                                                                             ),
                                                                                                ),
                                                                                    ),
                                                                        "30"=>array("name"=>"HTTPS",
                                                                                    "head"=>__("HTTPS"),
                                                                                    "get"=>array("head"=>"HTTPS-Session",
                                                                                                 "file"=>"launch_other.php",
                                                                                                 "title"=>__("HTTPS-Session"),
                                                                                                 "image"=>"./images/browser.png",                                                                                         
                                                                                                 "image_width"=>"16",
                                                                                                 "image_height"=>"16",
                                                                                                 "target"=>"_BLANK",
                                                                                                 "var"=>array("hostname"=>"%other_ip_address",
                                                                                                              "application"=>"https",
                                                                                                             ),
                                                                                                ),
                                                                                    ),
                                                                        "40"=>array("name"=>"FTP",
                                                                                    "head"=>__("FTP"),
                                                                                    "get"=>array("head"=>"FTP-Session",
                                                                                                 "file"=>"launch_other.php",
                                                                                                 "title"=>__("FTP-Session"),
                                                                                                 "image"=>"./images/shared_drive_l.png",                                                                                         
                                                                                                 "image_width"=>"16",
                                                                                                 "image_height"=>"16",
                                                                                                 "target"=>"_BLANK",
                                                                                                 "var"=>array("hostname"=>"%other_ip_address",
                                                                                                              "application"=>"ftp",
                                                                                                             ),
                                                                                                ),
                                                                                    ),
                                                                         "50"=>array("name"=>"Telnet",
                                                                                     "head"=>__("Telnet"),
                                                                                     "get"=>array("head"=>"Telnet",
                                                                                                  "file"=>"launch_other.php",
                                                                                                  "image"=>"./images/o_load_balancer.png",                                                                                         
                                                                                                  "image_width"=>"16",
                                                                                                  "image_height"=>"16",
                                                                                                  "title"=>__("Telnet system"),
                                                                                                  "target"=>"_BLANK",
                                                                                                  "var"=>array("hostname"=>"%other_ip_address",
                                                                                                               "application"=>"telnet",
                                                                                                               "ext"=>"vbs",
                                                                                                               ),
                                                                                                    ),
                                                                                     ),
                                                                        ),
                                                ),
                                ),
                  );
?>
