<?php

$query_array=array("headline"=>__("List all Windows Servers"),
                   "sql"=>"SELECT * FROM `system`, `processor`, `hard_drive` WHERE
						(system_os_name LIKE '%Server%') 
						AND system_uuid = hard_drive_uuid AND system_uuid = processor_uuid AND hard_drive_uuid = processor_uuid AND system_timestamp = processor_timestamp AND system_timestamp = hard_drive_timestamp AND processor_device_id = 'CPU0' AND hard_drive_index = 0 ",
                   "sort"=>"system_name",
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
                                  "32"=>array("name"=>"system_os_name",
                                               "head"=>__("OS"),
                                               "show"=>$show_os,
                                              ),
                                   "38"=>array("name"=>"system_memory",
                                               "head"=>__("RAM"),
                                               "show"=>"y",
                                              ),
                                   "40"=>array("name"=>"hard_drive_size",
                                               "head"=>__("First Disk Space"),
                                               "show"=>"y",
                                              ),
                                   "45"=>array("name"=>"system_system_type",
                                               "head"=>__("System Type"),
                                               "show"=>$show_type,
                                               "align"=>"center",
                                              ),
                                   "50"=>array("name"=>"system_vendor",
                                               "head"=>__("Vendor"),
                                               "show"=>"n",
                                              ), 
                                   "55"=>array("name"=>"system_model",
                                               "head"=>__("Model"),
                                               "show"=>"n",
                                              ),           
                                   "95"=>array("name"=>"processor_name",
                                               "head"=>__("CPU"),
                                               "show"=>"y",
                                              ),
                                   "96"=>array("name"=>"system_num_processors",
                                               "head"=>__("# cpu"),
                                               "show"=>"n",
                                              ),
                                   "97"=>array("name"=>"system_vcpu",
                                               "head"=>__("v cpu"),
                                               "show"=>"n",
                                              ),
                                   "98"=>array("name"=>"system_lcpu",
                                               "head"=>__("l cpu"),
                                               "show"=>"n",
                                              ),
								   "100"=>array("name"=>"system_os_arch",
                                               "head"=>__("OS Arch."),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),		  
                                   "142"=>array("name"=>"system_description",
                                               "head"=>__("Description"),
                                               "show"=>$show_description,
                                              ),
                                   "155"=>array("name"=>"net_user_name",
                                               "head"=>__("Username"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "160"=>array("name"=>"system_id_number",
                                               "head"=>__("Serial #"),
                                               "show"=>"n",
                                              ),
                                   "165"=>array("name"=>"system_language",
                                               "head"=>__("Sprache"),
                                               "show"=>"n",
                                              ),
                                  "167"=>array("name"=>"system_version",
                                               "head"=>__("OS-Version"),
                                               "show"=>"n",
                                              ),
								  "170"=>array("name"=>"system_timestamp",
                                               "head"=>__("Date Audited"),
                                               "show"=>$show_date_audited,
                                              ),
                                  ),
                  );
?>
