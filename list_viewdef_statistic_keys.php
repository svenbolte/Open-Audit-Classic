<?php

$query_array=array("headline"=>__("Statistic for Software Keys"),
                   "sql"=>"
                           SELECT
                               DISTINCT ms_keys_cd_key,
                               COUNT( * ) AS count_item,
                               ROUND( 100 / (
                                       SELECT count(ms_keys_cd_key) FROM ms_keys, system
                                       WHERE
                                           ms_keys_uuid = system_uuid AND ms_keys_uuid = system_uuid AND ms_keys_timestamp = system_timestamp
                                       )
                                 * COUNT( * )
                               , $round_to_decimal_places) AS percentage,ms_keys_name,ms_keys_release
                               FROM
                                   ms_keys, system
                               WHERE
                                    ms_keys_uuid = system_uuid AND
                                    ms_keys_uuid = system_uuid AND
                                    ms_keys_timestamp = system_timestamp AND
                                    ms_keys_cd_key != ''
                               GROUP BY ms_keys_cd_key
                               ",
                   "sort"=>"count_item",
                   "dir"=>"DESC",
                   "fields"=>array("10"=>array("name"=>"ms_keys_cd_key",
                                               "head"=>__("CD Key"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "20"=>array("name"=>"ms_keys_name",
                                               "head"=>__("Name"),
                                               "show"=>"y",
                                               "link"=>"n",
                                              ),
                                   "30"=>array("name"=>"count_item",
                                               "head"=>__("Count"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"n",
                                              ),
                                   "40"=>array("name"=>"percentage",
                                               "head"=>__("Percentage"),
                                               "show"=>"y",
                                               "link"=>"n",
                                               "search"=>"n",
                                              ),
                                  ),
                  );
?>
