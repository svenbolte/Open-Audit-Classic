<?php
/**********************************************************************************************************
Module:	list_viewdef_evet_log.php

Description:
	Viewdef for system event log
		
Change Control:
	
	[Nick Brown]	02/03/2009
	SQL query now sorted by "DESC" rather than "ASC"
	
**********************************************************************************************************/
$query_array=array("headline"=>__("Open Audit Event Log"),
                   "sql"=>"SELECT * FROM log",
                   "sort"=>"log_timestamp",
                   "dir"=>"DESC",
										"fields"=>array(
																	"5"=>array("name"=>"log_timestamp",
                                               "head"=>__("Time Stamp"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
																	"10"=>array("name"=>"log_module",
                                               "head"=>__("Module"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "20"=>array("name"=>"log_function",
                                               "head"=>__("Function"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                   "40"=>array("name"=>"log_message",
                                               "head"=>__("Event Message"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                   "50"=>array("name"=>"log_severity",
                                               "head"=>__("Severity"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"y",
                                              ),
                                  ),
                  );
?>
