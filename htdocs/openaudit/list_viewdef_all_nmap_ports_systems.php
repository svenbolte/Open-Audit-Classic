<?php
    $query_array=array("headline"=>__("List Nmap discovered ports on systems"),
                        "sql"=>"SELECT count(DISTINCT nmap_id) AS nmap_count, nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version
                                       FROM nmap_ports, system
                                       WHERE nmap_other_id  = system_uuid
                                       GROUP BY nmap_port_number, nmap_port_proto, nmap_port_name, nmap_port_version",
                       "sort"=>"nmap_port_number",
                       "dir"=>"ASC",
                       "get"=>array("file"=>"list.php",
                                    "title"=>__("Systems with this Nmap discovered port"),
                                    "var"=>array("view"=>"systems_for_nmap_port",
                                                 "name"=>"%nmap_port_number",
                                                 "headline_addition"=>"%nmap_port_number",
                                                 "headline_addition1"=>"%nmap_port_proto",
                                                 "headline_addition2"=>"%nmap_port_name",
                                                 "headline_addition3"=>"%nmap_port_version",
                                                ),
                                   ),
                       "fields"=>array("10"=>array("name"=>"nmap_count",
                                                   "head"=>__("Count"),
                                                   "show"=>"y",
                                                   "link"=>"y",
                                                  ),
                                       "20"=>array("name"=>"nmap_port_number",
                                                   "head"=>__("Port"),
                                                   "show"=>"y",
                                                   "link"=>"y",
                                                   "sort"=>"y",
                                                   "search"=>"y",
                                                  ),
                                       "30"=>array("name"=>"nmap_port_proto",
                                                   "head"=>__("Protocol"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                   "sort"=>"y",
                                                   "search"=>"y",
                                                  ),
                                       "40"=>array("name"=>"nmap_port_name",
                                                   "head"=>__("Service"),
                                                   "show"=>"y",
                                                   "link"=>"y",
                                                   "sort"=>"y",
                                                   "search"=>"y",
                                                  ),
                                       "50"=>array("name"=>"nmap_port_version",
                                                   "head"=>__("Version"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                   "sort"=>"y",
                                                   "search"=>"y",
                                                  ),
                                      ),
                      );
?>
