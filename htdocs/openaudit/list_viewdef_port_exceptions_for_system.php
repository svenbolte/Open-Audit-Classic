<?php
    $query_array=array("headline"=>__("List Windows Firewall Port Exceptions for "),
                       "sql"=>"SELECT * FROM firewall_ports where port_uuid = '".$_REQUEST["pc"]."' AND port_timestamp = '".$GLOBALS["timestamp"]."' ",
                       "sort"=>"port_profile, port_number",
                       "dir"=>"ASC",
                       "fields"=>array("10"=>array("name"=>"port_profile",
                                                   "head"=>__("Profile"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "20"=>array("name"=>"port_number",
                                                   "head"=>__("Port"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "30"=>array("name"=>"port_protocol",
                                                   "head"=>__("Protocol"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "40"=>array("name"=>"port_scope",
                                                   "head"=>__("Scope"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                       "50"=>array("name"=>"port_enabled",
                                                   "head"=>__("State"),
                                                   "show"=>"y",
                                                   "link"=>"n",
                                                  ),
                                      ),
                      );
?>
