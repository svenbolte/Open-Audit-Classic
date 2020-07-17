<?php

    $query_array=array("headline"=>__("List Nmap discovered ports with hosts"),
                       "sql"=>"SELECT nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version, system_uuid, system_name, net_ip_address, system_os_name, system_system_type
                                      FROM (SELECT nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version, system_uuid, system_name, net_ip_address, system_os_name, system_system_type
                                                   FROM nmap_ports, system
                                                   WHERE nmap_other_id = system_uuid
                                                   GROUP BY nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version, system_name
                                            UNION
                                            SELECT nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version, other_id, other_network_name, other_ip_address, other_description, other_type
                                                   FROM nmap_ports, other
                                                   WHERE nmap_other_id = other_mac_address OR nmap_other_id = other_id
                                                   GROUP BY nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version, other_network_name)
                                      AS TempTable1
                                      GROUP BY nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version, system_name",
                       "sort"=>"nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version, system_name",
                       "dir"=>"ASC",
                       "get"=>array("file"=>"system.php",
                                    "title"=>"Go to System",
                                    "var"=>array("pc"=>"%system_uuid",
                                                 "view"=>"summary",
                                                ),
                                   ),
                       "fields"=>array("10"=>array("name"=>"nmap_port_number",
                                                   "head"=>__("Port"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "20"=>array("name"=>"nmap_port_proto",
                                                   "head"=>__("Protocol"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "30"=>array("name"=>"nmap_port_name",
                                                   "head"=>__("Service"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "40"=>array("name"=>"nmap_port_version",
                                                   "head"=>__("Version"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "50"=>array("name"=>"system_name",
                                                   "head"=>__("Hostname"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "60"=>array("name"=>"net_ip_address",
                                                   "head"=>__("IP address"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "70"=>array("name"=>"system_os_name",
                                                   "head"=>__("Description"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "80"=>array("name"=>"system_system_type",
                                                   "head"=>__("Type"),
                                                   "show"=>"n",
                                                   "link"=>"n",
                                                  ),                                             
                                      ),
                      );
?>