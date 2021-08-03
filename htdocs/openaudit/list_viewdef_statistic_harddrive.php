<?php

$query_array=array("headline"=>__("Statistic for Hard Disks"),
                   "sql"=>"
                           SELECT
                               hard_drive_model,hard_drive_size,
                               COUNT(*) AS count_item,
                               ROUND( 100 / (
                                       SELECT count(*)
                                       FROM hard_drive, system WHERE
                                           system_uuid=hard_drive_uuid AND
                                           system_timestamp=hard_drive_timestamp AND
                                           hard_drive_size!=0
                                       )
                                 * COUNT(*)
                               ,$round_to_decimal_places) AS percentage
                           FROM hard_drive, system
                           WHERE
                               system_uuid=hard_drive_uuid AND
                               system_timestamp=hard_drive_timestamp AND
                               hard_drive_size!=0
                           GROUP BY hard_drive_size
                           ",
                   "sort"=>"count_item",
                   "dir"=>"DESC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Hosts with this Hard Drive Size"),
                                "var"=>array("view"=>"systems_for_harddrive",
                                             "hard_drive_size"=>"%hard_drive_size",
                                             "headline_addition"=>"%hard_drive_size",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"hard_drive_size",
                                               "head"=>__("Hard Drive Size"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
									"11"=>array("name"=>"hard_drive_model",
                                               "head"=>__("Type"),
                                               "show"=>"y",
                                               "link"=>"n",
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
