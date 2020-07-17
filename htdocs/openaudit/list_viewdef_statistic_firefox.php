<?php

$query_array=array("headline"=>__("Statistic for Mozilla Firefox Versions"),
                   "sql"=>"
                           SELECT
                               DISTINCT software_name, software_version,
                               COUNT( * ) AS count_item,
                               round( 100 / (
                                       SELECT count(software_uuid) FROM software, system
                                       WHERE (software_name LIKE 'Mozilla Firefox%' OR software_name LIKE 'FrontMotion Firefox Community Edition%' OR software_name LIKE 'firefox')
                                              AND (software_name NOT LIKE 'Mozilla Firefox Extension%' AND software_name NOT LIKE 'FrontMotion Firefox Community Edition Extension%') 
                                              AND software_timestamp=system_timestamp AND software_uuid=system_uuid
                                       )
                                 * COUNT( * )
                               ,$round_to_decimal_places ) AS percentage
                               FROM software, system
                               WHERE (software_name LIKE 'Mozilla Firefox%' OR software_name LIKE 'FrontMotion Firefox Community Edition%' OR software_name LIKE 'firefox')
                                      AND (software_name NOT LIKE 'Mozilla Firefox Extension%' AND software_name NOT LIKE 'FrontMotion Firefox Community Edition Extension%') 
                                      AND software_timestamp=system_timestamp AND software_uuid=system_uuid
                               GROUP BY software_version
                               ",
                   "sort"=>"count_item",
                   "dir"=>"DESC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Systems installed this Version of this Software"),
                                "var"=>array("name"=>"%software_name",
                                             "version"=>"%software_version",
                                             "view"=>"systems_for_software_version",
                                             "headline_addition"=>"%software_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
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
