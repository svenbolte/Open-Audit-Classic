<?php

$query_array=array("headline"=>array("name"=>__("Audit-Trail for Software"),
                                     "sql"=>"SELECT `system_name` FROM `system` WHERE `system_uuid` = '" . $_REQUEST["pc"] . "'",
                                     ),
                       "sql"=>"
                              SELECT software_name, software_version, software_first_timestamp, system_name, system_uuid, net_ip_address
                              FROM software, system
                              WHERE software_uuid = system_uuid AND software_uuid = '".$_REQUEST["pc"]."' AND software_timestamp = system_timestamp
                              ",
                   "sort"=>"software_first_timestamp",
                   "dir"=>"DESC",
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
                                               "link"=>"n",
                                              ),

                                   "30"=>array("name"=>"software_first_timestamp",
                                               "head"=>__("First Detected"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                  ),
                  );
?>
