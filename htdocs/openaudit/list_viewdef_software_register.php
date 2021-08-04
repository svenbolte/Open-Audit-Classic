<?php

$query_array=array("headline"=>__("Software License Register"),
                   "sql"=>"SELECT software_reg_id, software_title, count(software.software_name) AS number_used, software_comments,
						IFNULL((SELECT sum(license_purchase_number) as number_purchased FROM 
							software_licenses WHERE 
							software_register.software_reg_id = software_licenses.license_software_id),0) AS purchased,
                        IFNULL((SELECT sum(license_purchase_number) as number_purchased FROM 
						software_licenses WHERE 
						software_register.software_reg_id = software_licenses.license_software_id),0)
                             - IFNULL(count(software.software_name),0) as differenz
				   FROM	software_register, software, system WHERE
						software_title = software_name AND 
						software_uuid = system_uuid AND 
						software_timestamp = system_timestamp 
						GROUP BY software_title",
                   "sort"=>"software_title",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"list.php",
                                "title"=>__("Software License Register"),
                                "var"=>array("name"=>"%software_title",
                                             "view"=>"systems_for_software",
                                             "headline_addition"=>"%software_title",
                                            ),
                               ),
								"fields"=>array(
								"10"=>array("name"=>"software_reg_id",
											"head"=>__("ID"),
                                            "show"=>"y",
                                            "link"=>"n",
                                              ),
                                   "20"=>array("name"=>"software_title",
                                               "head"=>__("Produkt"),
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),
                                   "30"=>array("name"=>"number_used",
                                               "head"=>__("used"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "35"=>array("name"=>"purchased",
                                               "head"=>__("purchased"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "37"=>array("name"=>"differenz",
                                               "head"=>__("difference"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "40"=>array("name"=>"software_comments",
                                               "head"=>__("comment"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                               ),
				 );
				  
?>
