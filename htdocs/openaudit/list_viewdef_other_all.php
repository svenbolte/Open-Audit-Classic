<?php

$query_array=array("headline"=>__("List all Other Equipment"),
                   "sql"=>"SELECT other_id, other_ip_address, other_mac_address,other_network_name,other_location,other_manufacturer, other_type, other_description, other_linked_pc FROM other WHERE other_ip_address <> ' Not-Networked'",
                   "sort"=>"other_ip_address",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"system.php",
                                "title"=>__("Go to System"),
                                "var"=>array("other"=>"%other_id",
                                             "view"=>"other_system",
                                            ),
                               ),
                   "fields"=>array(
                                   "10"=>array("name"=>"other_ip_address",
                                               "head"=>__("IP"),
                                               "width"=>"160",
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "20"=>array("name"=>"other_network_name",
                                               "head"=>__("Attached Device"),
                                               "width"=>"160",
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"system.php",
                                                            "title"=>__("Go to System"),
                                                            "var"=>array("pc"=>"%other_linked_pc",
                                                                         "view"=>"summary",
                                                                        ),
                                                           ),
                                              ),
                                   "30"=>array("name"=>"other_type",
                                               "head"=>__("Type"),
                                               "align"=>"center",
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "32"=>array("name"=>"other_manufacturer",
                                               "head"=>__("Manufacturer"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "34"=>array("name"=>"other_location",
                                               "head"=>__("Ort-Details"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "40"=>array("name"=>"other_description",
                                               "head"=>__("Description"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "50"=>array("name"=>"other_mac_address",
                                               "head"=>__("MAC Address"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                  ),
                  );
?>
