<?php

$query_array=array("headline"=>__("Statistic for Microsoft Office Versions und Visio"),
                   "sql"=>"
                           SELECT
                               DISTINCT software_name, software_version, software_location,
                               COUNT( software_name ) AS count_item,
                               round( 100 / (
                                       SELECT count(software_name) FROM software, system
                                       WHERE
                                           (software_name LIKE 'Microsoft Office%' or
											software_name LIKE 'Microsoft Visio%' OR
											software_name LIKE 'Microsoft 365%') AND
                                           software_timestamp=system_timestamp AND
                                           software_uuid=system_uuid
                                       )
                                 * COUNT( * )
                               ,$round_to_decimal_places ) AS percentage
                               FROM
                                   software, system
                               WHERE
                                           (software_name LIKE 'Microsoft Office%' or
											software_name LIKE 'Microsoft Visio%' OR
											software_name LIKE 'Microsoft 365%') AND
                                           software_timestamp=system_timestamp AND
                                           software_uuid=system_uuid
                               GROUP BY software_name,software_version
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
                   "fields"=>array( "10"=>array("name"=>"count_item",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"n",
                                              ),	
								"12"=>array("name"=>"software_name",
                                               "head"=>__("Name"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
									"14"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
								 "16"=>array("name"=>"software_location",
                                               "head"=>__("Installdir"),
                                               "show"=>"y",
                                               "link"=>"y",
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
