<?php

$query_array=array("headline"=>__("List all Windows Workstations"),
                   "sql"=>"SELECT * FROM `system` WHERE ((system_system_type = 'Other' OR system_system_type = 'Unknown' OR system_system_type = 'Desktop' OR system_system_type = 'Low Profile Desktop' OR system_system_type = 'Pizza Box' OR system_system_type = 'Mini Tower' OR system_system_type = 'Tower' OR system_system_type = 'All in One' OR system_system_type = 'Space-Saving' OR system_system_type = 'Lunch Box' OR system_system_type = 'Sealed-Case PC'
						 OR system_system_type = 'Embedded PC' OR system_system_type = 'Mini PC' OR system_system_type = 'Stick PC' ) AND system_os_name NOT LIKE '%Server%' )",
                   "table"=>"`system`",
                   "sort"=>"system_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"system.php",
                                "title"=>__("Go to System"),
                                "var"=>array("pc"=>"%system_uuid",
                                             "view"=>"summary",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"system_uuid",
                                               "head"=>__("UUID"),
                                               "show"=>"n",
                                              ),

                                   "20"=>array("name"=>"net_ip_address",
                                               "head"=>__("IP"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "30"=>array("name"=>"system_name",
                                               "head"=>__("Hostname"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                    "40"=>array("name"=>"net_user_name",
                                               "head"=>__("Network User"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                    "50"=>array("name"=>"net_domain",
                                               "head"=>__("Domain"),
                                               "show"=>$show_domain,
                                              ),
                                   "60"=>array("name"=>"system_os_name",
                                               "head"=>__("OS"),
                                               "show"=>$show_os,
                                              ),
                                   "70"=>array("name"=>"system_service_pack",
                                               "head"=>__("Servicepack"),
                                               "show"=>$show_service_pack,
                                              ),
                                   "80"=>array("name"=>"system_timestamp",
                                               "head"=>__("Date Audited"),
                                               "show"=>$show_date_audited,
                                              ),
                                   "90"=>array("name"=>"system_system_type",
                                               "head"=>__("System Type"),
                                               "show"=>$show_type,
                                               "align"=>"center",
                                              ),
                                   "92"=>array("name"=>"system_vendor",
                                               "head"=>__("Vendor"),
                                               "show"=>"y",
                                              ), 
                                   "94"=>array("name"=>"system_model",
                                               "head"=>__("Model"),
                                               "show"=>"y",
                                              ),           
                                   "96"=>array("name"=>"system_id_number",
                                               "head"=>__("Serial #"),
                                               "show"=>"y",
                                              ),

                                   "100"=>array("name"=>"system_description",
                                               "head"=>__("Description"),
                                               "show"=>$show_description,
                                              ),

                                  ),
                  );
?>
