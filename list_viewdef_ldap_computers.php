<?php
$query_array=array("headline"=>__("All LDAP audited systems"),
                   "sql"=>"SELECT ldap_computers_cn, ldap_computers_description, ldap_computers_os, ldap_computers_service_pack, ldap_computers_timestamp, ldap_computer_status, ldap_connections_name, ldap_connections_fqdn, system_uuid
													FROM (
													(SELECT ldap_computers_cn, ldap_computers_description, ldap_computers_os, ldap_computers_service_pack, ldap_computers_timestamp, 'deleted' as ldap_computer_status, ldap_connections_name, ldap_connections_fqdn
													FROM ldap_computers
													LEFT JOIN ldap_paths on ldap_computers.ldap_computers_path_id=ldap_paths.ldap_paths_id
													LEFT JOIN ldap_connections on ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id
													WHERE ldap_computers_timestamp<>ldap_paths_timestamp)
													UNION 
													(SELECT ldap_computers_cn, ldap_computers_description, ldap_computers_os, ldap_computers_service_pack, ldap_computers_timestamp, 'active' as ldap_computer_status, ldap_connections_name, ldap_connections_fqdn
													FROM ldap_computers
													LEFT JOIN ldap_paths on ldap_computers.ldap_computers_path_id=ldap_paths.ldap_paths_id
													LEFT JOIN ldap_connections on ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id 
													WHERE ldap_computers_timestamp=ldap_paths_timestamp)
													) AS U 
													LEFT JOIN system on ldap_computers_cn=system.system_name
													WHERE (ldap_connections_fqdn=net_domain OR ldap_connections_name=net_domain OR net_domain IS NULL)",
                   "sort"=>"ldap_computers_cn",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"system.php",
                                "title"=>__("Go to System"),
                                "var"=>array("pc"=>"%system_uuid",
                                             "view"=>"summary",
                                            ),
                               ),								
										"fields"=>array(
																	"1"=>array("name"=>"system_uuid",
                                               "head"=>__("UUID"),
                                               "show"=>"n",
                                              ),
																	"5"=>array("name"=>"ldap_computer_status",
                                               "head"=>__("Status"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
																	"10"=>array("name"=>"ldap_computers_cn",
                                               "head"=>__("Full Name"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "20"=>array("name"=>"ldap_computers_description",
                                               "head"=>__("Description"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                   "25"=>array("name"=>"ldap_computers_os",
                                               "head"=>__("Operating System"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
																							 ),
                                   "26"=>array("name"=>"ldap_computers_service_pack",
                                               "head"=>__("Service Pack"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
																							 ),
                                   "40"=>array("name"=>"ldap_connections_name",
                                               "head"=>__("LDAP Connection"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                   "50"=>array("name"=>"ldap_computers_timestamp",
                                               "head"=>__("Date Audited"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                  ),
                  );
?>
