<?php
include "include_config.php";
if  ((isset($ldap_full_details)) and ($ldap_full_details!='y')){
                        $ldap_full_details = 'n';
                        } 
$query_array=array("headline"=>__("All LDAP audited users"),
                   "sql"=>"SELECT * FROM (
                                                    (SELECT ldap_connections_name, ldap_users_cn, ldap_users_description, ldap_users_department, ldap_users_timestamp, 'deleted' as ldap_user_status 
                                                    FROM ldap_users 
                                                    LEFT JOIN ldap_paths ON ldap_users.ldap_users_path_id=ldap_paths.ldap_paths_id 
                                                    LEFT JOIN ldap_connections ON ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id 
                                                    WHERE ldap_users_timestamp<>ldap_paths_timestamp)
                                                    UNION 
                                                    (SELECT ldap_connections_name, ldap_users_cn, ldap_users_description, ldap_users_department, ldap_users_timestamp, 'active' as ldap_user_status 
                                                    FROM ldap_users 
                                                    LEFT JOIN ldap_paths ON ldap_users.ldap_users_path_id=ldap_paths.ldap_paths_id 
                                                    LEFT JOIN ldap_connections ON ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id 
                                                    WHERE ldap_users_timestamp=ldap_paths_timestamp)
                                                    ) AS U ",													
                                        "sort"=>"ldap_users_cn",
                                        "dir"=>"ASC",
                                        "fields"=>array(
                                                                        "5"=>array("name"=>"ldap_user_status",
                                               "head"=>__("Status"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                                                        "10"=>array("name"=>"ldap_users_cn",
                                               "head"=>__("Full Name"),
                                               "show"=>"y",
                                               "link"=>"y",
                                                "get"=>array("file"=>"ldap_details.php",
                                                            "title"=>__("Show Ldap Details"),
                                                            "var"=>array("name"=>"%ldap_users_cn",
                                                                         "full details"=>"$ldap_full_details",
                                                                         "record type"=>"user",                                                                         
                                                                         "headline_addition"=>"%ldap_users_cn",
                                                                        ),
                                                           ),
                                              ),
                                   "20"=>array("name"=>"ldap_users_description",
                                               "head"=>__("Description"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                   "30"=>array("name"=>"ldap_users_department",
                                               "head"=>__("Department"),
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
                                   "50"=>array("name"=>"ldap_users_timestamp",
                                               "head"=>__("Date Audited"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                  ),
                  );
?>
