<?php
$query_array=array("headline"=>__("List all Software"),
                   "sql"=>"SELECT count(software_name) AS software_count, software_name, software_version, software_publisher, software_url, software_comment, software_first_timestamp FROM softwapps, system where software_name NOT LIKE '%hotfix%' AND software_name NOT LIKE '%Service Pack%' AND software_name NOT REGEXP '[KB|Q][0-9]{6,}' AND software_uuid = system_uuid AND software_timestamp = system_timestamp GROUP BY software_name, software_version ",
                   "sort"=>"software_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Systems installed this Version of this Software"),
                                "var"=>array("name"=>"%software_name",
                                             "version"=>"%software_version",
                                             "view"=>"systems_for_software_version",
                                             "headline_addition"=>"%software_name",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"software_count",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "sort"=>"y",
                                               "search"=>"n",
                                              ),
                                   "20"=>array("name"=>"software_name",
                                               "head"=>__("Name"),
                                               "show"=>"y",
                                               "link"=>"y",
											   "get"=>array("file"=>"list.php",
                                                            "title"=>__("Systems installed this Software"),
                                                            "var"=>array("name"=>"%software_name",
                                                                         "view"=>"systems_for_softwareapps",
                                                                         "headline_addition"=>"%software_name",
                                                                        ),
                                                           ),
                                              ),
                                   "30"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),

                                   "40"=>array("name"=>"software_publisher",
                                               "head"=>__("Publisher"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "get"=>array("file"=>"%software_url",
                                                            "title"=>__("External Link"),
                                                            "target"=>"_BLANK",
                                                           ),
                                              ),

								   "41"=>array("name"=>"software_comment",
                                               "head"=>__("Comment"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "search"=>"y",
                                              ),

								  "50"=>array("name"=>"software_first_timestamp",
                                               "head"=>__("First installed"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),

							  ),
                  );
?>
