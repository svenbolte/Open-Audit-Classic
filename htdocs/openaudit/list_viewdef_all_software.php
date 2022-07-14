<?php
$query_array=array("headline"=>__("List all Software"),
                   "sql"=>"SELECT (LENGTH(software.software_name) - LENGTH(REPLACE(software.software_name, ' ', '')) + 1) AS swordct, count(software_name) AS software_count, software_name, softwareversionen.sv_bemerkungen, softwareversionen.sv_lizenztyp, softwareversionen.sv_version, software_version, software_publisher, software_url, software_comment, software_first_timestamp
						FROM system, software  
						LEFT JOIN softwareversionen
						ON ( ( (LENGTH(software.software_name) - LENGTH(REPLACE(software.software_name, ' ', '')) + 1) >= 2
							AND LOWER(SUBSTRING_INDEX(software.software_name, ' ', 2))
							LIKE LOWER(SUBSTRING_INDEX(softwareversionen.sv_product, ' ', 2)) )
						OR  ( (LENGTH(software.software_name) - LENGTH(REPLACE(software.software_name, ' ', '')) + 1) = 1
							AND LOWER(SUBSTRING_INDEX(software.software_name, ' ', 1))
							LIKE LOWER(SUBSTRING_INDEX(softwareversionen.sv_product, ' ', 1)) )
						)
						WHERE software_name NOT LIKE '%hotfix%'
						AND software_name NOT LIKE '%Service Pack%' 
						AND software_name NOT LIKE '% Updater%'
						AND software_name NOT LIKE '%MUI (%' AND software_name NOT LIKE '%Proofing %' AND software_name NOT LIKE '%Language%' AND software_name NOT LIKE '%Korrektur%' AND software_name NOT LIKE '%linguisti%' AND software_name NOT REGEXP 'SP[1-4]{1,}' AND software_name NOT REGEXP '[KB|Q][0-9]{6,}' AND software_uuid = system_uuid AND software_timestamp = system_timestamp GROUP BY software_name, software_version ",
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

								   "41"=>array("name"=>"sv_bemerkungen",
                                               "head"=>__("Anmerkungen"),
                                               "show"=>"n",
                                               "link"=>"n",
											   "search"=>"y",
                                              ),

								   "42"=>array("name"=>"software_comment",
                                               "head"=>__("Comment"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "search"=>"y",
                                              ),

								   "44"=>array("name"=>"sv_lizenztyp",
                                               "head"=>__("Lizenztyp"),
                                               "show"=>"y",
                                               "link"=>"n",
											   "search"=>"y",
                                              ),

                                   "46"=>array("name"=>"software_publisher",
                                               "head"=>__("Publisher"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"%software_url",
                                                            "title"=>__("External Link"),
                                                            "target"=>"_BLANK",
                                                           ),
                                              ),

								  "50"=>array("name"=>"software_first_timestamp",
                                               "head"=>__("First installed"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),

							  ),
                  );
?>
