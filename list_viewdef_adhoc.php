<?php

$query_array=array("headline"=>"Ad-Hoc Query",
                   "sql"=>"SELECT software_name, software_version, system_name FROM software, system WHERE software_uuid = system_uuid AND software_timestamp = system_timestamp GROUP BY software_name, software_version ",
                   "sort"=>"software_name",
                   "dir"=>"ASC",
                   "fields"=>array("20"=>array("name"=>"software_name",
                                               "head"=>$l_swf." ".$l_nam,
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "30"=>array("name"=>"system_name",
                                               "head"=>$l_ver,
                                               "show"=>"y",
                                               "link"=>"y",
                                              ),

                                   "40"=>array("name"=>"system_name",
                                               "head"=>$l_pub,
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                  ),
                  );
?>
