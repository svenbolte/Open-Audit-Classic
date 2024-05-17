<?php

$query_array=array("headline"=>__("Statistic for Processors"),
                   "sql"=>"
                           SELECT
                               processor_name,
                               COUNT(*) count_item,
                               ROUND( 100 / (
                                       SELECT count(*)
                                       FROM  processor INNER JOIN system ON
                                           system_uuid=processor_uuid AND system_timestamp=processor_timestamp
                                       )
                                 * COUNT(*)
                               ,$round_to_decimal_places ) AS percentage
                           FROM processor INNER JOIN system ON
                           system_uuid=processor_uuid AND system_timestamp=processor_timestamp
                           GROUP BY processor_name
                           ",
                   "sort"=>"count_item",
                   "dir"=>"DESC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Hosts with this Processor"),
                                "var"=>array("view"=>"systems_for_processor",
                                             "name"=>"%processor_name",
                                             "headline_addition"=>"%processor_name",
                                            ),
                               ),
                   "fields"=>array(
                                   "10"=>array("name"=>"count_item",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"n",
                                              ),
									"20"=>array("name"=>"processor_name",
                                               "head"=>__("Processor"),
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
