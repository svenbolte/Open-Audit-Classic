<?php

$query_array=array("headline"=>__("List All Monitors"),
                   "sql"=>"SELECT count(monitor_model) AS monitor_count, monitor_id, monitor_model, monitor_manufacturer, monitor_serial, system_name, system_uuid FROM monitor, system WHERE monitor_uuid = system_uuid AND monitor_timestamp = system_timestamp GROUP BY monitor_model, monitor_manufacturer ",
                   "sort"=>"system_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"system.php",
                                "title"=>__("Go to System"),
                                "var"=>array("pc"=>"%system_uuid",
                                             "view"=>"summary",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"monitor_count",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "sort"=>"n",
                                               "search"=>"n",
                                               "get"=>array("file"=>"list.php",
                                                            "title"=>"List all Systems for this Modell and Manufacturer",
                                                            "var"=>array("view"=>"systems_for_monitor_modell_manufacturer",
                                                                         "modell"=>"%monitor_model",
                                                                         "manufacturer"=>"%monitor_manufacturer",
                                                                         "headline_addition"=>"%monitor_model",
                                                                        ),
                                                           ),
                                              ),
                                   "20"=>array("name"=>"other_linked_pc",
                                               "head"=>__("IP"),
                                               "show"=>"n",
                                              ),
                                   "30"=>array("name"=>"system_name",
                                               "head"=>__("Attached Device"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "40"=>array("name"=>"monitor_manufacturer",
                                               "head"=>__("Manufacturer"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"list.php",
                                                            "title"=>__("List all Systems for this Manufacturer"),
                                                            "var"=>array("view"=>"systems_for_monitor_manufacturer",
                                                                         "manufacturer"=>"%monitor_manufacturer",
                                                                         "headline_addition"=>"%monitor_manufacturer",
                                                                        ),
                                                           ),
                                              ),
                                   "50"=>array("name"=>"monitor_model",
                                               "head"=>__("Model"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"list.php",
                                                            "title"=>__("List all Systems for this Modell"),
                                                            "var"=>array("view"=>"systems_for_monitor_modell",
                                                                         "modell"=>"%monitor_model",
                                                                         "headline_addition"=>"%monitor_model",
                                                                        ),
                                                           ),
                                              ),
                                  ),
                  );
?>
