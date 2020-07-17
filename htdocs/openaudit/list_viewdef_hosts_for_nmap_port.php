<?php

    $query_array=array("headline"=>__("List all hosts running Nmap discovered service '".$_REQUEST["headline_addition2"]."' (version '".$_REQUEST["headline_addition3"]."') on ".strtoupper($_REQUEST["headline_addition1"])." port"),
                       "sql"=>"SELECT system_uuid, net_ip_address, system_name, system_os_name, system_system_type
                                      FROM system, nmap_ports
                                      WHERE nmap_port_number = '".$_REQUEST["name"]."' AND nmap_port_proto = '".$_REQUEST["headline_addition1"]."' AND nmap_port_version = '".$_REQUEST["headline_addition3"]."' AND nmap_other_id = system_uuid
                               UNION
                               SELECT other_id, other_ip_address, other_network_name, other_description, other_type
                                      FROM other, nmap_ports
                                      WHERE nmap_port_number = '".$_REQUEST["name"]."' AND nmap_port_proto = '".$_REQUEST["headline_addition1"]."' AND nmap_port_version = '".$_REQUEST["headline_addition3"]."' AND (nmap_other_id = other_mac_address OR nmap_other_id = other_id)",
                       "sort"=>"system_name",
                       "dir"=>"ASC",
                       "get"=>array("file"=>"system.php",
                                    "title"=>"Go to System",
                                    "var"=>array("pc"=>"%system_uuid",
                                                 "view"=>"summary",
                                                ),
                                   ),
                       "fields"=>array("10"=>array("name"=>"system_uuid",
                                                   "head"=>__("UUID"),
                                                   "show"=>"n",
                                                  ),
                                       "20"=>array("name"=>"net_ip_address",
                                                   "head"=>__("IP address"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "30"=>array("name"=>"system_name",
                                                   "head"=>__("Hostname"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "40"=>array("name"=>"system_os_name",
                                                   "head"=>__("Description"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "50"=>array("name"=>"system_system_type",
                                                   "head"=>__("Type"),
                                                   "show"=>"n",
                                                   "link"=>"n",
                                                  ),                                             
                                      ),
                      );
?>