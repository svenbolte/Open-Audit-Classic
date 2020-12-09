<?php

$query_array=array("headline"=>__("List Audit Trail for Host"),
                   "sql"=>"SELECT * FROM system_audits WHERE system_audits_uuid = '".$_REQUEST["pc"]."' ",
                   "sort"=>"system_audits_timestamp",
                   "dir"=>"DESC",
                   "fields"=>array("10"=>array("name"=>"system_audits_timestamp",
                                               "head"=>__("Date Audited"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "search"=>"n",
                                              ),
                                   "20"=>array("name"=>"system_audits_username",
                                               "head"=>__("Audited User"),
                                               "show"=>"y",
                                               "link"=>"y",
                                               "search"=>"n",
                                              ),
                                  ),
                  );
?>
