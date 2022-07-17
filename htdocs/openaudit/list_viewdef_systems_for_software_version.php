<?php

$query_array=array("headline"=>__("List Systems for Software/Version"),
                   "sql"=>"SELECT distinct * FROM software, system where software_name = '" . @@$_GET["name"] . "' AND software_version = '" . @$_GET['version'] . "' AND software_uuid = system_uuid AND software_timestamp = system_timestamp group by software_uuid,system_name",
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
                                   "40"=>array("name"=>"software_name",
                                               "head"=>__("Software Name"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                  "50"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                              /*
                                   "60"=>array("name"=>"system_os_name",
                                               "head"=>__("OS"),
                                               "show"=>$show_os,
                                              ),*/
                                   "70"=>array("name"=>"system_timestamp",
                                               "head"=>__("Date Audited"),
                                               "show"=>$show_date_audited,
                                              ),
                                   "80"=>array("name"=>"system_system_type",
                                               "head"=>__("System Type"),
                                               "show"=>$show_type,
                                               "align"=>"center",
                                              ),
                                   "90"=>array("name"=>"system_description",
                                               "head"=>__("Description"),
                                               "show"=>$show_description,
                                              ),
                                   "100"=>array("name"=>"net_domain",
                                               "head"=>__("Domain"),
                                               "show"=>$show_domain,
                                              ),/*
                                   "110"=>array("name"=>"system_service_pack",
                                               "head"=>__("Servicepack"),
                                               "show"=>$show_service_pack,
                                              ),*/
                                  ),
                  );
?>
