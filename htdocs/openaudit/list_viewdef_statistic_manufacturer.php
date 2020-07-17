<?php

$query_array=array("headline"=>__("Statistic for Manufacturer"),
                   "sql"=>"SELECT system_vendor,
													COUNT( system_uuid ) AS count_item,
													round( 100 /
													(
													SELECT count(system_uuid)
													FROM system  WHERE system_vendor != ''
													) * COUNT( system_uuid ), 2 ) AS percentage
													FROM system
													GROUP BY system_vendor",
                   "sort"=>"count_item",
                   "dir"=>"DESC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Systems installed by this manufacturer"),
                                "var"=>array("name"=>"%full_system_os_name",
                                             "view"=>"systems_for_software",
                                             "headline_addition"=>"%full_system_os_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"system_vendor",
                                               "head"=>__("Manufacturer"),
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
