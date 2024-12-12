<?php
$query_array=array("headline"=>__("List all known old Software with hosts"),
                   "sql"=>"
		SELECT software_location, net_user_name, system_name,software.software_name, softwareversionen.sv_product, 
				software_version, softwareversionen.sv_version, softwareversionen.sv_icondata,sv_lizenztyp, software_first_timestamp, (1=1) as sv_newer  
			FROM system,software
			LEFT JOIN softwareversionen
			ON (
			   CONCAT('%', LOWER(RTRIM(Replace(Replace(software.software_name,'(x64)',''),'.',''))) ,'%')      
			   LIKE CONCAT('%', LOWER(RTRIM(Replace(Replace(softwareversionen.sv_product,'(x64)',''),'.',''))) ,'%')
			   )
		WHERE CONCAT(
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(software_version, '.', 1), '.', -1), 15, '0'),
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(software_version, '.', 2), '.', -1), 15, '0'),
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(software_version, '.', 3), '.', -1), 15, '0'), 
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(software_version, '.', 4), '.', -1), 15, '0') 
			   ) <
			   CONCAT(
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(softwareversionen.sv_version , '.', 1), '.', -1), 15, '0'),
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(softwareversionen.sv_version , '.', 2), '.', -1), 15, '0'),
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(softwareversionen.sv_version , '.', 3), '.', -1), 15, '0'), 
				LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(softwareversionen.sv_version , '.', 4), '.', -1), 15, '0') 
			   ) 
				AND software_uuid = system_uuid AND software_timestamp = system_timestamp
				GROUP BY software_name, software_version
",
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
                   "fields"=>array("10"=>array("name"=>"system_uuid",
                                               "head"=>__("UUID"),
                                               "show"=>"n",
                                              ),
                                   "20"=>array("name"=>"software_name",
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
                                   "30"=>array("name"=>"software_version",
                                               "head"=>__("Version"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),

								   "33"=>array("name"=>"sv_version",
                                               "head"=>__("Ver from DB"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "search"=>"n",
                                              ),

								   "34"=>array("name"=>"sv_newer",
                                               "head"=>__("OLD"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "search"=>"n",
											   "sort"=>"n",
                                              ),

								   "36"=>array("name"=>"sv_instlocation",
                                               "head"=>__("SCX"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "sort"=>"y",
                                              ),

                                   "40"=>array("name"=>"system_name",
                                               "head"=>__("Hostname"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"system.php",
                                                            "title"=>__("Go to System"),
                                                            "var"=>array("pc"=>"%system_uuid",
                                                                         "view"=>"summary",
                                                                        ),
                                                           ),
                                              ),
                                    "50"=>array("name"=>"net_user_name",
                                               "head"=>__("Network User"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),

									"60"=>array("name"=>"software_location",
                                               "head"=>__("Installdir"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),

									"65"=>array("name"=>"software_first_timestamp",
                                               "head"=>__("First installed"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),

								   "70"=>array("name"=>"sv_lizenztyp",
                                               "head"=>__("Lizenztyp"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "search"=>"y",
                                              ),
										  
                                  ),
                  );
?>
