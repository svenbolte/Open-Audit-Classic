<?php
/**********************************************************************************************************
Changes:

[Edoardo]	30/01/2008	New page
					
**********************************************************************************************************/
$query_array=array("headline"=>__("List All Hard Disks"),
                   "sql"=>"SELECT * FROM hard_drive, system WHERE hard_drive_uuid = system_uuid AND hard_drive_timestamp = system_timestamp ",
                   "sort"=>"system_name, hard_drive_index",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"system.php",
                                "title"=>__("Go to System"),
                                "var"=>array("pc"=>"%system_uuid",
                                             "view"=>"summary",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"system_uuid",
                                               "head"=>__("UUID"),
                                               "show"=>"n",
                                              ),
                                   "20"=>array("name"=>"net_ip_address",
                                               "head"=>__("IP"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "30"=>array("name"=>"system_name",
                                               "head"=>__("Hostname"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                    "40"=>array("name"=>"net_user_name",
                                               "head"=>__("Network User"),
                                               "show"=>"n",
                                               "link"=>"n",
                                              ),
                                   "50"=>array("name"=>"hard_drive_manufacturer",
                                               "head"=>__("Manufacturer"),
                                               "show"=>"n",
                                               "link"=>"n",
                                              ),
                                   "60"=>array("name"=>"hard_drive_interface_type",
                                               "head"=>__("Type"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "70"=>array("name"=>"hard_drive_caption",
                                               "head"=>__("Caption"),
                                               "show"=>"n",
                                               "link"=>"n",
                                              ),
                                   "80"=>array("name"=>"hard_drive_index",
                                               "head"=>__("Index"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "90"=>array("name"=>"hard_drive_model",
                                               "head"=>__("Model"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "100"=>array("name"=>"hard_drive_size",
                                               "head"=>__("Size"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "110"=>array("name"=>"hard_drive_partitions",
                                               "head"=>__("Partitions"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "120"=>array("name"=>"hard_drive_status",
                                               "head"=>__("Status"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "130"=>array("name"=>"hard_drive_predicted_failure",
                                               "head"=>__("S.M.A.R.T. Failure Predicted"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                  ),
                  );
?>
