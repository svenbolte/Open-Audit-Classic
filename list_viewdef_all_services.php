<?php

$query_array=array("headline"=>__("List all Services"),
                   "sql"=>"SELECT count(service_id) AS service_count, service_display_name, sd_description
                           FROM service, system, service_details
                           WHERE service_uuid  = system_uuid AND service_timestamp = system_timestamp AND sd_display_name = service_display_name
                           GROUP BY service_display_name",
                   "sort"=>"service_display_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Hosts with this Service"),
                                "var"=>array("view"=>"systems_for_service",
                                             "name"=>"%service_display_name",
                                             "headline_addition"=>"%service_display_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"service_count",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "20"=>array("name"=>"service_display_name",
                                               "head"=>__("name"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "sort"=>"y",
                                               "search"=>"y",
                                              ),
                                   "30"=>array("name"=>"sd_description",
                                               "head"=>__("Description"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "sort"=>"y",
                                               "search"=>"y",
                                              ),
                                  ),
                  );
?>
