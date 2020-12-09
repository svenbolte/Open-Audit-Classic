<?php

$query_array=array("headline"=>array("name"=>__("Keys"),
                                     "sql"=>"SELECT `system_name` FROM `system` WHERE `system_uuid` = '" . $_REQUEST["pc"] . "'",
                                     ),
                   "sql"=>"SELECT ms_keys_name, ms_keys_cd_key, system_name, net_ip_address, system_uuid FROM ms_keys, system WHERE system_uuid LIKE '".urldecode($_GET["pc"])."%' AND ms_keys_uuid = system_uuid AND ms_keys_timestamp = system_timestamp ",
                   "sort"=>"system_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"list.php",
                                "title"=>"Keys for this Software",
                                "var"=>array("view"=>"keys_for_software",
                                                     "name"=>"%ms_keys_name",
                                                     "headline_addition"=>"%ms_keys_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"ms_keys_name",
                                               "head"=>__("Software"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "20"=>array("name"=>"ms_keys_cd_key",
                                               "head"=>__("Key"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),

                            ),
                  );
?>
