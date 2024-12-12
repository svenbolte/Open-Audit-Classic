<?php

$query_array=array("headline"=>__("Statistic for Java Versions"),
                   "sql"=>"
                           SELECT
                               DISTINCT software_name,software_publisher, software_version, software_location,
                               COUNT( * ) AS count_item,
                               round( 100 / (
                                       SELECT count(software_uuid) FROM software, system
                                       WHERE
                                           (software_name LIKE 'Java%' OR
                                           software_name LIKE 'J2SE%' OR
                                           software_name LIKE '%JRE%' OR
											software_name LIKE 'AdoptOpenJDK%') AND
                                           software_timestamp=system_timestamp AND
                                           software_uuid=system_uuid
                                       )
                                 * COUNT( * )
                               ,$round_to_decimal_places ) AS percentage
                               FROM
                                   software, system
                               WHERE
                                    (software_name LIKE 'Java%' OR
                                    software_name LIKE 'J2SE%' OR
                                    software_name LIKE '%JRE%' OR
									software_name LIKE 'AdoptOpenJDK%') AND
                                    software_timestamp=system_timestamp AND
                                    software_uuid=system_uuid
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
                   "fields"=>array(
				                  "9"=>array("name"=>"count_item",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"n",
                                              ),
								 "10"=>array("name"=>"software_name",
                                               "head"=>__("Name"),
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
								"11"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
								 "12"=>array("name"=>"software_publisher",
                                               "head"=>__("Hersteller"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
								 "13"=>array("name"=>"software_location",
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
