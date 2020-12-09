<?php

$query_array=array("headline"=>__("Statistic for Operating Systems"),
                   "sql"=>"SELECT
                               system_os_name,
                               system_os_name AS full_system_os_name,
                               COUNT( system_uuid ) AS count_item,
                               round( 100 / (SELECT count(system_uuid) FROM system  WHERE system_os_name != '') * COUNT( system_uuid ), $round_to_decimal_places ) AS percentage
                           FROM system
                           WHERE (1)
                           GROUP BY system_os_name",
                   "sort"=>"count_item",
                   "dir"=>"DESC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Systems installed this Version of OS"),
                                "var"=>array("name"=>"%full_system_os_name",
                                             "view"=>"systems_for_software",
                                             "headline_addition"=>"%full_system_os_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"system_os_name",
                                               "head"=>__("OS"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "20"=>array("name"=>"count_item",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"n",
                                              ),
                                   "30"=>array("name"=>"percentage",
                                               "head"=>__("Percentage"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"n",
                                              ),
                                  ),
                  );
?>
