<?php
/**********************************************************************************************************
Module:	list_viewdef_all_printers.php

Description:
	
		
Recent changes:
	
	[Edoardo]		19/05/2009	Added share name and location.
	[Edoardo]		05/01/2010	Fixed query (only existing printers are showed).
	[Edoardo]		31/05/2010	Added Driver Name - Suggested by jpa.
	
**********************************************************************************************************/
$query_array=array("headline"=>__("List All Printers"),
                   "sql"=>"SELECT * FROM other, system WHERE other_type = 'printer' AND (other_linked_pc = system_uuid OR other_linked_pc = '') AND other_timestamp = system_timestamp ",
                   "sort"=>"other_network_name",
                   "dir"=>"ASC",
                   "get"=>array("file"=>"system.php",
                                "title"=>__("Go to Printer"),
                                "var"=>array("other"=>"%other_id",
                                             "view"=>"printer",
                                            ),
                               ),
                   "fields"=>array("10"=>array("name"=>"other_linked_pc",
                                               "head"=>__("UUID"),
                                               "show"=>"n",
                                              ),
                                   "20"=>array("name"=>"other_network_name",
                                               "head"=>__("Attached Device"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "get"=>array("file"=>"system.php",
                                                            "title"=>__("Go to System"),
                                                            "var"=>array("pc"=>"%other_linked_pc",
                                                                         "view"=>"summary",
                                                                        ),
                                                           ),
                                              ),
                                   "30"=>array("name"=>"other_description",
                                               "head"=>__("Description"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                              
                                   "40"=>array("name"=>"other_p_port_name",
                                               "head"=>__("Port"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "50"=>array("name"=>"other_p_shared",
                                               "head"=>__("Shared"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                              
                                   "60"=>array("name"=>"other_p_share_name",
                                               "head"=>__("Share Name"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "70"=>array("name"=>"other_location",
                                               "head"=>__("Location"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "80"=>array("name"=>"other_model",
                                               "head"=>__("Driver Name"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                  ),
                  );
?>
