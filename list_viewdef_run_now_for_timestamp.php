<?php
include "include_config.php";

$timestamp = date('d/m/y h:i:s a',$_GET['timestamp']);

    $query_array=array("headline"=> "Run Now Log for \"{$_GET['name']}\" on {$timestamp}",
                       "sql"=>"SELECT * FROM audit_log
                               WHERE audit_log_config_id   = '{$_GET['config_id']}' AND
                                     audit_log_timestamp   = '{$_GET['timestamp']}' AND
                                     audit_log_schedule_id = 'none'",
                       "sort"=>"audit_log_id",
                       "dir"=>"ASC",
                       "get"=>array("file"=>"search.php",
                                    "title"=>__("Run Now Log For Configuration on Date/Time"),
                                    "var"=>array( "search_field"=>"%audit_log_host" ),
                                   ),
                       "fields"=>array("10"=>array("name"=>"audit_log_message",
                                                   "head"=>__("Log Message"),
                                                   "show"=>"y",
                                                  ),
                                       "20"=>array("name"=>"audit_log_host",
                                                   "head"=>__("Hostname"),
                                                   "show"=>"y",
                                                   "link"=>"y",
                                                  ),
                                       "30"=>array("name"=>"audit_log_pid",
                                                   "head"=>__("PID"),
                                                   "show"=>"y",
                                                  ),
                                       "40"=>array("name"=>"audit_log_time",
                                                   "head"=>__("Timestamp"),
                                                   "show"=>"y",
                                                  ),
                                      ),
                      );
?>
